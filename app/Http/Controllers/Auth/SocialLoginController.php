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
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::findOrCreateFromGoogle($googleUser);

            Auth::login($user, true);

            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return redirect()->intended('/admin');
            }

            return redirect('/')->with('success', 'Successfully logged in with Google!');
        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
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
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::findOrCreateFromFacebook($facebookUser);

            Auth::login($user, true);

            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return redirect()->intended('/admin');
            }

            return redirect('/')->with('success', 'Successfully logged in with Facebook!');
        } catch (Exception $e) {
            \Log::error('Facebook Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Facebook login failed. Please try again.');
        }
    }
}
