<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\District;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        return view('owner.businesses.create', compact('governorates', 'categories'));
    }

    public function store(Request $request)
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
                'description' => 'required|string',
                'district_id' => 'required|exists:districts,id',
                'sub_area_id' => 'required|exists:sub_areas,id',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:categories,id',
                'phone' => 'required|string|regex:/^09[0-9]{8}$/',
                'landline_suffix' => 'nullable|string|regex:/^[0-9]{7}$/',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'address' => 'required|string|max:500',
                'google_maps_link' => 'nullable|url|max:500',
                'logo' => 'required|image|max:2048',
                'images' => 'required|array|min:1|max:16',
                'images.*' => 'required|image|max:2048',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
            ]);

            \Log::info('Business store attempt', ['validated' => $validated]);

            $validated['owner_id'] = Auth::id();

            // Use subcategory if provided, otherwise use main category
            if ($request->filled('subcategory_id')) {
                $validated['category_id'] = $request->subcategory_id;
            }
            unset($validated['subcategory_id']);

            // Handle landline number
            if ($request->filled('landline_suffix')) {
                $validated['landline'] = $request->landline_suffix;
            }
            unset($validated['landline_suffix']);

            // Handle logo upload
            $logoFile = $request->file('logo');
            \Log::info('Logo upload attempt', [
                'original_name' => $logoFile->getClientOriginalName(),
                'size' => $logoFile->getSize(),
                'mime' => $logoFile->getMimeType(),
                'temp_path' => $logoFile->getRealPath(),
            ]);

            $logoPath = $logoFile->store('logos', 'public');
            $validated['logo'] = $logoPath;

            \Log::info('Logo stored', [
                'stored_path' => $logoPath,
                'full_path' => storage_path('app/public/' . $logoPath),
                'exists_after_store' => file_exists(storage_path('app/public/' . $logoPath)),
            ]);

            // Handle images upload
            $imagePaths = [];
            foreach ($request->file('images') as $index => $image) {
                \Log::info('Image upload attempt', [
                    'index' => $index,
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                ]);

                $path = $image->store('business_images', 'public');
                $imagePaths[] = $path;

                \Log::info('Image stored', [
                    'index' => $index,
                    'stored_path' => $path,
                    'exists_after_store' => file_exists(storage_path('app/public/' . $path)),
                ]);
            }
            $validated['images'] = $imagePaths;

            // Remove fields that are not in the database
            unset($validated['facebook'], $validated['instagram']);

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

            $validated['status'] = 'pending';

            \Log::info('Before store', ['validated' => $validated]);

            Business::create($validated);

            \Log::info('After store');

            return redirect()->route('owner.businesses.index')->with('success', 'تم تقديم المنشأة بنجاح وهي بانتظار الموافقة.');
        } catch (\Exception $e) {
            \Log::error('Business store failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة المنشأة: ' . $e->getMessage());
        }
    }

    public function edit(Business $business)
    {
        $this->authorizeOwner($business);

        $governorates = Governorate::with('districts')->get();
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        return view('owner.businesses.edit', compact('business', 'governorates', 'categories'));
    }

    public function update(Request $request, Business $business)
    {
        try {
            $this->authorizeOwner($business);

            // Ensure storage directories exist
            if (!Storage::disk('public')->exists('logos')) {
                Storage::disk('public')->makeDirectory('logos');
            }
            if (!Storage::disk('public')->exists('business_images')) {
                Storage::disk('public')->makeDirectory('business_images');
            }

            \Log::info('Business update attempt', ['business_id' => $business->id, 'request_data' => $request->except(['logo', 'images'])]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'english_name' => 'nullable|string|max:255',
                'description' => 'required|string',
                'district_id' => 'required|exists:districts,id',
                'sub_area_id' => 'nullable|exists:sub_areas,id',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:categories,id',
                'phone' => 'required|string|min:9|max:15',
                'landline_suffix' => 'nullable|string|regex:/^[0-9]{7}$/',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'address' => 'required|string|max:500',
                'google_maps_link' => 'nullable|url|max:500',
                'logo' => 'nullable|image|max:2048',
                'images' => 'nullable|array|max:16',
                'images.*' => 'nullable|image|max:2048',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $logoPath;
            } else {
                // Keep existing logo if not uploaded
                $validated['logo'] = $business->logo;
            }

            // Use subcategory if provided, otherwise use main category
            if ($request->filled('subcategory_id')) {
                $validated['category_id'] = $request->subcategory_id;
            }
            unset($validated['subcategory_id']);

            // Handle landline number
            if ($request->filled('landline_suffix')) {
                $validated['landline'] = $request->landline_suffix;
            } else {
                $validated['landline'] = null;
            }
            unset($validated['landline_suffix']);

            // Handle null sub_area_id
            if (!$request->filled('sub_area_id')) {
                unset($validated['sub_area_id']);
            }

            // Handle images upload
            $currentImages = $business->images ?? [];
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('business_images', 'public');
                    $imagePaths[] = $path;
                }
                $currentImages = array_merge($currentImages, $imagePaths);
            }

            // Handle images deletion
            if ($request->filled('images_to_delete')) {
                $imagesToDelete = json_decode($request->images_to_delete, true);
                $currentImages = array_values(array_diff($currentImages, $imagesToDelete));
            }

            $validated['images'] = $currentImages;

            // Remove fields that are not in the database
            unset($validated['facebook'], $validated['instagram']);

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

            \Log::info('Before update', ['validated' => $validated]);

            $business->update($validated);

            \Log::info('After update', ['business_id' => $business->id]);

            return redirect()->route('owner.businesses.index')->with('success', 'تم تحديث البيانات بنجاح.');
        } catch (\Exception $e) {
            \Log::error('Business update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage());
        }
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
