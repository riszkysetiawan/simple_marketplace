<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('customer');

            // ✅ Gunakan Passport - accessToken
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $user->hasRole('super_admin') ? '/admin' : '/home'
                ]
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Register Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // ✅ Gunakan Passport - accessToken
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $user->hasRole('super_admin') ? '/admin' : '/home'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Google Login
     */
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $idToken = $request->input('id_token');

            // Decode JWT Token
            $parts = explode('.', $idToken);

            if (count($parts) !== 3) {
                throw new \Exception('Invalid token format');
            }

            // Decode payload
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

            if (!$payload) {
                throw new \Exception('Invalid token payload');
            }

            // Verify issuer
            if ($payload['iss'] !== 'https://accounts.google.com') {
                throw new \Exception('Invalid token issuer');
            }

            // Verify audience
            if ($payload['aud'] !== env('GOOGLE_CLIENT_ID')) {
                throw new \Exception('Invalid token audience: ' . $payload['aud']);
            }

            // Verify expiration
            if ($payload['exp'] < time()) {
                throw new \Exception('Token has expired');
            }

            // Extract user data
            $googleUser = (object) [
                'id' => $payload['sub'],
                'name' => $payload['name'] ?? 'User',
                'email' => $payload['email'],
                'avatar' => $payload['picture'] ?? null,
            ];

            // Find or create user
            $user = User::findOrCreateFromGoogle($googleUser);

            if (!$user) {
                throw new \Exception('Failed to create or find user');
            }

            // ✅ Gunakan Passport - accessToken
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Google login successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $user->hasRole('super_admin') ? '/admin' : '/home'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Google login failed: ' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Facebook Login
     */
    public function facebookLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $accessToken = $request->input('access_token');

            // Get user data from Facebook
            $url = 'https://graph.facebook.com/me?fields=id,name,email,picture.type(large)&access_token=' . urlencode($accessToken);

            $response = @file_get_contents($url);

            if ($response === false) {
                throw new \Exception('Failed to get Facebook user data');
            }

            $facebookData = json_decode($response, true);

            if (isset($facebookData['error'])) {
                throw new \Exception('Facebook error: ' . $facebookData['error']['message']);
            }

            if (!isset($facebookData['id'])) {
                throw new \Exception('Invalid Facebook token');
            }

            $facebookUser = (object) [
                'id' => $facebookData['id'],
                'name' => $facebookData['name'] ?? 'User',
                'email' => $facebookData['email'] ?? $facebookData['id'] . '@facebook.com',
                'avatar' => $facebookData['picture']['data']['url'] ?? null,
            ];

            // Find or create user
            $user = User::findOrCreateFromFacebook($facebookUser);

            if (!$user) {
                throw new \Exception('Failed to create or find user');
            }

            // ✅ Gunakan Passport - accessToken
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Facebook login successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $user->hasRole('super_admin') ? '/admin' : '/home'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Facebook Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Facebook login failed: ' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            // ✅ Gunakan Passport - revoke
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);
        } catch (\Exception $e) {
            \Log::error('Logout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Logout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $request->user()->load('roles')
            ]);
        } catch (\Exception $e) {
            \Log::error('Me Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SSO Login
     */
    public function ssoLogin(Request $request)
    {
        try {
            $user = $request->user();
            // ✅ Gunakan Passport - accessToken
            $token = $user->createToken('app2_token')->accessToken;
            $redirectUrl = env('APP2_URL') . '/auth/sso/callback?token=' . $token . '&user_id=' . $user->id;

            return response()->json([
                'success' => true,
                'message' => 'SSO token generated',
                'data' => [
                    'redirect_url' => $redirectUrl,
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('SSO Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SSO login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SSO Callback
     */
    public function ssoCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = $request->input('user_id');
            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // ✅ Gunakan Passport - accessToken
            $newToken = $user->createToken('app2_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'SSO callback successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $newToken,
                    'token_type' => 'Bearer',
                    'redirect_url' => $user->hasRole('super_admin') ? '/admin' : '/home'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('SSO Callback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SSO callback failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
