@extends('layouts.app')

@section('page-title', 'Perfil de Empleado')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('employees.index') }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Empleados
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Perfil -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="w-24 h-24 bg-gray-200 text-gray-800 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $employee->full_name }}</h2>
                <p class="text-gray-500">{{ $employee->position ?? 'Sin cargo definido' }}</p>
                
                <div class="mt-4 pt-4 border-t border-gray-100 text-left">
                    <div class="mb-3">
                        <label class="text-xs text-gray-500 uppercase font-bold">Email</label>
                        <p class="text-sm text-gray-800">{{ $employee->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs text-gray-500 uppercase font-bold">Departamento</label>
                        <p class="text-sm text-gray-800">{{ $employee->department ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs text-gray-500 uppercase font-bold">Estado</label>
                        <span class="px-2 py-1 rounded text-xs {{ $employee->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->status == 'active' ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="block w-full bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Activos Asignados (Placeholder para Fase 2 Parte 2) -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Activos Asignados Actualmente</h3>
                
                <!-- Aquí irán los activos asignados -->
                <div class="bg-gray-50 p-8 text-center rounded border border-dashed border-gray-300">
                    <p class="text-gray-500">El historial de activos estará disponible próximamente.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Asignaciones</h3>
                
                <!-- Aquí irá el historial -->
                <div class="text-center py-4">
                    <p class="text-gray-500 text-sm">No hay registros históricos.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
