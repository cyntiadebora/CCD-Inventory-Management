<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfFirstLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->first_login) {
            // Kalau user sudah login dan ini login pertama, arahkan ke form reset
            return redirect()->route('password.reset.form');
        }

        return $next($request);
    }
}
