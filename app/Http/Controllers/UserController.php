<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Check if company has reached user limit
        $company = Auth::user()->company;
        if ($company && $company->hasReachedUserLimit()) {
            return back()->withErrors([
                'email' => 'Has alcanzado el límite de usuarios permitidos para tu empresa. Contacta al soporte para aumentar tu cuota.'
            ])->withInput();
        }

        $validPermissions = array_keys(\App\Config\PermissionConfig::getAllPermissions());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'role' => 'required|in:admin,editor,viewer,custom',
            'permissions' => 'array',
            'permissions.*' => 'in:' . implode(',', $validPermissions),
        ]);

        // Only save permissions if role is custom
        $permissions = $request->role === 'custom' ? $request->permissions : null;

        // Verify that the current user has all the permissions they're trying to assign
        if ($permissions && !Auth::user()->isAdmin()) {
            foreach ($permissions as $permission) {
                if (!Auth::user()->hasPermission($permission)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'No puedes asignar el permiso "' . $permission . '" porque tú no lo tienes.');
                }
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'permissions' => $permissions,
            'is_active' => true,
            'company_id' => Auth::user()->company_id, // Explicitly assign company
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validPermissions = array_keys(\App\Config\PermissionConfig::getAllPermissions());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,editor,viewer,custom',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'in:' . implode(',', $validPermissions),
        ]);

        // Only save permissions if role is custom
        $permissions = $request->role === 'custom' ? $request->permissions : null;

        // Verify that the current user has all the permissions they're trying to assign
        if ($permissions && !Auth::user()->isAdmin()) {
            foreach ($permissions as $permission) {
                if (!Auth::user()->hasPermission($permission)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'No puedes asignar el permiso "' . $permission . '" porque tú no lo tienes.');
                }
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'permissions' => $permissions,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Protect superadmin from deletion
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.index')->with('error', 'No se puede eliminar la cuenta de Superadministrador.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('users.change-password');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada exitosamente.');
    }
}
