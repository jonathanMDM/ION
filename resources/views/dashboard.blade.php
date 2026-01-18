@extends('layouts.app')

@section('page-title', 'Panel de Control')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Panel Principal</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Gestión integral de activos y métricas operativas.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                <i class="far fa-calendar-alt mr-2.5 text-indigo-500"></i>
                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                    {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM, YYYY') }}
                </span>
            </div>
            <button onclick="window.location.reload()" class="p-2.5 bg-white dark:bg-gray-800 text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-all hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Alerts Section (Conditional) -->
    @if(!auth()->user()->company_id || isset($subscriptionWarning))
    <div class="grid grid-cols-1 gap-6">
        @if(!auth()->user()->company_id)
        <div class="rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #152326, var(--color-black-pearl)); border: 1px solid var(--color-blue-lagoon);">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 font-medium">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-lg" style="background: rgba(14, 104, 115, 0.3); border: 1px solid var(--color-blue-lagoon); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);">
                        <i class="fas fa-building" style="color: var(--color-burnt-orange);"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color: #FFFFFF;">Cuenta sin Organización</h2>
                        <p style="color: #B0C4C9;">Vincule su cuenta a una empresa para empezar a gestionar inventarios.</p>
                    </div>
                </div>
                <a href="https://wa.me/573145781261" target="_blank" class="px-6 py-3 rounded-xl font-black shadow-lg transition-all hover:opacity-90 hover:scale-105" style="background: var(--color-burnt-orange); color: #FFFFFF; box-shadow: 0 4px 20px rgba(254, 126, 60, 0.4);">
                    Soporte Técnico
                </a>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full blur-3xl opacity-20" style="background: var(--color-burnt-orange);"></div>
        </div>
        @endif
        
        @if(isset($subscriptionWarning))
            <!-- Simplified Subscription Warning -->
            <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 bg-amber-500 text-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-400">
                        @if($subscriptionWarning['is_expired'])
                            Suscripción Expirada. Acceso restringido.
                        @else
                            La suscripción expira en {{ $subscriptionWarning['days_left'] }} días ({{ $subscriptionWarning['expires_at'] }}).
                        @endif
                    </p>
                </div>
                <button class="text-xs font-black uppercase tracking-widest text-amber-700 dark:text-amber-500 hover:underline">Renovar</button>
            </div>
        @endif
    </div>
    @endif

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Assets -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: #B0C4C9;">Activos Totales</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: #FFFFFF;">
                        {{ $stats['total_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: var(--color-blue-lagoon);">Unidades en sistema</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg" style="background: rgba(14, 104, 115, 0.2); border: 1px solid var(--color-blue-lagoon); color: var(--color-burnt-orange);">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        <!-- Operative -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: #B0C4C9;">Operativos</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: var(--color-burnt-orange);">
                        {{ $stats['active_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: var(--color-blue-lagoon);">Estado: Activo</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg" style="background: rgba(254, 126, 60, 0.1); border: 1px solid var(--color-burnt-orange); color: var(--color-burnt-orange);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: #B0C4C9;">En Taller</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: #F59E0B;">
                        {{ $stats['maintenance_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: #B0C4C9; opacity: 0.7;">Requiere atención</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #F59E0B;">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>

        <!-- Decommissioned -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: #B0C4C9;">Bajas</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: var(--color-lust);">
                        {{ $stats['decommissioned_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: #B0C4C9; opacity: 0.7;">Fuera de servicio</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg" style="background: rgba(228, 32, 27, 0.1); border: 1px solid var(--color-lust); color: var(--color-lust);">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Balanced Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Recent Activity and Alerts (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Assets per Location -->
            <div class="rounded-3xl shadow-sm p-6" style="background: var(--bg-dark-green); border: 1px solid var(--green-bangladesh);">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold" style="color: var(--text-white);">Activos por Sede</h4>
                    <span class="text-xs px-2 py-1 rounded bg-opacity-20 flex items-center gap-1" style="background: rgba(3, 98, 76, 0.3); color: var(--green-mountain);">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="font-bold">{{ $stats['locations_count'] ?? 0 }}</span>
                        Sedes
                    </span>
                </div>
                <div class="space-y-4">
                    @forelse($assets_per_location as $location)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $location->name }}</p>
                                <p class="text-[10px] text-gray-500 font-medium">{{ $location->address }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-green-600 dark:text-green-400">{{ $location->assets_count }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Activos</p>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-400">
                        <p class="text-sm font-medium">No hay sedes con activos registrados.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Stock Alerter (Conditional) -->
            @if($lowStockAssets->count() > 0)
            <div class="rounded-3xl shadow-sm p-6" style="background: var(--bg-card); border: 1px solid var(--color-lust);">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold" style="color: #FFFFFF;">Alertas de Stock</h4>
                    <span class="text-xs px-2 py-1 rounded flex items-center gap-1 font-bold" style="background: rgba(228, 32, 27, 0.1); color: var(--color-lust);">
                        <i class="fas fa-exclamation-triangle"></i>
                        Atención Requerida
                    </span>
                </div>
                <div class="space-y-3">
                    @foreach($lowStockAssets as $asset)
                    <div class="flex items-center justify-between p-3 rounded-xl border" style="background: rgba(228, 32, 27, 0.05); border-color: rgba(228, 32, 27, 0.2);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(228, 32, 27, 0.1); color: var(--color-lust);">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold" style="color: #FFFFFF;">{{ $asset->name }}</p>
                                <p class="text-[10px] font-medium" style="color: var(--color-lust);">Stock Bajo: {{ $asset->stock }} unds</p>
                            </div>
                        </div>
                        <a href="{{ route('assets.show', $asset->id) }}" class="text-xs font-bold hover:underline" style="color: var(--color-lust);">Ver</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Assets -->
            <div class="rounded-3xl shadow-sm p-6" style="background: var(--bg-dark-green); border: 1px solid var(--green-bangladesh);">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold" style="color: var(--text-white);">Activos Recientes</h4>
                    <a href="{{ route('assets.index') }}" class="text-xs font-bold hover:opacity-80 transition-opacity" style="color: var(--green-caribbean);">Ver Todo</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentAssets as $asset)
                    <div class="flex items-center justify-between group p-2 hover:bg-white/5 rounded-xl transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                @if($asset->image_path)
                                <img src="{{ asset('storage/' . $asset->image_path) }}" class="w-12 h-12 rounded-xl object-cover border shadow-sm" style="border-color: var(--green-bangladesh);" alt="{{ $asset->name }}">
                                @else
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm" style="background: rgba(3, 98, 76, 0.3); border-color: var(--green-bangladesh); color: var(--green-mountain);">
                                    <i class="fas fa-cube text-lg"></i>
                                </div>
                                @endif
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-400">
                        <i class="fas fa-folder-open text-3xl mb-3 block"></i>
                        <p class="text-sm font-medium">No se encontraron registros recientes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Column: Quick Actions and System Health (1/3 width) -->
        <div class="space-y-8">
            
            <!-- Quick Actions -->
            <div class="rounded-3xl shadow-sm p-6" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
                <h4 class="text-lg font-bold mb-6" style="color: #FFFFFF;">Acciones Rápidas</h4>
                <div class="grid grid-cols-1 gap-3">
                    @if(Auth::user()->hasPermission('create_assets'))
                    <a href="{{ route('assets.create') }}" class="group flex items-center p-4 rounded-2xl transition-all" style="background: rgba(254, 126, 60, 0.1); border: 1px solid var(--color-burnt-orange);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform" style="background: rgba(254, 126, 60, 0.2); color: var(--color-burnt-orange);">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-bold group-hover:text-white transition-colors" style="color: var(--color-burnt-orange);">Nuevo Activo</span>
                            <span class="block text-[10px] group-hover:text-white transition-colors uppercase tracking-widest font-black" style="color: #B0C4C9;">Registro Central</span>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('import_assets'))
                    <a href="{{ route('imports.create') }}" class="group flex items-center p-4 rounded-2xl transition-all" style="background: rgba(14, 104, 115, 0.1); border: 1px solid var(--color-blue-lagoon);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform" style="background: rgba(14, 104, 115, 0.2); color: var(--color-blue-lagoon);">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-bold group-hover:text-white transition-colors" style="color: var(--color-blue-lagoon);">Importar Excel</span>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="group flex items-center p-4 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-900 rounded-2xl transition-all border border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="ml-4 text-gray-900 dark:text-gray-100 group-hover:text-white transition-colors">
                            <span class="text-sm font-bold">Mis Reportes</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Assets per Location -->
            <div class="rounded-3xl shadow-sm p-6" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold" style="color: #FFFFFF;">Activos por Sede</h4>
                    <span class="text-xs px-2 py-1 rounded bg-opacity-20 flex items-center gap-1" style="background: rgba(14, 104, 115, 0.3); color: var(--color-blue-lagoon);">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="font-bold">{{ $stats['locations_count'] ?? 0 }}</span>
                        Sedes
                    </span>
                </div>
                <div class="space-y-4">
                    @forelse($assets_per_location as $location)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $location->name }}</p>
                                <p class="text-[10px] text-gray-500 font-medium">{{ $location->address }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-green-600 dark:text-green-400">{{ $location->assets_count }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Activos</p>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-400">
                        <p class="text-sm font-medium">No hay sedes con activos registrados.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Mini Pulse and Status -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-3xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-gray-400 tracking-[0.2em]">Ocupación Regional</span>
                        <span class="text-xs font-bold text-sky-600 dark:text-sky-400">{{ $stats['total_locations'] }} Sedes</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Categorías</span>
                            <span class="text-sm font-black text-gray-900 dark:text-white">{{ $stats['total_categories'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Mantenimientos</span>
                            <span class="text-sm font-black text-gray-900 dark:text-white">{{ $stats['total_maintenances'] }}</span>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
