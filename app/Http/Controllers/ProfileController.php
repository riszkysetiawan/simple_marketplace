<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function index()
    {
        $user = auth()->user()->load('roles');
        $stats = [
            'total_orders' => $user->transactions()->count(),
            'pending_orders' => $user->transactions()->where('status', 'pending')->count(),
            'completed_orders' => $user->transactions()->where('status', 'completed')->count(),
            'total_spent' => $user->transactions()
                ->whereIn('status', ['completed', 'shipped'])
                ->sum('total_amount'),
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        $user->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Update avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $user = auth()->user();
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully',
            'avatar_url' => Storage::url($path)
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);
        $user = auth()->user();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 422);
        }
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }
        auth()->logout();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
            'redirect' => route('home')
        ]);
    }
}
