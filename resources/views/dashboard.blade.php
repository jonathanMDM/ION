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
        <div class="bg-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 font-medium">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Cuenta sin Organización</h2>
                        <p class="text-indigo-100 opacity-90">Vincule su cuenta a una empresa para empezar a gestionar inventarios.</p>
                    </div>
                </div>
                <a href="https://wa.me/573145781261" target="_blank" class="px-6 py-3 bg-white text-indigo-600 rounded-xl font-bold shadow-lg hover:bg-gray-50 transition-all">
                    Soporte Técnico
                </a>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
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
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Activos Totales</p>
                    <h3 class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ $stats['total_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-indigo-500 mt-1">Unidades en sistema</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        <!-- Operative -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Operativos</p>
                    <h3 class="text-4xl font-extrabold text-green-600 leading-tight">
                        {{ $stats['active_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-gray-500 mt-1">Estado: Activo</p>
                </div>
                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">En Taller</p>
                    <h3 class="text-4xl font-extrabold text-amber-500 leading-tight">
                        {{ $stats['maintenance_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-gray-500 mt-1">Requiere atención</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>

        <!-- Decommissioned -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Bajas</p>
                    <h3 class="text-4xl font-extrabold text-rose-600 leading-tight">
                        {{ $stats['decommissioned_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-gray-500 mt-1">Fuera de servicio</p>
                </div>
                <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Balanced Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Recent Activity and Alerts (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Stock Alerter (Conditional) -->
            @if($lowStockAssets->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-red-100 dark:border-red-900/30 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        Alertas de Stock Bajo
                    </h4>
                    <span class="px-3 py-1 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-black rounded-lg uppercase tracking-wider">
                        Acción Requerida
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($lowStockAssets as $asset)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-microchip text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $asset->name }}</p>
                                <p class="text-[10px] text-gray-500 font-medium">{{ $asset->location->name }} • {{ $asset->subcategory->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-red-600">{{ $asset->quantity }} / {{ $asset->minimum_quantity }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Disponible / Mín.</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Assets Table -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Activos Recientes</h4>
                    <a href="{{ route('assets.index') }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Ver Todo</a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($recent_assets as $asset)
                    <div class="p-5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center border border-gray-100 dark:border-gray-600 overflow-hidden">
                                @if($asset->image)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($asset->image, 'http') ? $asset->image : asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-gray-300"></i>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('assets.show', $asset->id) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-indigo-600 transition-colors">{{ $asset->name }}</a>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $asset->subcategory->category->name }} • {{ $asset->location->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="hidden sm:block text-right text-xs">
                                <p class="text-gray-400 font-medium">Agregado</p>
                                <p class="font-bold text-gray-600 dark:text-gray-400">{{ $asset->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-lg border
                                {{ $asset->status == 'active' ? 'bg-green-50 text-green-600 border-green-100 dark:bg-green-900/20 dark:border-green-800/50' : '' }}
                                {{ $asset->status == 'maintenance' ? 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:border-amber-800/50' : '' }}
                                {{ $asset->status == 'decommissioned' ? 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:border-rose-800/50' : '' }}">
                                {{ ucfirst($asset->status) }}
                            </span>
                        </div>
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
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Acciones Rápidas</h4>
                <div class="grid grid-cols-1 gap-3">
                    @if(Auth::user()->hasPermission('create_assets'))
                    <a href="{{ route('assets.create') }}" class="group flex items-center p-4 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-600 rounded-2xl transition-all border border-indigo-100 dark:border-indigo-800/30">
                        <div class="w-10 h-10 bg-white dark:bg-gray-800 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-bold text-indigo-900 dark:text-indigo-100 group-hover:text-white transition-colors">Nuevo Activo</span>
                            <span class="block text-[10px] text-indigo-400 group-hover:text-indigo-100 transition-colors uppercase tracking-widest font-black">Registro Central</span>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('import_assets'))
                    <a href="{{ route('imports.create') }}" class="group flex items-center p-4 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-600 rounded-2xl transition-all border border-emerald-100 dark:border-emerald-800/30">
                        <div class="w-10 h-10 bg-white dark:bg-gray-800 text-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-bold text-emerald-900 dark:text-emerald-100 group-hover:text-white transition-colors">Importar Excel</span>
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

            <!-- Mini Pulse and Status -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-3xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-gray-400 tracking-[0.2em]">Ocupación Regional</span>
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['total_locations'] }} Sedes</span>
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

                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-center">
                        <div class="inline-flex items-center px-4 py-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-full border border-green-100 dark:border-green-800/30 text-[10px] font-black uppercase tracking-widest gap-2">
                            <div class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </div>
                            Sistema Operativo
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
