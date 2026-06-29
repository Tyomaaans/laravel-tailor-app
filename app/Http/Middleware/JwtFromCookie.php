<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        // Jika belum ada Authorization header, ambil dari cookie
        if (! $request->bearerToken() && $token = $request->cookie('access_token')) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}