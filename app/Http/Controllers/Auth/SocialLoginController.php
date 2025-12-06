<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google Callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            \Log::info('Google User:', (array) $googleUser);

            // ✅ Find or create user
            $user = User::findOrCreateFromGoogle($googleUser);

            // ✅ Assign role jika belum ada
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }

            // ✅ Login user
            Auth::login($user, true);

            // ✅ Redirect berdasarkan role
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return redirect()->intended('/admin')
                    ->with('success', 'Welcome back, Admin ' . $user->name . '!');
            }

            return redirect()->intended('/home')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            \Log::error('Stack: ' . $e->getTraceAsString());
            return redirect('/login')->with('error', 'Failed to login with Google: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to Facebook
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook Callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            \Log::info('Facebook User:', (array) $facebookUser);

            // ✅ Find or create user
            $user = User::findOrCreateFromFacebook($facebookUser);

            // ✅ Assign role jika belum ada
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }

            // ✅ Login user
            Auth::login($user, true);

            // ✅ Redirect berdasarkan role
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return redirect()->intended('/admin')
                    ->with('success', 'Welcome back, Admin ' . $user->name . '!');
            }

            return redirect()->intended('/home')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        } catch (Exception $e) {
            \Log::error('Facebook OAuth Error: ' . $e->getMessage());
            \Log::error('Stack: ' . $e->getTraceAsString());
            return redirect('/login')->with('error', 'Failed to login with Facebook: ' . $e->getMessage());
        }
    }
}
