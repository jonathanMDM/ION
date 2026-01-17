<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show the force password change form.
     */
    public function show()
    {
        return view('auth.force-password-change');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => [
                'required', 
                'min:8', 
                'confirmed', 
                'regex:/[a-z]/', 
                'regex:/[A-Z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*?&#.]/'
            ],
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas ingresadas no coinciden.',
            'password.regex' => 'La contraseña no cumple con los requisitos de seguridad.',
        ]);

        // Specific custom validation checks for better user feedback
        $password = $request->password;
        $errors = [];
        
        if (!preg_match('/[a-z]/', $password)) $errors[] = 'Debe contener al menos una letra minúscula.';
        if (!preg_match('/[A-Z]/', $password)) $errors[] = 'Debe contener al menos una letra mayúscula.';
        if (!preg_match('/[0-9]/', $password)) $errors[] = 'Debe contener al menos un número.';
        if (!preg_match('/[@$!%*?&#.]/', $password)) $errors[] = 'Debe contener al menos un carácter especial (@$!%*?&#.).';

        if (!empty($errors)) {
            return back()->withErrors(['password' => $errors])->withInput();
        }

        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Contraseña actualizada exitosamente. ¡Bienvenido a ION Inventory!');
    }
}
