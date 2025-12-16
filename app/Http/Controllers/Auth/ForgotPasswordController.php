<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No existe una cuenta con este correo electrónico.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generar contraseña temporal
        $temporaryPassword = Str::random(10);
        
        // Actualizar usuario con contraseña temporal y marcar para cambio obligatorio
        $user->password = Hash::make($temporaryPassword);
        $user->must_change_password = true;
        $user->save();

        // Enviar email con contraseña temporal
        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'temporaryPassword' => $temporaryPassword
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Recuperación de Contraseña - ION Inventory');
            });

            return back()->with('success', 'Se ha enviado una contraseña temporal a tu correo electrónico.');
        } catch (\Exception $e) {
            // Si falla el envío de email, revertir cambios
            $user->must_change_password = false;
            $user->save();
            
            return back()->with('error', 'Error al enviar el correo. Por favor, contacta al administrador.');
        }
    }
}
