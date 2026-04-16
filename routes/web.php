<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\Owner\BusinessController as OwnerBusinessController;
use App\Http\Controllers\LanguageController;

Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Manager routes
require __DIR__.'/manager.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories.index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->middleware('throttle:contact')->name('contact.submit');
Route::get('/api/districts/{district}/sub-areas', [DistrictController::class, 'subAreas']);
Route::get('/api/governorates/{governorate}/districts', [DistrictController::class, 'districtsByGovernorate']);
Route::get('/search', [BusinessController::class, 'search'])->name('business.search');
Route::get('/api/search', [BusinessController::class, 'apiSearch'])->name('business.api.search');
Route::get('/business/{business}', [BusinessController::class, 'show'])->name('business.show');
Route::get('/category/{category}', [BusinessController::class, 'category'])->name('business.category');
Route::get('/featured', [BusinessController::class, 'featured'])->name('business.featured');

Route::get('/favorites', [FavoriteController::class, 'index'])->middleware('auth')->name('favorites.index');
Route::post('/favorites/{business}/toggle', [FavoriteController::class, 'toggle'])->middleware('auth')->name('favorites.toggle');
Route::post('/favorites/{business}', [FavoriteController::class, 'store'])->middleware('auth')->name('favorites.store');
Route::delete('/favorites/{business}', [FavoriteController::class, 'destroy'])->middleware('auth')->name('favorites.destroy');
Route::post('/business/{business}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/businesses', [OwnerBusinessController::class, 'index'])->name('businesses.index');
    Route::get('/businesses/create', [OwnerBusinessController::class, 'create'])->name('businesses.create');
    Route::post('/businesses', [OwnerBusinessController::class, 'store'])->name('businesses.store');
    Route::get('/businesses/{business}/edit', [OwnerBusinessController::class, 'edit'])->name('businesses.edit');
    Route::put('/businesses/{business}', [OwnerBusinessController::class, 'update'])->name('businesses.update');
    Route::put('/password', [DashboardController::class, 'updatePassword'])->name('password.update');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:owner'])
    ->name('dashboard');

// Storage debug route
Route::get('/debug/storage', function () {
    $storagePath = storage_path('app/public');
    $publicStoragePath = public_path('storage');
    $logosPath = storage_path('app/public/logos');
    $imagesPath = storage_path('app/public/business_images');

    $isSymlink = is_link($publicStoragePath);
    $symlinkTarget = $isSymlink ? readlink($publicStoragePath) : 'N/A';

    $logos = is_dir($logosPath) ? array_slice(scandir($logosPath), 0, 10) : [];
    $images = is_dir($imagesPath) ? array_slice(scandir($imagesPath), 0, 10) : [];

    // Check database for image paths
    $businesses = \App\Models\Business::select('id', 'name', 'logo', 'images')->limit(5)->get();

    return response()->json([
        'app_url' => config('app.url'),
        'storage_path' => $storagePath,
        'public_storage_path' => $publicStoragePath,
        'is_symlink' => $isSymlink,
        'symlink_target' => $symlinkTarget,
        'storage_exists' => is_dir($storagePath),
        'logos_exists' => is_dir($logosPath),
        'images_exists' => is_dir($imagesPath),
        'sample_logos' => $logos,
        'sample_images' => $images,
        'writable' => is_writable($storagePath),
        'businesses_in_db' => $businesses,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
