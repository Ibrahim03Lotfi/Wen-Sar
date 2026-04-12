<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // For business owners, show their businesses
        if ($user->hasRole('owner')) {
            $businesses = $user->businesses()->with(['category', 'subArea'])->get();
            return view('dashboard', compact('businesses'));
        }

        // For regular users, show a simple dashboard
        return view('dashboard');
    }
}
