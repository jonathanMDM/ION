<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Display the settings page (including 2FA).
     */
    public function settings()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        // Ensure secret exists for generating QR
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

        return view('profile.settings', compact('user', 'secret', 'qrCode'));
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'items_per_page' => 'required|in:10,25,50,100',
            'notifications.low_stock' => 'nullable|boolean',
            'notifications.maintenance' => 'nullable|boolean',
            'notifications.weekly_digest' => 'nullable|boolean',
        ]);

        $preferences = $user->preferences ?? [];
        
        // Update values
        $preferences['pagination']['items_per_page'] = (int) $validated['items_per_page'];
        
        // Handle checkboxes (if not present, they are false)
        $preferences['notifications'] = [
            'low_stock' => $request->has('notifications.low_stock'),
            'maintenance' => $request->has('notifications.maintenance'),
            'weekly_digest' => $request->has('notifications.weekly_digest'),
        ];

        $user->preferences = $preferences;
        $user->save();

        return redirect()->back()->with('success', 'Preferencias actualizadas correctamente.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
}
