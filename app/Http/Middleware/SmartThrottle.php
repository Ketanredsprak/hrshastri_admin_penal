<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;

class SmartThrottle
{
    public function handle($request, Closure $next)
    {
        // Create unique key per user or IP
        $key = $request->user()
            ? 'user_'.$request->user()->id
            : 'ip_'.$request->ip();

        $maxAttempts = 300;  // allow more
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'status' => false,
                'message' => 'Too many requests, slow down'
            ], 429);
        }

        // Record this hit
        RateLimiter::hit($key, $decaySeconds);

        return $next($request);
    }
}
