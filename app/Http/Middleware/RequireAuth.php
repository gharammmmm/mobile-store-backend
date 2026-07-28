<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('user_id')) {
            return response()->json([
                'error' => 'Unauthorized. Please login first.'
            ], 401);
        }

        return $next($request);
    }
}
