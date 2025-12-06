<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Autentikasi pengguna
        $request->authenticate();

        // Regenerasi session
        $request->session()->regenerate();

        // Ambil pengguna yang terautentikasi
        $user = auth()->user();

        // Pengalihan berdasarkan role
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return redirect('/admin')
                ->with('success', 'Welcome back, Admin ' . $user->name . '!');
        }

        // Jika role adalah 'customer', arahkan ke home
        if ($user->hasRole('customer')) {
            return redirect()->route('home')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Pengalihan default jika tidak ada role yang sesuai
        return redirect()->route('home')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userName = auth()->user()->name;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Goodbye, ' . $userName . '! See you soon.');
    }
}
