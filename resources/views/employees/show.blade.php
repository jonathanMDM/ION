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

        <!-- Activos Asignados -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Activos Asignados Actualmente</h3>
                
                @if($activeAssignments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Asignación</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devolución Esperada</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($activeAssignments as $assignment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-box text-indigo-600 mr-2"></i>
                                            <a href="{{ route('assets.show', $assignment->asset->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $assignment->asset->name }}
                                            </a>
                                        </div>
                                        @if($assignment->asset->custom_id)
                                            <span class="text-xs text-gray-500">ID: {{ $assignment->asset->custom_id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $assignment->asset->subcategory->category->name }} / {{ $assignment->asset->subcategory->name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $assignment->asset->location->name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $assignment->assigned_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($assignment->expected_return_date)
                                            <span class="px-2 py-1 rounded text-xs {{ $assignment->expected_return_date->isPast() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $assignment->expected_return_date->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">No definida</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-50 p-8 text-center rounded border border-dashed border-gray-300">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">No tiene activos asignados actualmente.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Asignaciones</h3>
                
                @if($assignmentHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignmentHistory as $assignment)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-box text-gray-400 mr-2"></i>
                                        <a href="{{ route('assets.show', $assignment->asset->id) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $assignment->asset->name }}
                                        </a>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ $assignment->asset->subcategory->category->name }} / {{ $assignment->asset->subcategory->name }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                    Devuelto
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-3 pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Asignado</p>
                                    <p class="text-sm text-gray-900">{{ $assignment->assigned_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Devuelto</p>
                                    <p class="text-sm text-gray-900">{{ $assignment->return_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            @if($assignment->notes)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Notas</p>
                                <p class="text-sm text-gray-700">{{ $assignment->notes }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500 text-sm">No hay registros históricos.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
