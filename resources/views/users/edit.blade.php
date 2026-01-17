@extends('layouts.app')

@section('page-title', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Editar Acceso de Usuario</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Modificando perfil de seguridad para <strong>{{ $user->name }}</strong>.</p>
        </div>
        <a href="{{ route('users.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 dark:text-red-400 font-bold">Por favor corrija los siguientes errores:</p>
                    <ul class="mt-1 text-xs text-red-600 dark:text-red-400 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Side: Basic Info -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-user-edit text-indigo-500 mr-3"></i>
                            <h3 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-widest">Información de Perfil</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                            <span class="ml-3 text-[10px] font-black uppercase text-gray-500 tracking-wider">Estado Activo</span>
                        </label>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="name">Nombre Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="email">Correo Electrónico</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 pl-11 pr-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" required>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                            <p class="text-[10px] text-amber-700 dark:text-amber-400 font-medium leading-relaxed flex items-start gap-3">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                Para cambiar la contraseña de este usuario, él mismo debe realizarlo desde su configuración de cuenta personal.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Custom Permissions Section -->
                @php
                    $userPermissions = old('permissions', $user->permissions ?? []);
                @endphp
                <div id="permissions-section" class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden {{ old('role', $user->role) == 'custom' ? '' : 'hidden' }} transform transition-all duration-500">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-widest">Ajustes de Permisos Personalizados</h3>
                    </div>
                    <div class="p-8 space-y-8">
                        @foreach(\App\Config\PermissionConfig::getPermissionsByCategory() as $category => $permissions)
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest flex items-center">
                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span> {{ $category }}
                                </h4>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="selectAll('{{ Str::slug($category) }}')" class="text-[9px] font-black text-gray-400 hover:text-indigo-600 transition-colors uppercase tracking-widest bg-gray-100 dark:bg-gray-900/40 px-2 py-1 rounded-lg">Todas</button>
                                    <button type="button" onclick="deselectAll('{{ Str::slug($category) }}')" class="text-[9px] font-black text-gray-400 hover:text-red-500 transition-colors uppercase tracking-widest bg-gray-100 dark:bg-gray-900/40 px-2 py-1 rounded-lg">Ninguna</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 {{ Str::slug($category) }}-group">
                                @foreach($permissions as $key => $label)
                                <label class="group flex items-center justify-between p-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-transparent hover:border-indigo-500/30 transition-all cursor-pointer">
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 transition-colors">{{ $label }}</span>
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $userPermissions) ? 'checked' : '' }} class="w-5 h-5 rounded-lg border-gray-300 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Security Role -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-shield-alt text-emerald-500 mr-2"></i>
                        <h3 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Nivel de Seguridad</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3" for="role">Rol de Acceso</label>
                        <select name="role" id="role" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-xs font-bold focus:ring-2 focus:ring-emerald-500 transition-all" 
                            required onchange="togglePermissions()">
                            <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>👁️ Visor (Solo lectura)</option>
                            <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>✍️ Editor (Crear y editar)</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>⚡ Administrador (Total)</option>
                            <option value="custom" {{ old('role', $user->role) == 'custom' ? 'selected' : '' }}>🛠️ Personalizado (Elegir)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
            <a href="{{ route('users.index') }}" 
                class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all active:scale-95">
                Cancelar
            </a>
            <button type="submit" 
                class="w-full md:w-auto px-16 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-indigo-500/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm">
                <i class="fas fa-sync-alt mr-2 text-lg"></i> Actualizar Usuario
            </button>
        </div>
    </form>
</div>

<script>
function togglePermissions() {
    const role = document.getElementById('role').value;
    const permissionsSection = document.getElementById('permissions-section');
    
    if (role === 'custom') {
        permissionsSection.classList.remove('hidden');
        setTimeout(() => {
            permissionsSection.style.opacity = '1';
            permissionsSection.style.transform = 'scale(1)';
        }, 10);
    } else {
        permissionsSection.style.opacity = '0';
        permissionsSection.style.transform = 'scale(0.95)';
        setTimeout(() => {
            permissionsSection.classList.add('hidden');
        }, 500);
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

document.addEventListener('DOMContentLoaded', togglePermissions);
</script>
@endsection
