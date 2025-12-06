<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if (! $user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }

                if (! $user->hasAnyRole(['super_admin', 'customer'])) {
                    $user->assignRole('customer');
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => null,
                ]);

                $user->assignRole('customer');
            }

            Auth::login($user, true);

            if ($user->hasRole('super_admin')) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/customer');
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());

            return redirect('/login')->with('error', 'Failed to login with Google.');
        }
    }

    public function redirectToFacebook()
    {
        // ✅ FIX: Remove ->scopes(['email']) or use valid scopes
        return Socialite::driver('facebook')
            ->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // ✅ Handle case where Facebook doesn't provide email
            $email = $facebookUser->getEmail();

            if (! $email) {
                // Generate fallback email from Facebook ID
                $email = $facebookUser->getId() . '@facebook.com';

                \Log::warning('Facebook user has no email', [
                    'facebook_id' => $facebookUser->getId(),
                    'name' => $facebookUser->getName(),
                ]);
            }

            $user = User::where('facebook_id', $facebookUser->getId())->first();

            if (! $user) {
                $user = User::where('email', $email)->first();
            }

            if ($user) {
                // Update Facebook ID if not set
                if (! $user->facebook_id) {
                    $user->update([
                        'facebook_id' => $facebookUser->getId(),
                        'avatar' => $facebookUser->getAvatar(),
                    ]);
                }

                if (! $user->hasAnyRole(['super_admin', 'customer'])) {
                    $user->assignRole('customer');
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $email,
                    'facebook_id' => $facebookUser->getId(),
                    'avatar' => $facebookUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => null,
                ]);

                $user->assignRole('customer');
            }

            Auth::login($user, true);

            if ($user->hasRole('super_admin')) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/customer');
        } catch (Exception $e) {
            \Log::error('Facebook OAuth Error: ' . $e->getMessage());

            return redirect('/login')->with('error', 'Failed to login with Facebook. Please try another method.');
        }
    }
}
