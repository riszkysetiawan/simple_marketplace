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

            // ✅ Redirect based on role
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
        return Socialite::driver('facebook')->scopes(['email'])->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            if (! $facebookUser->getEmail()) {
                return redirect('/login')->with('error', 'Facebook did not provide your email.');
            }

            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
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
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
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

            return redirect('/login')->with('error', 'Failed to login with Facebook.');
        }
    }
}
