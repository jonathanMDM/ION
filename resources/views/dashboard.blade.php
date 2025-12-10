@extends('layouts.app')

@section('page-title', 'Panel')

@section('content')
<!-- Announcements -->
@if(isset($announcements) && $announcements->count() > 0)
<div class="mb-6 space-y-4">
    @foreach($announcements as $announcement)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg shadow-md p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-bullhorn text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-bold text-gray-900">{{ $announcement->title }}</h3>
                <p class="mt-2 text-gray-700">{{ $announcement->message }}</p>
                @if($announcement->start_date || $announcement->end_date)
                <div class="mt-2 text-xs text-gray-500">
                    @if($announcement->start_date)
                        Desde: {{ \Carbon\Carbon::parse($announcement->start_date)->format('d/m/Y') }}
                    @endif
                    @if($announcement->end_date)
                        - Hasta: {{ \Carbon\Carbon::parse($announcement->end_date)->format('d/m/Y') }}
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Assets -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total de Activos</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_assets'] }}</p>
            </div>
            <div class="bg-gray-200 rounded-full p-3">
                <i class="fas fa-box text-gray-800 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Active Assets -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Activos</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['active_assets'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Maintenance -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">En Mantenimiento</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['maintenance_assets'] }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-wrench text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Decommissioned -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Dados de Baja</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['decommissioned_assets'] }}</p>
            </div>
            <div class="bg-red-100 rounded-full p-3">
                <i class="fas fa-times-circle text-red-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
        <div class="space-y-3">
            @if(Auth::user()->hasPermission('create_assets'))
            <a href="{{ route('assets.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-gray-200 rounded-lg transition">
                <i class="fas fa-plus-circle text-gray-800 mr-3"></i>
                <span class="text-gray-700">Agregar Nuevo Activo</span>
            </a>
            @endif
            
            @if(Auth::user()->hasPermission('import_assets'))
            <a href="{{ route('imports.create') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                <i class="fas fa-file-excel text-green-600 mr-3"></i>
                <span class="text-gray-700">Importar desde Excel</span>
            </a>
            @endif
            
            @if(Auth::user()->hasPermission('create_maintenance'))
            <a href="{{ route('maintenances.create') }}" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                <i class="fas fa-tools text-yellow-600 mr-3"></i>
                <span class="text-gray-700">Agregar Mantenimiento</span>
            </a>
            @endif
            
            @if(Auth::user()->hasPermission('manage_locations'))
            <a href="{{ route('locations.create') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                <i class="fas fa-map-marker-alt text-purple-600 mr-3"></i>
                <span class="text-gray-700">Agregar Ubicación</span>
            </a>
            @endif
            
            @if(!Auth::user()->hasPermission('create_assets') && 
                !Auth::user()->hasPermission('import_assets') && 
                !Auth::user()->hasPermission('create_maintenance') && 
                !Auth::user()->hasPermission('manage_locations'))
            <div class="text-center py-4">
                <i class="fas fa-lock text-gray-400 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">No tienes permisos para acciones rápidas</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Assets -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Activos Recientes</h3>
        @if($recent_assets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 text-sm font-semibold text-gray-600">Nombre</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-600">Ubicación</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-600">Categoría</th>
                            <th class="text-center py-2 text-sm font-semibold text-gray-600">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_assets as $asset)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3">
                                <a href="{{ route('assets.show', $asset->id) }}" class="text-gray-800 hover:text-gray-900 font-medium">
                                    {{ $asset->name }}
                                </a>
                            </td>
                            <td class="py-3 text-sm text-gray-600">{{ $asset->location->name }}</td>
                            <td class="py-3 text-sm text-gray-600">{{ $asset->subcategory->category->name }}</td>
                            <td class="py-3 text-center">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $asset->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $asset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $asset->status == 'decommissioned' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($asset->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">
                No se encontraron activos.
                @if(Auth::user()->hasPermission('create_assets'))
                    <a href="{{ route('assets.create') }}" class="text-gray-800 hover:text-gray-900">Agrega tu primer activo</a>
                @endif
            </p>
        @endif
    </div>
</div>

<!-- Additional Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Ubicaciones</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_locations'] }}</p>
            </div>
            <i class="fas fa-map-marker-alt text-gray-400 text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Categorías</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_categories'] }}</p>
            </div>
            <i class="fas fa-folder text-gray-400 text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Registros de Mantenimiento</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_maintenances'] }}</p>
            </div>
            <i class="fas fa-wrench text-gray-400 text-xl"></i>
        </div>
    </div>
</div>
@endsection
