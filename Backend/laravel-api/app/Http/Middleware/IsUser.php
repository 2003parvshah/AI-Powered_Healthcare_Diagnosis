<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'user') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized, User only'], 403);
    }
}
