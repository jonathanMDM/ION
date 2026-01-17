@extends('layouts.app')

@section('page-title', 'Perfil de Empleado')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 text-gray-400 hover:text-indigo-600 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Perfil del Colaborador</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gestión de activos y vinculación organizacional.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('employees.edit', $employee->id) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/20 flex items-center">
                <i class="fas fa-user-edit mr-2"></i> Editar Perfil
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Personal Stats & Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="h-24 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                <div class="px-6 pb-6 pt-0 -mt-12 text-center">
                    <div class="relative inline-block">
                        <div class="w-24 h-24 bg-white dark:bg-gray-900 rounded-2xl shadow-xl flex items-center justify-center text-3xl font-black text-indigo-600 border-4 border-white dark:border-gray-800 mx-auto">
                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 rounded-full border-2 border-white dark:border-gray-800 {{ $employee->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                    </div>
                    
                    <h2 class="mt-4 text-xl font-black text-gray-900 dark:text-white">{{ $employee->full_name }}</h2>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $employee->position ?? 'Sin cargo' }}</p>
                    
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black uppercase tracking-wider">
                            {{ $employee->department ?? 'General' }}
                        </span>
                        <span class="px-3 py-1 {{ $employee->status == 'active' ? 'bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400' }} rounded-full text-[10px] font-black uppercase tracking-wider">
                            {{ $employee->status == 'active' ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <div class="mt-8 space-y-4 text-left border-t border-gray-50 dark:border-gray-700 pt-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-indigo-500">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Correo</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate">{{ $employee->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-teal-500">
                                <i class="fas fa-id-card text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">ID Empleado</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 font-medium">#{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-indigo-600 rounded-2xl p-4 text-white shadow-lg shadow-indigo-500/20">
                    <p class="text-[10px] font-bold uppercase opacity-80">Activos</p>
                    <p class="text-2xl font-black">{{ $activeAssignments->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Histórico</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $assignmentHistory->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content: Assignments & Timeline -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Active Assets Section -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center uppercase tracking-wider">
                        <i class="fas fa-boxes text-indigo-500 mr-3"></i> Activos Asignados
                    </h3>
                </div>
                
                @if($activeAssignments->count() > 0)
                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">
                                    <th class="pb-2 px-4">Información del Activo</th>
                                    <th class="pb-2 px-4">Categoría</th>
                                    <th class="pb-2 px-4">Fecha Asig.</th>
                                    <th class="pb-2 px-4">Estado</th>
                                    <th class="pb-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeAssignments as $assignment)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="bg-gray-50/50 dark:bg-gray-900/40 rounded-l-2xl py-4 px-4 border-l border-t border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-indigo-500 shadow-sm">
                                                <i class="fas fa-laptop-code"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $assignment->asset->name }}</p>
                                                <p class="text-[10px] font-mono text-gray-500">{{ $assignment->asset->custom_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="bg-gray-50/50 dark:bg-gray-900/40 py-4 px-4 border-t border-b border-gray-100 dark:border-gray-700">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $assignment->asset->subcategory->category->name }}</span>
                                    </td>
                                    <td class="bg-gray-50/50 dark:bg-gray-900/40 py-4 px-4 border-t border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ $assignment->assigned_date->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="bg-gray-50/50 dark:bg-gray-900/40 py-4 px-4 border-t border-b border-gray-100 dark:border-gray-700">
                                        @if($assignment->expected_return_date)
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $assignment->expected_return_date->isPast() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                Exp: {{ $assignment->expected_return_date->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] font-black uppercase">Permanente</span>
                                        @endif
                                    </td>
                                    <td class="bg-gray-50/50 dark:bg-gray-900/40 rounded-r-2xl py-4 px-4 border-r border-t border-b border-gray-100 dark:border-gray-700 text-right">
                                        <a href="{{ route('assets.show', $assignment->asset->id) }}" class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm text-gray-400 hover:text-indigo-600 transition-colors">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-8 py-12 text-center">
                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700/50 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-200 dark:border-gray-600">
                            <i class="fas fa-ghost text-3xl text-gray-300"></i>
                        </div>
                        <h4 class="text-gray-800 dark:text-white font-bold">Sin asignaciones activas</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Este colaborador no tiene recursos vinculados actualmente.</p>
                    </div>
                @endif
            </div>

            <!-- Timeline / Audit Section -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center uppercase tracking-wider">
                        <i class="fas fa-history text-emerald-500 mr-3"></i> Trazabilidad Histórica
                    </h3>
                </div>
                <div class="p-8">
                    @if($assignmentHistory->count() > 0)
                        <div class="relative pl-8 space-y-8 before:absolute before:inset-y-0 before:left-0 before:w-0.5 before:bg-gray-100 dark:before:bg-gray-700 flex flex-col">
                            @foreach($assignmentHistory as $assignment)
                            <div class="relative">
                                <div class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-white dark:bg-gray-800 border-4 border-emerald-500 shadow-sm z-10 transition-transform hover:scale-125"></div>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 bg-gray-50/50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 group hover:border-emerald-500/30 transition-all">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-black text-emerald-600 uppercase tracking-widest">ASIGNACIÓN FINALIZADA</span>
                                            <span class="text-gray-300">•</span>
                                            <span class="text-[10px] font-bold text-gray-400">#{{ $assignment->id }}</span>
                                        </div>
                                        <h4 class="text-sm font-black text-gray-900 dark:text-white">Devolución de: {{ $assignment->asset->name }}</h4>
                                        <p class="text-[10px] text-gray-500 mt-1">Periodo: {{ $assignment->assigned_date->format('d/m/Y') }} — {{ $assignment->return_date->format('d/m/Y') }}</p>
                                        @if($assignment->notes)
                                            <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded-xl text-[11px] text-gray-600 dark:text-gray-400 italic border-l-2 border-emerald-500">
                                                "{{ $assignment->notes }}"
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('assets.show', $assignment->asset->id) }}" class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline">Ver Activo</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-gray-400 text-sm font-medium italic italic">No hay registros previos en el historial de este colaborador.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
