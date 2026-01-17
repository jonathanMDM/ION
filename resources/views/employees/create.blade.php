@extends('layouts.app')

@section('page-title', 'Registrar Empleado')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Registrar Empleado</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Añada un nuevo colaborador para asignarle activos y recursos.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl">
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

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left: Personal Info (2 columns) -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Perfil del Colaborador</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="first_name">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-medium" 
                                    placeholder="Ej: Juan Camilo" required>
                                @error('first_name') <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="last_name">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-medium" 
                                    placeholder="Ej: Pérez García" required>
                                @error('last_name') <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="email">
                                    Correo Electrónico Corporativo <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                        class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 pl-11 pr-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" 
                                        placeholder="juan.perez@empresa.com" required>
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Organizational Info -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-sitemap text-emerald-500 mr-2"></i>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Cargo / Estado</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="department">Departamento</label>
                            <input type="text" name="department" value="{{ old('department') }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all" 
                                placeholder="Ej: Operaciones">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="position">Cargo</label>
                            <input type="text" name="position" value="{{ old('position') }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all" 
                                placeholder="Ej: Analista Senior">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="status">Estado Lab.</label>
                            <select name="status" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Activo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
            <a href="{{ route('employees.index') }}" 
                class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all active:scale-95">
                Cancelar
            </a>
            <button type="submit" 
                class="w-full md:w-auto px-16 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-indigo-500/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm">
                <i class="fas fa-save mr-2 text-lg"></i> Registrar Empleado
            </button>
        </div>
    </form>
</div>
@endsection
