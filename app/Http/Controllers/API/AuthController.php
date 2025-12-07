<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $this->getRedirectUrl($user)
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Register Error: ' . $e->getMessage());
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

            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $this->getRedirectUrl($user)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Google Login - FIXED REDIRECT
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

            Log::info('Google Login Attempt - Token received');

            // Decode JWT Token
            $parts = explode('.', $idToken);

            if (count($parts) !== 3) {
                throw new \Exception('Invalid token format');
            }

            // Decode payload dengan padding yang benar
            $payload = base64_decode(strtr($parts[1], '-_', '+/'));
            $payloadData = json_decode($payload, true);

            if (!$payloadData) {
                throw new \Exception('Invalid token payload');
            }

            Log::info('Google Token Payload:', $payloadData);

            // Verify issuer
            if (!isset($payloadData['iss']) || !in_array($payloadData['iss'], ['https://accounts.google.com', 'accounts.google.com'])) {
                throw new \Exception('Invalid token issuer');
            }

            // Verify audience
            $clientId = config('services.google.client_id');
            if (!isset($payloadData['aud']) || $payloadData['aud'] !== $clientId) {
                Log::error('Token audience mismatch', [
                    'expected' => $clientId,
                    'received' => $payloadData['aud'] ?? 'not set'
                ]);
                throw new \Exception('Invalid token audience');
            }

            // Verify expiration
            if (!isset($payloadData['exp']) || $payloadData['exp'] < time()) {
                throw new \Exception('Token has expired');
            }

            // Verify email is verified
            if (!isset($payloadData['email_verified']) || $payloadData['email_verified'] !== true) {
                throw new \Exception('Email not verified by Google');
            }

            // Extract user data
            $googleUser = (object) [
                'id' => $payloadData['sub'],
                'name' => $payloadData['name'] ?? 'User',
                'email' => $payloadData['email'],
                'avatar' => $payloadData['picture'] ?? null,
            ];

            Log::info('Google User Data:', (array) $googleUser);

            // Find or create user
            $user = User::findOrCreateFromGoogle($googleUser);

            if (!$user) {
                throw new \Exception('Failed to create or find user');
            }

            Log::info('User found/created:', ['id' => $user->id, 'email' => $user->email]);

            // Create token
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Google login successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $this->getRedirectUrl($user)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Google login failed: ' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Facebook Login - FIXED REDIRECT
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

            Log::info('Facebook Login Attempt');

            // Get user data from Facebook
            $url = 'https://graph.facebook.com/me?fields=id,name,email,picture.type(large)&access_token=' . urlencode($accessToken);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new \Exception('cURL error: ' . $curlError);
            }

            if ($httpCode !== 200 || !$response) {
                throw new \Exception('Failed to get Facebook user data. HTTP Code: ' . $httpCode);
            }

            $facebookData = json_decode($response, true);

            Log::info('Facebook User Data:', $facebookData);

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

            Log::info('User found/created:', ['id' => $user->id, 'email' => $user->email]);

            // Create token
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Facebook login successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'redirect_url' => $this->getRedirectUrl($user)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Facebook Login Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

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
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
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
            Log::error('Me Error: ' . $e->getMessage());
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
            $token = $user->createToken('app2_token')->accessToken;
            $redirectUrl = config('app.app2_url', env('APP2_URL')) . '/auth/sso/callback?token=' . $token . '&user_id=' . $user->id;

            return response()->json([
                'success' => true,
                'message' => 'SSO token generated',
                'data' => [
                    'redirect_url' => $redirectUrl,
                    'token' => $token,
                    'user' => $user->load('roles')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('SSO Login Error: ' . $e->getMessage());
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

            $newToken = $user->createToken('app2_session')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'SSO callback successful',
                'data' => [
                    'user' => $user->load('roles'),
                    'access_token' => $newToken,
                    'token_type' => 'Bearer',
                    'redirect_url' => $this->getRedirectUrl($user)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('SSO Callback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SSO callback failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Get redirect URL based on user role
     */
    private function getRedirectUrl($user)
    {
        // Admin redirect ke admin panel
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return url('/admin');
        }

        return url('/');
    }
}
