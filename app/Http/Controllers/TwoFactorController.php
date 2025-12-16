<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        if (!$user->two_factor_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->two_factor_secret = Crypt::encryptString($secret);
            $user->save();
        } else {
            $secret = Crypt::decryptString($user->two_factor_secret);
        }
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
        
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);
        
        return view('two-factor.show', compact('secret', 'qrCode', 'user'));
    }
    
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric'
        ]);
        
        $user = Auth::user();
        $google2fa = new Google2FA();
        $secret = Crypt::decryptString($user->two_factor_secret);
        
        $valid = $google2fa->verifyKey($secret, $request->code);
        
        if ($valid) {
            $user->two_factor_enabled = true;
            
            // Generate recovery codes
            $recoveryCodes = [];
            for ($i = 0; $i < 8; $i++) {
                $recoveryCodes[] = strtoupper(substr(md5(random_bytes(10)), 0, 10));
            }
            $user->two_factor_recovery_codes = Crypt::encryptString(json_encode($recoveryCodes));
            $user->save();
            
            return redirect()->route('profile.settings')
                ->with('success', '2FA activado exitosamente.')
                ->with('recovery_codes', $recoveryCodes);
        }
        
        return back()->with('error', 'Código inválido. Intenta nuevamente.');
    }
    
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);
        
        $user = Auth::user();
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->save();
        
        return redirect()->route('profile.settings')
            ->with('success', '2FA desactivado exitosamente.');
    }
    
    public function showVerifyForm(Request $request)
    {
        // Check if user ID is in session (from login)
        if (!$request->session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }
        
        return view('two-factor.verify');
    }
    
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);
        
        // Get user ID from session (set during login)
        $userId = $request->session()->get('2fa:user:id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors([
                'code' => 'Sesión expirada. Por favor inicia sesión nuevamente.'
            ]);
        }
        
        $user = \App\Models\User::find($userId);
        
        if (!$user || !$user->two_factor_enabled) {
            $request->session()->forget('2fa:user:id');
            return redirect()->route('login');
        }
        
        $google2fa = new Google2FA();
        $secret = Crypt::decryptString($user->two_factor_secret);
        
        $valid = $google2fa->verifyKey($secret, $request->code);
        
        if ($valid) {
            // Remove 2FA session data
            $request->session()->forget('2fa:user:id');
            
            // Login the user
            Auth::login($user, true);
            $request->session()->regenerate();
            
            // Redirect based on role
            if ($user->isSuperAdmin()) {
                return redirect()->intended(route('superadmin.index'));
            }
            
            return redirect()->intended(route('dashboard'));
        }
        
        // Check recovery codes
        if ($user->two_factor_recovery_codes) {
            $recoveryCodes = json_decode(Crypt::decryptString($user->two_factor_recovery_codes), true);
            if (in_array(strtoupper($request->code), $recoveryCodes)) {
                // Remove used recovery code
                $recoveryCodes = array_diff($recoveryCodes, [strtoupper($request->code)]);
                $user->two_factor_recovery_codes = Crypt::encryptString(json_encode(array_values($recoveryCodes)));
                $user->save();
                
                // Remove 2FA session data
                $request->session()->forget('2fa:user:id');
                
                // Login the user
                Auth::login($user, true);
                $request->session()->regenerate();
                
                // Redirect based on role
                if ($user->isSuperAdmin()) {
                    return redirect()->intended(route('superadmin.index'));
                }
                
                return redirect()->intended(route('dashboard'));
            }
        }
        
        return back()->withErrors([
            'code' => 'Código inválido. Por favor intenta de nuevo.'
        ]);
    }
}
