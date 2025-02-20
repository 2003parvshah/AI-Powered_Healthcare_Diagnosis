<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsPatient
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'patient') {
            return $next($request);
        }
        elseif (Auth::check() && Auth::user()->role === 'patient') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized, User only'], 403);
    }
}
