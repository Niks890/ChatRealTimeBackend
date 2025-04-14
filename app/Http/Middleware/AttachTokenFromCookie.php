<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttachTokenFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken() && $request->hasCookie('access_token')) {
            $token = $request->cookie('access_token');
            // Log::info('AttachTokenFromCookie middleware activated', ['token' => $token]);
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        return $next($request);
    }
}
