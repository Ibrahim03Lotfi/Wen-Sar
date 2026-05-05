<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\District;
use App\Models\SubArea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::where('status', 'approved')
            ->with(['owner', 'category', 'district', 'subArea', 'approvedBy']);

        // Filter by search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by district
        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $businesses = $query->latest()->paginate(20);
        $categories = Category::all();
        $districts = District::all();

        return view('manager.businesses.index', compact('businesses', 'categories', 'districts'));
    }

    public function show(Business $business)
    {
        $business->load(['owner', 'category', 'district', 'subArea', 'approvedBy']);
        return view('manager.businesses.show', compact('business'));
    }

    public function edit(Business $business)
    {
        $categories = Category::all();
        $districts = District::with('subAreas')->get();
        $subAreas = SubArea::where('district_id', $business->district_id)->get();

        return view('manager.businesses.edit', compact('business', 'categories', 'districts', 'subAreas'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'english_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'district_id' => 'required|exists:districts,id',
            'sub_area_id' => 'nullable|exists:sub_areas,id',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|regex:/^[0-9]{8}$/',
            'phones' => 'nullable|array',
            'phones.*' => 'nullable|string|regex:/^[0-9]{8}$/',
            'landline_suffix' => 'nullable|string|regex:/^[0-9]{7}$/',
            'landlines' => 'nullable|array',
            'landlines.*' => 'nullable|string|regex:/^[0-9]{7}$/',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'address' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
        ]);

        // Handle phone numbers
        $phones = [];
        if ($request->filled('phone')) {
            $phones[] = '09' . $request->phone;
        }
        if ($request->has('phones')) {
            foreach ($request->phones as $p) {
                if ($p) $phones[] = '09' . $p;
            }
        }
        $validated['phones'] = !empty($phones) ? array_values(array_unique($phones)) : null;
        $validated['phone'] = $phones[0] ?? null;
        unset($validated['phone']);

        // Handle landline numbers
        $landlines = [];
        if ($request->has('landlines')) {
            foreach ($request->landlines as $l) {
                if ($l) $landlines[] = '011' . $l;
            }
        }
        $validated['landlines'] = !empty($landlines) ? array_values(array_unique($landlines)) : null;
        $validated['landline'] = $landlines[0] ?? null;

        // Handle business hours
        $businessHours = [
            'regular' => [
                'open' => $request->opening_time,
                'close' => $request->closing_time,
            ],
            'closed_days' => $request->closed_days ?? [],
            'overrides' => $request->overrides ?? [],
        ];
        $validated['business_hours'] = $businessHours;

        $business->update($validated);

        return redirect()->route('manager.businesses.index')->with('success', __('Business updated successfully.'));
    }

    public function destroy(Business $business)
    {
        // Delete associated images
        if ($business->logo) {
            \Storage::disk('public')->delete($business->logo);
        }
        if ($business->images) {
            foreach ($business->images as $image) {
                \Storage::disk('public')->delete($image);
            }
        }

        $business->delete();

        return redirect()->route('manager.businesses.index')->with('success', __('Business deleted successfully.'));
    }

    public function createForOwner(User $user)
    {
        $categories = Category::all();
        $districts = District::with('subAreas')->get();

        return view('manager.businesses.create-for-owner', compact('user', 'categories', 'districts'));
    }

    public function storeForOwner(Request $request, User $user)
    {
        try {
            // Ensure storage directories exist
            if (!Storage::disk('public')->exists('logos')) {
                Storage::disk('public')->makeDirectory('logos');
            }
            if (!Storage::disk('public')->exists('business_images')) {
                Storage::disk('public')->makeDirectory('business_images');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'english_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'district_id' => 'required|exists:districts,id',
                'sub_area_id' => 'nullable|exists:sub_areas,id',
                'category_id' => 'required|exists:categories,id',
                'phone' => 'nullable|string|regex:/^[0-9]{8}$/',
                'phones' => 'nullable|array',
                'phones.*' => 'nullable|string|regex:/^[0-9]{8}$/',
                'landlines' => 'nullable|array',
                'landlines.*' => 'nullable|string|regex:/^[0-9]{7}$/',
                'opening_time' => 'nullable|date_format:H:i',
                'closing_time' => 'nullable|date_format:H:i',
                'address' => 'nullable|string|max:500',
                'logo' => 'nullable|image|max:2048',
                'images' => 'nullable|array|max:16',
                'images.*' => 'nullable|image|max:2048',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
            ]);

            $validated['owner_id'] = $user->id;
            $validated['status'] = 'approved';
            $validated['approved_by'] = Auth::guard('manager')->id();
            $validated['approved_at'] = now();

            // Handle phone numbers
            $phones = [];
            if ($request->filled('phone')) {
                $phones[] = '09' . $request->phone;
            }
            if ($request->has('phones')) {
                foreach ($request->phones as $p) {
                    if ($p) $phones[] = '09' . $p;
                }
            }
            $validated['phones'] = !empty($phones) ? array_values(array_unique($phones)) : null;
            $validated['phone'] = $phones[0] ?? null;

            // Handle landline numbers
            $landlines = [];
            if ($request->filled('landline_suffix')) {
                $landlines[] = '011' . $request->landline_suffix;
            }
            if ($request->has('landlines')) {
                foreach ($request->landlines as $l) {
                    if ($l) $landlines[] = '011' . $l;
                }
            }
            $validated['landlines'] = !empty($landlines) ? array_values(array_unique($landlines)) : null;
            $validated['landline'] = $landlines[0] ?? null;
            unset($validated['landline_suffix']);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $logoPath;
            }

            // Handle images upload
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('business_images', 'public');
                    $imagePaths[] = $path;
                }
                $validated['images'] = $imagePaths;
            }

            // Handle social links
            $socialLinks = [];
            if ($request->filled('facebook')) {
                $socialLinks['facebook'] = $request->facebook;
            }
            if ($request->filled('instagram')) {
                $socialLinks['instagram'] = $request->instagram;
            }
            $validated['social_links'] = $socialLinks;

            // Handle business hours
            $businessHours = [
                'regular' => [
                    'open' => $request->opening_time,
                    'close' => $request->closing_time,
                ],
                'closed_days' => $request->closed_days ?? [],
                'overrides' => $request->overrides ?? [],
            ];
            $validated['business_hours'] = $businessHours;

            Business::create($validated);

            return redirect()->route('manager.owners.show', $user)->with('success', __('Business added and approved successfully.'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('Error adding business: ') . $e->getMessage());
        }
    }
}
