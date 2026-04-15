<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->withCount('businesses');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $owners = $query->latest()->paginate(20);
        return view('manager.owners.index', compact('owners'));
    }

    public function show(User $user)
    {
        // Ensure user is an owner
        if (!$user->hasRole('owner')) {
            abort(404);
        }

        $user->load(['businesses' => function($q) {
            $q->with(['category', 'district', 'subArea'])->latest();
        }]);

        return view('manager.owners.show', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        // Ensure user is an owner
        if (!$user->hasRole('owner')) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', __('Password updated successfully.'));
    }

    public function destroy(User $user)
    {
        // Ensure user is an owner
        if (!$user->hasRole('owner')) {
            abort(404);
        }

        // Get business count for message
        $businessCount = $user->businesses()->count();

        // Delete all owner's businesses first (cascade)
        $user->businesses()->delete();

        // Remove owner role before deleting user
        $user->removeRole('owner');

        // Delete the user
        $user->delete();

        $message = $businessCount > 0
            ? __('Owner and :count businesses deleted successfully.', ['count' => $businessCount])
            : __('Owner deleted successfully.');

        return redirect()->route('manager.owners.index')->with('success', $message);
    }
}
