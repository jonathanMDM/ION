@extends('layouts.app')

@section('page-title', 'Panel')

@section('content')
<!-- Subscription Warning -->
@if(isset($subscriptionWarning))
<div class="mb-6">
    @if(isset($subscriptionWarning['is_expired']) && $subscriptionWarning['is_expired'])
    <!-- Expired Subscription -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-4xl"></i>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-xl font-bold mb-2">⚠️ Suscripción Expirada</h3>
                <p class="text-lg mb-3">Su suscripción expiró el {{ $subscriptionWarning['expires_at'] }}</p>
                <p class="text-sm opacity-90">Por favor, contacte al administrador para renovar su suscripción y continuar usando el sistema.</p>
            </div>
        </div>
    </div>
    @elseif(isset($subscriptionWarning['is_critical']) && $subscriptionWarning['is_critical'])
    <!-- Critical Warning (7 days or less) -->
    <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-lg shadow-lg p-6 text-white animate-pulse">
        <div>
            <h3 class="text-xl font-bold mb-2">🚨 ¡Atención Urgente!</h3>
            <p class="text-lg mb-3">Su suscripción expira en <strong class="text-2xl">{{ $subscriptionWarning['days_left'] }}</strong> {{ $subscriptionWarning['days_left'] == 1 ? 'día' : 'días' }}</p>
            <p class="text-sm opacity-90">Fecha de expiración: {{ $subscriptionWarning['expires_at'] }}</p>
            <p class="text-sm mt-2 font-semibold">Por favor, renueve su suscripción lo antes posible para evitar interrupciones en el servicio.</p>
        </div>
    </div>
    @else
    <!-- Standard Warning (8-15 days) -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-400 rounded-lg shadow-lg p-6 text-gray-900">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-bell text-4xl"></i>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-xl font-bold mb-2">📅 Recordatorio de Suscripción</h3>
                <p class="text-lg mb-3">Su suscripción expira en <strong class="text-xl">{{ $subscriptionWarning['days_left'] }}</strong> días</p>
                <p class="text-sm opacity-90">Fecha de expiración: {{ $subscriptionWarning['expires_at'] }}</p>
                <p class="text-sm mt-2 font-semibold">Le recomendamos renovar su suscripción pronto para asegurar la continuidad del servicio.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

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

<!-- Low Stock Alerts Widget -->
@if($lowStockAssets->count() > 0)
<div class="mb-6">
    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-red-100 rounded-full p-3 mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">⚠️ Alertas de Stock Bajo</h3>
                    <p class="text-sm text-gray-600">{{ $lowStockAssets->count() }} activo(s) requieren atención</p>
                </div>
            </div>
            <a href="{{ route('assets.index') }}" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Activo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ubicación</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Stock Actual</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Mínimo</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($lowStockAssets as $asset)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <i class="fas fa-box text-red-500 mr-2"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $asset->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $asset->subcategory->category->name }} / {{ $asset->subcategory->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                            {{ $asset->location->name }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $asset->quantity == 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $asset->quantity }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">
                            {{ $asset->minimum_quantity }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($asset->quantity == 0)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>Agotado
                                </span>
                            @else
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Crítico
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('assets.edit', $asset->id) }}" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Actualizar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

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
