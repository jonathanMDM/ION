Route::get('/debug-user', function() {
    if (!Auth::check()) {
        return response()->json([
            'authenticated' => false,
            'message' => 'No hay usuario autenticado'
        ]);
    }
    
    $user = Auth::user();
    return response()->json([
        'authenticated' => true,
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'is_superadmin' => $user->is_superadmin,
        'isSuperAdmin_method' => $user->isSuperAdmin(),
        'isAdmin_method' => $user->isAdmin(),
    ]);
})->middleware('auth')->name('debug.user');
