<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Rate Limiting
        $key = 'login|' . $request->ip() . '|' . $request->email;
        
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            
            // Log blocked attempt
            \App\Models\LoginAttempt::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'success' => false,
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors([
                'email' => 'Demasiados intentos fallidos. Por favor intente de nuevo en ' . $seconds . ' segundos.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Clear rate limiter
            \Illuminate\Support\Facades\RateLimiter::clear($key);

            // Log successful attempt
            \App\Models\LoginAttempt::create([
                'email' => $request->email,
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'success' => true,
                'user_agent' => $request->userAgent(),
            ]);

            // Check if user is active
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ]);
            }

            // Check if company is active (for non-superadmins)
            if (!Auth::user()->isSuperAdmin() && Auth::user()->company && Auth::user()->company->status === 'inactive') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'El acceso ha sido restringido. Por favor contacte al área de soporte para regularizar su estado de pago.',
                ]);
            }

            // Check if subscription is expired (for non-superadmins)
            if (!Auth::user()->isSuperAdmin() && Auth::user()->company && Auth::user()->company->isExpired()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu membresía ha expirado. Por favor contacta al área de soporte para renovar tu suscripción.',
                ]);
            }

            // ========================================
            // CHECK 2FA - If enabled, require verification
            // ========================================
            if (Auth::user()->two_factor_enabled) {
                // Store user ID in session for 2FA verification
                $request->session()->put('2fa:user:id', Auth::id());
                
                // Logout temporarily (will re-login after 2FA verification)
                Auth::logout();
                
                // Redirect to 2FA verification page
                return redirect()->route('2fa.verify');
            }

            // Redirect based on role
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->intended(route('superadmin.index'));
            }

            return redirect()->intended(route('dashboard'));
        }

        // Increment rate limiter
        \Illuminate\Support\Facades\RateLimiter::hit($key, 60); // 1 minute lockout after 5 attempts

        // Log failed attempt
        \App\Models\LoginAttempt::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'success' => false,
            'user_agent' => $request->userAgent(),
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        return redirect()->route('login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
