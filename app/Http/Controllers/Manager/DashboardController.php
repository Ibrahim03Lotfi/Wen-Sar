<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use App\Models\Governorate;
use App\Models\District;
use App\Models\SubArea;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_businesses' => Business::where('status', 'pending')->count(),
            'approved_businesses' => Business::where('status', 'approved')->count(),
            'total_owners' => User::whereHas('roles', function($q) {
                $q->where('name', 'owner');
            })->count(),
            'total_governorates' => Governorate::count(),
            'total_districts' => District::count(),
            'total_sub_areas' => SubArea::count(),
        ];

        $recentPending = Business::where('status', 'pending')
            ->with(['owner', 'category', 'district'])
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', compact('stats', 'recentPending'));
    }
}
