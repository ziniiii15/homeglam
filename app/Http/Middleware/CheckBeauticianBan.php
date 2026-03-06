<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBeauticianBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('beautician')->check() && 
            auth()->guard('beautician')->user()->banned_until && 
            auth()->guard('beautician')->user()->banned_until->isFuture()) {
            
            return redirect()->route('beautician.banned');
        }

        return $next($request);
    }
}
