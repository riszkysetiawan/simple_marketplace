<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // ✅ Super Admin → /admin
                if ($user->hasRole('super_admin')) {
                    return redirect('/admin');
                }

                // ✅ Customer → /customer
                if ($user->hasRole('customer')) {
                    return redirect('/customer');
                }

                // Default
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
