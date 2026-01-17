@extends('layouts.superadmin')

@section('page-title', 'Editar Empresa')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Editar: {{ $company->name }}</h3>
            
            @if($company->users->where('role', 'admin')->first())
            <form action="{{ route('superadmin.users.impersonate', $company->users->where('role', 'admin')->first()) }}" method="POST">
                @csrf
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2 px-4 rounded shadow flex items-center">
                    <i class="fas fa-user-secret mr-2"></i> Iniciar Sesión como Admin
                </button>
            </form>
            @endif
        </div>
        
        <form action="{{ route('superadmin.companies.update', $company) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Company Details -->
                <div class="col-span-2">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">Datos de la Empresa</h4>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nit" class="block text-sm font-medium text-gray-700 mb-1">NIT / RUC / ID Fiscal</label>
                    <input type="text" name="nit" id="nit" value="{{ old('nit', $company->nit) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('nit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $company->address) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="user_limit" class="block text-sm font-medium text-gray-700 mb-1">Límite de Usuarios *</label>
                    <input type="number" name="user_limit" id="user_limit" value="{{ old('user_limit', $company->user_limit) }}" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('user_limit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Usuarios actuales: {{ $company->users()->count() }} / {{ $company->user_limit }}</p>
                </div>

                <div>
                    <label for="subscription_expires_at" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Expiración</label>
                    <input type="date" name="subscription_expires_at" id="subscription_expires_at" value="{{ old('subscription_expires_at', $company->subscription_expires_at?->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('subscription_expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @if($company->subscription_expires_at)
                        <p class="text-xs {{ $company->isExpired() ? 'text-red-600 font-bold' : 'text-gray-500' }} mt-1">
                            {{ $company->isExpired() ? '⚠️ Expirado' : 'Expira en ' . $company->subscription_expires_at->diffForHumans() }}
                        </p>
                    @endif
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                        <option value="active" {{ $company->status === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ $company->status === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <!-- Enabled Modules Section -->
            <div class="mb-6 border-t pt-6">
                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">
                    <i class="fas fa-puzzle-piece mr-2"></i>Módulos Habilitados
                </h4>
                <p class="text-sm text-gray-600 mb-4">Selecciona los módulos que estarán disponibles para esta empresa</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $moduleNames = \App\Models\Company::getModuleNames();
                        $currentModules = old('enabled_modules', $company->enabled_modules ?? \App\Models\Company::getDefaultModules());
                    @endphp

                    @foreach($moduleNames as $key => $name)
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                   name="enabled_modules[{{ $key }}]" 
                                   id="module_{{ $key }}"
                                   value="1"
                                   {{ ($currentModules[$key] ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="module_{{ $key }}" class="font-medium text-gray-700 cursor-pointer">
                                {{ $name }}
                            </label>
                            <p class="text-xs text-gray-500">
                                @switch($key)
                                    @case('financial_control')
                                        Control completo de información financiera de activos
                                        @break
                                    @case('depreciation')
                                        Cálculo automático de depreciación con múltiples métodos
                                        @break
                                    @case('cost_centers')
                                        Organización de activos por centros de costo con presupuesto
                                        @break
                                    @case('asset_costs')
                                        Registro de costos asociados a cada activo
                                        @break
                                    @case('transfers')
                                        Transferencias de activos entre ubicaciones
                                        @break
                                    @case('loans')
                                        Gestión de préstamos temporales de activos
                                        @break
                                    @case('disposals')
                                        Proceso formal de baja de activos
                                        @break
                                    @case('advanced_audit')
                                        Auditoría detallada de todas las acciones
                                        @break
                                    @case('compliance')
                                        Cumplimiento de normas contables (NIIF/IFRS)
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Nota sobre módulos:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Los módulos deshabilitados no serán visibles para los usuarios de la empresa</li>
                                <li>Los datos existentes se conservan aunque el módulo esté deshabilitado</li>
                                <li>Algunos módulos dependen de otros (ej: Centros de Costo requiere Control Financiero)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('superadmin.companies.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow">
                    Cancelar
                </a>
                <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow">
                    Actualizar Empresa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
