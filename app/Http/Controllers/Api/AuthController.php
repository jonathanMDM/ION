<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Generate a new API token for the authenticated user
     */
    public function generateToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token_name' => 'required|string|max:255',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);
        
        $user = $request->user();
        
        // Generate a unique token
        $token = Str::random(80);
        
        // Set expiration date
        $expiresAt = null;
        if (isset($validated['expires_in_days'])) {
            $expiresAt = now()->addDays($validated['expires_in_days']);
        }
        
        // Save token to user
        $user->api_token = Hash::make($token);
        $user->api_token_expires_at = $expiresAt;
        $user->save();
        
        return response()->json([
            'message' => 'API token generated successfully',
            'token' => $token,
            'token_name' => $validated['token_name'],
            'expires_at' => $expiresAt?->toIso8601String(),
            'warning' => 'Please save this token securely. You will not be able to see it again.',
        ], 201);
    }

    /**
     * Revoke the current API token
     */
    public function revokeToken(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $user->api_token = null;
        $user->api_token_expires_at = null;
        $user->save();
        
        return response()->json([
            'message' => 'API token revoked successfully'
        ]);
    }

    /**
     * Check if user has an active API token
     */
    public function tokenStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $hasToken = !is_null($user->api_token);
        $isExpired = false;
        
        if ($hasToken && $user->api_token_expires_at) {
            $isExpired = $user->api_token_expires_at->isPast();
        }
        
        return response()->json([
            'has_token' => $hasToken,
            'is_expired' => $isExpired,
            'expires_at' => $user->api_token_expires_at?->toIso8601String(),
            'status' => $hasToken && !$isExpired ? 'active' : 'inactive',
        ]);
    }
}
