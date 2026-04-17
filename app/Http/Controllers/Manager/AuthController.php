<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('manager')->check()) {
            return redirect()->route('manager.dashboard');
        }
        return view('manager.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password_1' => 'required|string',
            'password_2' => 'required|string',
        ]);

        // Sanitize username to prevent XSS
        $username = strip_tags($request->username);
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        $manager = Manager::where('username', $username)
                         ->where('is_active', true)
                         ->first();

        if (!$manager) {
            throw ValidationException::withMessages([
                'username' => __('Invalid credentials or account inactive.'),
            ]);
        }

        // Verify both passwords
        if (!Hash::check($request->password_1, $manager->password_1) ||
            !Hash::check($request->password_2, $manager->password_2)) {
            
            // Log failed attempt for security
            \Log::warning('Manager login failed', [
                'username' => $request->username,
                'ip' => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'username' => __('Invalid credentials.'),
            ]);
        }

        // Update last login
        $manager->update(['last_login_at' => now()]);

        Auth::guard('manager')->login($manager, $request->boolean('remember'));

        // Regenerate session for security
        $request->session()->regenerate();

        return redirect()->intended(route('manager.dashboard'));
    }

    public function showSetupForm()
    {
        // Only allow setup if no managers exist
        if (Manager::count() > 0) {
            return redirect()->route('manager.login');
        }
        return view('manager.auth.setup');
    }

    public function setup(Request $request)
    {
        // Only allow setup if no managers exist
        if (Manager::count() > 0) {
            return redirect()->route('manager.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:managers',
            'password_1' => 'required|string|min:8|confirmed',
            'password_2' => 'required|string|min:8|confirmed',
        ]);

        // Sanitize inputs to prevent XSS
        $name = strip_tags($request->name);
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $username = strip_tags($request->username);
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        // Ensure passwords are different
        if ($request->password_1 === $request->password_2) {
            return back()->withErrors(['password_2' => __('The two passwords must be different for security.')])->withInput();
        }

        $manager = Manager::create([
            'name' => $name,
            'username' => $username,
            'password_1' => Hash::make($request->password_1),
            'password_2' => Hash::make($request->password_2),
            'is_active' => true,
        ]);

        Auth::guard('manager')->login($manager);

        return redirect()->route('manager.dashboard')->with('success', __('Manager account created successfully!'));
    }

    public function logout(Request $request)
    {
        Auth::guard('manager')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('manager.login');
    }
}
