<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthenticated. API token required.'
            ], 401);
        }
        
        // Find user by token
        $user = \App\Models\User::whereNotNull('api_token')
            ->where('is_active', true)
            ->get()
            ->first(function ($user) use ($token) {
                return Hash::check($token, $user->api_token);
            });
        
        if (!$user) {
            return response()->json([
                'message' => 'Invalid API token'
            ], 401);
        }
        
        // Check if token is expired
        if ($user->api_token_expires_at && $user->api_token_expires_at->isPast()) {
            return response()->json([
                'message' => 'API token has expired'
            ], 401);
        }
        
        // Set authenticated user
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        return $next($request);
    }
}
