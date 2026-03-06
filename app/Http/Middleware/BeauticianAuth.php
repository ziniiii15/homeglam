<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeauticianAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('beautician')->check()) {
            return redirect()->route('beautician.login');
        }

        return $next($request);
    }
}

