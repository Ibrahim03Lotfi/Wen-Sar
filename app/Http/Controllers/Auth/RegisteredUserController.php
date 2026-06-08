<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Store intended role in session if provided
        if ($request->has('role')) {
            $request->session()->put('intended_role', $request->role);
        }
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // Use transaction so partial failures (e.g. missing role) don't leave a user record behind
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Determine requested role (if any) or fall back to 'user'
            $requestedRole = $request->session()->has('intended_role') ? $request->session()->get('intended_role') : 'user';

            // If requested role doesn't exist, fallback to 'user' and create 'user' role if missing
            if (!Role::where('name', $requestedRole)->exists()) {
                Log::warning("Requested role [{$requestedRole}] does not exist. Falling back to 'user'.");
                $requestedRole = 'user';
            }

            if (!Role::where('name', 'user')->exists()) {
                Role::create(['name' => 'user']);
            }

            $user->assignRole($requestedRole);
            // consume intended role if it was set
            if ($request->session()->has('intended_role')) {
                $request->session()->forget('intended_role');
            }

            DB::commit();

            // Fire registered event and login after successful commit
            event(new Registered($user));
            Auth::login($user);

            return redirect('/');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage(), ['exception' => $e]);

            // Ensure no partially created user remains
            if (isset($user) && $user instanceof User) {
                try {
                    $user->delete();
                } catch (\Exception $ex) {
                    Log::error('Failed to delete partially created user: ' . $ex->getMessage());
                }
            }

            return redirect()->back()->withInput()->withErrors(['register' => 'حدث خطأ أثناء إنشاء الحساب، حاول مرة أخرى.']);
        }
    }
}
