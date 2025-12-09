<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiTokenController extends Controller
{
    /**
     * Show the API token management page
     */
    public function index()
    {
        $user = auth()->user();
        
        $hasToken = !is_null($user->api_token);
        $isExpired = false;
        $expiresAt = $user->api_token_expires_at;
        
        if ($hasToken && $expiresAt) {
            $isExpired = $expiresAt->isPast();
        }
        
        return view('api.token', compact('hasToken', 'isExpired', 'expiresAt'));
    }

    /**
     * Generate a new API token
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'token_name' => 'required|string|max:255',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);
        
        $user = auth()->user();
        
        // Generate a unique token
        $token = Str::random(80);
        
        // Set expiration date
        $expiresAt = null;
        if (isset($validated['expires_in_days'])) {
            $expiresAt = now()->addDays((int) $validated['expires_in_days']);
        }
        
        // Save token to user
        $user->api_token = Hash::make($token);
        $user->api_token_expires_at = $expiresAt;
        $user->save();
        
        return redirect()->route('superadmin.api.token.index')
            ->with('success', 'API token generated successfully!')
            ->with('token', $token);
    }

    /**
     * Revoke the current API token
     */
    public function revoke()
    {
        $user = auth()->user();
        
        $user->api_token = null;
        $user->api_token_expires_at = null;
        $user->save();
        
        return redirect()->route('superadmin.api.token.index')
            ->with('success', 'API token revoked successfully.');
    }
}
