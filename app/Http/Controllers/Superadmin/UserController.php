<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function impersonate(\App\Models\User $user) 
    {
        // Store the original superadmin ID in session
        session(['impersonator_id' => \Auth::id()]);
        
        // Login as the target user
        \Auth::login($user);
        
        \Log::info('Impersonation started', [
            'impersonator_id' => session('impersonator_id'),
            'target_user_id' => $user->id,
            'target_user_name' => $user->name
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Ahora estás viendo la aplicación como ' . $user->name);
    }
    
    public function stopImpersonating()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('dashboard')->with('error', 'No estás suplantando a ningún usuario');
        }
        
        $impersonatorId = session('impersonator_id');
        
        // Login back as the superadmin
        $superadmin = \App\Models\User::find($impersonatorId);
        
        if (!$superadmin) {
            session()->forget('impersonator_id');
            \Log::error('Superadmin not found during stop impersonation', ['impersonator_id' => $impersonatorId]);
            \Auth::logout();
            return redirect()->route('login')->with('error', 'Error al volver a la cuenta de Superadmin. Por favor, inicia sesión nuevamente.');
        }
        
        session()->forget('impersonator_id');
        \Auth::login($superadmin);
        
        \Log::info('Impersonation stopped', ['superadmin_id' => $impersonatorId]);
        
        return redirect()->route('superadmin.index')->with('success', 'Has vuelto a tu cuenta de Superadmin');
    }
}
