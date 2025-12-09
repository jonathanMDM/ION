<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function impersonate(User $user)
    {
        // Guard against impersonating yourself or another superadmin
        if ($user->id === Auth::id() || $user->isSuperAdmin()) {
            return back()->with('error', 'No puedes suplantar a este usuario.');
        }

        // Store original user ID in session
        session()->put('impersonator_id', Auth::id());

        // Login as the target user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Has iniciado sesión como {$user->name}");
    }

    public function stopImpersonating()
    {
        if (session()->has('impersonator_id')) {
            $impersonatorId = session()->pull('impersonator_id');
            
            // Find superadmin without company scope
            $impersonator = User::withoutGlobalScope(\App\Scopes\CompanyScope::class)
                ->find($impersonatorId);

            if ($impersonator) {
                Auth::login($impersonator);
                return redirect()->route('superadmin.dashboard')->with('success', 'Has vuelto a tu cuenta de Superadministrador.');
            }
        }

        return redirect()->route('login');
    }
}
