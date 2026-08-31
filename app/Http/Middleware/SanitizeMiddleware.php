<?php

namespace App\Http\Middleware;

use Closure;

class SanitizeMiddleware
{
    public function handle($request, Closure $next)
    {
        $request->merge(array_map(function ($value) {
            if (is_string($value)) {
                $value = preg_replace('/\s+/', ' ', $value);
                return trim($value);
            }
            return $value;
        }, $request->all()));

        return $next($request);
    }
}
