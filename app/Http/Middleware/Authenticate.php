<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\User;
use Closure;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $user = User::where('bearerToken', $token)->first();

        if(!$user || !$token)
        {
            return response()->json([
               "message" => "Authentication failed"
            ], 401);
        }

        return $next($request);
    }
}
