<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ CLEAR CACHE
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ DETAILED LOGGING
        Log::info('==========================================');
        Log::info('LOGIN ATTEMPT');
        Log::info('==========================================');
        Log::info('User ID: ' . $user->id);
        Log::info('Email: ' . $user->email);
        Log::info('Roles (getRoleNames): ' . $user->getRoleNames()->implode(', '));
        Log::info('Has super_admin: ' . ($user->hasRole('super_admin') ? 'YES' : 'NO'));
        Log::info('Has customer: ' . ($user->hasRole('customer') ? 'YES' : 'NO'));
        Log::info('Is super admin: ' . ($user->isSuperAdmin() ? 'YES' : 'NO'));
        Log::info('Is customer: ' . ($user->isCustomer() ?  'YES' : 'NO'));

        // ✅ CHECK 1: Super Admin (FIRST PRIORITY)
        if ($user->hasRole('super_admin')) {
            Log::info('✅ DECISION: Redirect to /admin (super_admin role detected)');
            Log::info('==========================================');
            return redirect('/admin');
        }

        // ✅ CHECK 2: Customer (SECOND PRIORITY)
        if ($user->hasRole('customer')) {
            Log::info('✅ DECISION: Redirect to /customer (customer role detected)');
            Log::info('==========================================');
            return redirect('/customer');
        }

        // ✅ NO VALID ROLE
        Log::warning('❌ DECISION: No valid role - logout user');
        Log::info('==========================================');

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors(['email' => 'No valid role assigned to your account.Please contact support.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
