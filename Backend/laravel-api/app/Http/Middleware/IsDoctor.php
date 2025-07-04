<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsDoctor
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'doctor') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized, Doctor only'], 403);
    }
}
