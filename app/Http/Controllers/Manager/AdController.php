<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    private const MAX_ADS = 6;

    private function adsTableExists(): bool
    {
        return Schema::hasTable('ads');
    }

    private function migrationMessage(): string
    {
        return __('Ads feature is not available yet. Please run migrations.');
    }

    private function emptyAds(): Collection
    {
        return collect();
    }

    public function index()
    {
        if (!$this->adsTableExists()) {
            return view('manager.ads.index', ['ads' => $this->emptyAds()])
                ->with('error', $this->migrationMessage());
        }

        $ads = Ad::latest()->get();

        return view('manager.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        if (!$this->adsTableExists()) {
            return back()->with('error', $this->migrationMessage());
        }

        if (Ad::count() >= self::MAX_ADS) {
            return back()->with('error', __('Maximum 6 ads are allowed.'));
        }

        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $path = $request->file('image')->store('ads', 'public');

        Ad::create([
            'image_path' => $path,
            'manager_id' => auth('manager')->id(),
        ]);

        return back()->with('success', __('Ad added successfully.'));
    }

    public function update(Request $request, Ad $ad)
    {
        if (!$this->adsTableExists()) {
            return back()->with('error', $this->migrationMessage());
        }

        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $newPath = $request->file('image')->store('ads', 'public');

        if ($ad->image_path && Storage::disk('public')->exists($ad->image_path)) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->update([
            'image_path' => $newPath,
            'manager_id' => auth('manager')->id(),
        ]);

        return back()->with('success', __('Ad updated successfully.'));
    }

    public function destroy(Ad $ad)
    {
        if (!$this->adsTableExists()) {
            return back()->with('error', $this->migrationMessage());
        }

        if ($ad->image_path && Storage::disk('public')->exists($ad->image_path)) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return back()->with('success', __('Ad deleted successfully.'));
    }
}
