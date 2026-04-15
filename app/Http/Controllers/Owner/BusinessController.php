<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\District;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $businesses = $user->businesses()->with(['category', 'district'])->latest()->get();
        return view('owner.businesses.index', compact('businesses'));
    }

    public function create()
    {
        $governorates = Governorate::with('districts')->get();
        $categories = Category::whereNull('parent_id')->get();
        return view('owner.businesses.create', compact('governorates', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'english_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'sub_area_id' => 'required|exists:sub_areas,id',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'required|string|regex:/^09[0-9]{8}$/',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'address' => 'required|string|max:500',
            'google_maps_link' => 'required|url|max:500',
            'logo' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
        ]);

        $validated['owner_id'] = Auth::id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Handle images upload
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('business_images', 'public');
            $imagePaths[] = $path;
        }
        $validated['images'] = $imagePaths;

        // Handle social links
        $socialLinks = [];
        if ($request->filled('facebook')) {
            $socialLinks['facebook'] = $request->facebook;
        }
        if ($request->filled('instagram')) {
            $socialLinks['instagram'] = $request->instagram;
        }
        if (!empty($socialLinks)) {
            $validated['social_links'] = $socialLinks;
        }

        // Remove fields that are not in the database
        unset($validated['facebook'], $validated['instagram']);

        // Set status to pending for manager approval
        $validated['status'] = 'pending';

        Business::create($validated);

        return redirect()->route('owner.businesses.index')->with('success', __('Business submitted successfully and is pending approval.'));
    }

    public function edit(Business $business)
    {
        $this->authorizeOwner($business);

        $governorates = Governorate::with('districts')->get();
        $categories = Category::whereNull('parent_id')->get();
        
        return view('owner.businesses.edit', compact('business', 'governorates', 'categories'));
    }

    public function update(Request $request, Business $business)
    {
        $this->authorizeOwner($business);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'english_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'sub_area_id' => 'required|exists:sub_areas,id',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'required|string|regex:/^09[0-9]{8}$/',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'address' => 'required|string|max:500',
            'google_maps_link' => 'required|url|max:500',
            'logo' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'images' => 'nullable|array|min:1|max:5',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'images_to_delete' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        } else {
            // Keep existing logo if not uploaded
            $validated['logo'] = $business->logo;
        }

        // Handle images upload
        if ($request->hasFile('images')) {
            $existingImages = $business->images ?? [];
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('business_images', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = array_merge($existingImages, $imagePaths);
        } else {
            // Preserve existing images if no new images uploaded
            $validated['images'] = $business->images ?? [];
        }

        // Handle images deletion
        if ($request->filled('images_to_delete')) {
            $imagesToDelete = json_decode($request->images_to_delete, true);
            $currentImages = $validated['images'] ?? [];
            $validated['images'] = array_values(array_diff($currentImages, $imagesToDelete));
        }

        // Remove fields that are not in the database
        unset($validated['facebook'], $validated['instagram'], $validated['images_to_delete']);

        // Handle social links
        $socialLinks = $business->social_links ?? [];
        if ($request->filled('facebook')) {
            $socialLinks['facebook'] = $request->facebook;
        } elseif ($request->has('facebook')) {
            unset($socialLinks['facebook']);
        }
        if ($request->filled('instagram')) {
            $socialLinks['instagram'] = $request->instagram;
        } elseif ($request->has('instagram')) {
            unset($socialLinks['instagram']);
        }
        $validated['social_links'] = $socialLinks;

        $business->update($validated);

        return redirect()->route('owner.businesses.index')->with('success', 'تم تحديث البيانات بنجاح.');
    }

    private function authorizeOwner(Business $business)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($business->owner_id !== Auth::id() && !$user->hasRole('admin')) {
            abort(403);
        }
    }
}
