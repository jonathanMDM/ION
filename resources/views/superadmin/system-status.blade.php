@extends('layouts.superadmin')

@section('page-title', 'Estado del Sistema')

@section('content')
<div class="mb-6">
    <p class="text-gray-600">Monitoreo en tiempo real del estado de las empresas y recursos del sistema.</p>
</div>

<!-- System Health Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i class="fas fa-server text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Estado General</p>
                <p class="text-2xl font-bold text-gray-800">Operativo</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-200 text-gray-600 mr-4">
                <i class="fas fa-database text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Base de Datos</p>
                <p class="text-2xl font-bold text-gray-800">Conectado</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                <i class="fas fa-memory text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Uso de Memoria</p>
                <p class="text-2xl font-bold text-gray-800">{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</p>
            </div>
        </div>
    </div>
</div>

<!-- Companies Status Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Estado de Empresas</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuarios</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Actividad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($companies as $company)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $company->name }}</div>
                                <div class="text-sm text-gray-500">{{ $company->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $company->users_count }} / {{ $company->user_limit }}
                        @if($company->hasReachedUserLimit())
                            <span class="ml-2 text-red-500 text-xs" title="Límite alcanzado"><i class="fas fa-exclamation-circle"></i></span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $company->assets_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $company->last_active ? $company->last_active->diffForHumans() : 'Nunca' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($company->isExpired())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Expirado
                            </span>
                        @elseif($company->status === 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Activo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Inactivo
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay empresas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
