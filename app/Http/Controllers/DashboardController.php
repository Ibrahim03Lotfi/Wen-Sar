<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('كلمة المرور الحالية غير صحيحة')]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', __('تم تحديث كلمة المرور بنجاح'));
    }
}
