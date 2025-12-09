@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-4 md:p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Editar Usuario</h2>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre Completo</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">Tipo de Rol</label>
            <select name="role" id="role" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required onchange="togglePermissions()">
                <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>Visor (Solo lectura)</option>
                <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor (Crear y editar)</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador (Acceso completo)</option>
                <option value="custom" {{ old('role', $user->role) == 'custom' ? 'selected' : '' }}>Personalizado (Seleccionar permisos)</option>
            </select>
        </div>

        <div id="permissions-section" class="mb-6 {{ old('role', $user->role) == 'custom' ? '' : 'hidden' }}">
            <label class="block text-gray-700 text-sm font-bold mb-3">Permisos Personalizados</label>
            
            @php
                $userPermissions = old('permissions', $user->permissions ?? []);
            @endphp

            @foreach(\App\Config\PermissionConfig::getPermissionsByCategory() as $category => $permissions)
            <div class="mb-4 bg-gray-50 p-4 rounded border border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-bold text-gray-700">{{ $category }}</h4>
                    <div class="text-xs">
                        <button type="button" onclick="selectAll('{{ Str::slug($category) }}')" class="text-gray-800 hover:text-gray-900 mr-2">Todas</button>
                        <button type="button" onclick="deselectAll('{{ Str::slug($category) }}')" class="text-gray-500 hover:text-gray-700">Ninguna</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 {{ Str::slug($category) }}-group">
                    @foreach($permissions as $key => $label)
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $userPermissions) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-gray-800">
                        <span class="ml-2 text-sm">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-gray-800">
                <span class="ml-2 text-gray-700">Usuario Activo</span>
            </label>
            <p class="text-gray-600 text-xs italic mt-1">Los usuarios inactivos no pueden iniciar sesión</p>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <p class="text-sm text-yellow-700">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Nota:</strong> Para cambiar la contraseña del usuario, el usuario debe usar la opción "Cambiar Contraseña" desde su cuenta.
            </p>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <i class="fas fa-save mr-2"></i>Actualizar Usuario
            </button>
            <a href="{{ route('users.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function togglePermissions() {
    const role = document.getElementById('role').value;
    const permissionsSection = document.getElementById('permissions-section');
    
    if (role === 'custom') {
        permissionsSection.classList.remove('hidden');
    } else {
        permissionsSection.classList.add('hidden');
    }
}

function selectAll(category) {
    const container = document.querySelector('.' + category + '-group');
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = true);
}

function deselectAll(category) {
    const container = document.querySelector('.' + category + '-group');
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', togglePermissions);
</script>
@endsection
