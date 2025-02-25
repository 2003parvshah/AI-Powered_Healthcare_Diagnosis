<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class IsPatient
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'patient') {
            return $next($request);
        } elseif (FacadesAuth::check() && FacadesAuth::user()->role === 'patient') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized, User only'], 403);
    }
}
