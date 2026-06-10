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
                'phone' => 'nullable|string|regex:/^[0-9]{8}$/',
                'phones' => 'nullable|array',
                'phones.*' => 'nullable|string|regex:/^[0-9]{8}$/',
                'landlines' => 'nullable|array',
                'landlines.*' => 'nullable|string|regex:/^[0-9]{7}$/',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'address' => 'required|string|max:500',
                'google_maps_link' => 'nullable|url|max:500',
                'logo' => 'required|image',
                'images' => 'required|array|min:1|max:16',
                'images.*' => 'required|image',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
            ]);

            if (app()->environment('local')) {
                \Log::info('Business store attempt', ['validated' => $validated]);
            }

            $validated['owner_id'] = Auth::id();

            // Use subcategory if provided, otherwise use main category
            if ($request->filled('subcategory_id')) {
                $validated['category_id'] = $request->subcategory_id;
            }
            unset($validated['subcategory_id']);

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
            unset($validated['phones_input']);

            // Handle landline numbers
            $landlines = [];
            if ($request->has('landlines')) {
                foreach ($request->landlines as $l) {
                    if ($l) $landlines[] = '011' . $l;
                }
            }
            $validated['landlines'] = !empty($landlines) ? array_values(array_unique($landlines)) : null;
            $validated['landline'] = $landlines[0] ?? null;

            // Handle logo upload with compression
            $logoFile = $request->file('logo');
            if (app()->environment('local')) {
                \Log::info('Logo upload attempt', [
                    'original_name' => $logoFile->getClientOriginalName(),
                    'size' => $logoFile->getSize(),
                    'mime' => $logoFile->getMimeType(),
                ]);
            }

            $logoPath = $this->compressImage($logoFile, 'logos');
            $validated['logo'] = $logoPath;

            if (app()->environment('local')) {
                \Log::info('Logo stored', ['stored_path' => $logoPath]);
            }

            // Handle images upload with compression
            $imagePaths = [];
            foreach ($request->file('images') as $index => $image) {
                if (app()->environment('local')) {
                    \Log::info('Image upload attempt', [
                        'index' => $index,
                        'original_name' => $image->getClientOriginalName(),
                        'size' => $image->getSize(),
                    ]);
                }

                $path = $this->compressImage($image, 'business_images');
                $imagePaths[] = $path;

                if (app()->environment('local')) {
                    \Log::info('Image stored', [
                        'index' => $index,
                        'stored_path' => $path,
                    ]);
                }
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

            if (app()->environment('local')) {
                \Log::info('Before store', ['validated' => $validated]);
            }

            Business::create($validated);

            if (app()->environment('local')) {
                \Log::info('After store');
            }

            return redirect()->route('owner.businesses.index')->with('success', 'تم تقديم المنشأة بنجاح وهي بانتظار الموافقة.');
        } catch (\Exception $e) {
            $logContext = ['error' => $e->getMessage(), 'class' => get_class($e)];
            if (app()->environment('local')) {
                $logContext['trace'] = $e->getTraceAsString();
            }
            \Log::error('Business store failed', $logContext);
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

            if (app()->environment('local')) {
                \Log::info('Business update attempt', ['business_id' => $business->id]);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'english_name' => 'nullable|string|max:255',
                'description' => 'required|string',
                'district_id' => 'required|exists:districts,id',
                'sub_area_id' => 'nullable|exists:sub_areas,id',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:categories,id',
                'phone' => 'nullable|string|regex:/^[0-9]{8}$/',
                'phones' => 'nullable|array',
                'phones.*' => 'nullable|string|regex:/^[0-9]{8}$/',
                'landlines' => 'nullable|array',
                'landlines.*' => 'nullable|string|regex:/^[0-9]{7}$/',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'address' => 'required|string|max:500',
                'google_maps_link' => 'nullable|url|max:500',
                'logo' => 'nullable|image',
                'images' => 'nullable|array|max:16',
                'images.*' => 'nullable|image',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
            ]);

            if (app()->environment('local')) {
                \Log::info('Validation passed', ['validated' => $validated]);
            }

            // Handle logo upload with compression
            if ($request->hasFile('logo')) {
                $logoPath = $this->compressImage($request->file('logo'), 'logos');
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
            if ($request->has('landlines')) {
                foreach ($request->landlines as $l) {
                    if ($l) $landlines[] = '011' . $l;
                }
            }
            $validated['landlines'] = !empty($landlines) ? array_values(array_unique($landlines)) : null;
            $validated['landline'] = $landlines[0] ?? null;

            // Handle null sub_area_id
            if (!$request->filled('sub_area_id')) {
                unset($validated['sub_area_id']);
            }

            // Handle images upload with compression
            $currentImages = $business->images ?? [];
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $this->compressImage($image, 'business_images');
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

            if (app()->environment('local')) {
                \Log::info('Before update', ['validated' => $validated]);
            }

            $business->update($validated);

            if (app()->environment('local')) {
                \Log::info('After update', ['business_id' => $business->id]);
            }

            return redirect()->route('owner.businesses.index')->with('success', 'تم تحديث البيانات بنجاح.');
        } catch (\Exception $e) {
            $logContext = ['error' => $e->getMessage(), 'class' => get_class($e)];
            if (app()->environment('local')) {
                $logContext['trace'] = $e->getTraceAsString();
            }
            \Log::error('Business update failed', $logContext);
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

    /**
     * Compress and save an uploaded image using GD.
     * - Resizes to max 1920px on the longest side (preserves aspect ratio)
     * - Saves as JPEG at quality 75
     * - Returns the storage-relative path (e.g. "logos/abc123.jpg")
     */
    private function compressImage(\Illuminate\Http\UploadedFile $file, string $directory): string
    {
        $filename = $directory . '/' . uniqid() . '_' . time() . '.jpg';
        $fullPath = Storage::disk('public')->path($filename);

        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        // Create GD image resource from uploaded file
        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $source = imagecreatefromjpeg($sourcePath);
        } elseif ($mime === 'image/png') {
            $source = imagecreatefrompng($sourcePath);
        } elseif ($mime === 'image/webp') {
            $source = imagecreatefromwebp($sourcePath);
        } elseif ($mime === 'image/gif') {
            $source = imagecreatefromgif($sourcePath);
        } else {
            // Fallback: try jpeg
            $source = @imagecreatefromjpeg($sourcePath);
            if (!$source) {
                // If GD can't handle it, store as-is
                $file->storeAs($directory, basename($filename), 'public');
                return $filename;
            }
        }

        if (!$source) {
            // GD failed, store original
            $file->storeAs($directory, basename($filename), 'public');
            return $filename;
        }

        $origWidth  = imagesx($source);
        $origHeight = imagesy($source);
        $maxDimension = 1920;

        // Calculate new dimensions keeping aspect ratio
        if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
            if ($origWidth >= $origHeight) {
                $newWidth  = $maxDimension;
                $newHeight = (int) round($origHeight * ($maxDimension / $origWidth));
            } else {
                $newHeight = $maxDimension;
                $newWidth  = (int) round($origWidth * ($maxDimension / $origHeight));
            }
        } else {
            $newWidth  = $origWidth;
            $newHeight = $origHeight;
        }

        // Create resized canvas
        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG/GIF before converting to JPEG
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Save as JPEG at quality 75
        imagejpeg($canvas, $fullPath, 75);

        imagedestroy($source);
        imagedestroy($canvas);

        return $filename;
    }
}