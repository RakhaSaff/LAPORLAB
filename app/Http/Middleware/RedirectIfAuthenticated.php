<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($guard === 'admin' && Auth::guard('admin')->check()) {
                return redirect('/admin/dashboard');
            }

            if ($guard === null && Auth::guard('web')->check()) {
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}