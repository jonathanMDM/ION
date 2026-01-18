@extends('layouts.app')

@section('page-title', 'Panel de Control')

@section('content')
    <!-- Header & Controls Combined -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h2 class="text-4xl font-black tracking-tight" style="color: var(--text-main);">Panel Principal</h2>
            <p class="mt-2 text-lg font-medium" style="color: var(--text-secondary);">Gestión integral de activos y métricas operativas.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center px-5 py-3 rounded-2xl shadow-sm bg-white border border-gray-100">
                <i class="far fa-calendar-alt mr-3 text-indigo-500"></i>
                <span class="text-sm font-bold text-gray-600">
                    {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM, YYYY') }}
                </span>
            </div>
            <button onclick="window.location.reload()" class="p-3 rounded-2xl shadow-sm bg-white border border-gray-100 text-gray-400 hover:text-indigo-600 hover:shadow-md transition-all">
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

    <!-- Metrics Grid (High Contrast Forest Theme) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Assets (Deep Forest) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #076653 0%, #06231D 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-80 text-white">Activos Totales</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['total_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-medium text-white/90">Unidades en sistema</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>

        <!-- Operative (Emerald) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #10B981 0%, #047857 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">Operativos</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['active_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-medium text-white/90">Estado: Activo</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
             <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>

        <!-- Maintenance (Dark Lime) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #65A30D 0%, #365314 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">En Taller</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['maintenance_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-medium text-white/90">Requiere atención</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
             <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>

        <!-- Decommissioned (Dark Tech Gray) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #475569 0%, #0F172A 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-80 text-white">Bajas</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['decommissioned_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-medium text-white/90">Fuera de servicio</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
             <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>
    </div>

    <!-- Balanced Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Recent Activity and Alerts (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Stock Alerter (Conditional) -->
            @if($lowStockAssets->count() > 0)
            <div class="rounded-[2rem] p-8 bg-white border border-red-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-gray-800">Alertas de Stock</h4>
                    <span class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-2 bg-red-50 text-red-600">
                        <i class="fas fa-exclamation-triangle"></i>
                        Atención Requerida
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($lowStockAssets as $asset)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-red-50/50 border border-red-100 transition-colors hover:bg-red-50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-white shadow-sm text-red-500">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">{{ $asset->name }}</h5>
                                <p class="text-xs font-medium text-red-500">Stock Bajo: {{ $asset->stock }} unds</p>
                            </div>
                        </div>
                        <a href="{{ route('assets.show', $asset->id) }}" class="text-sm font-bold text-red-500 hover:underline">Ver</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Assets (Deep Forest Theme) -->
            <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#076653]/30" style="background: linear-gradient(135deg, #06231D 0%, #020C0A 100%);">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-white">Activos Recientes</h4>
                    <a href="{{ route('assets.index') }}" class="text-sm font-bold text-[#E3EF26] hover:text-white transition-colors uppercase tracking-wider flex items-center gap-2">
                        Ver Todo <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentAssets as $asset)
                    <div class="flex items-center justify-between group p-3 hover:bg-white/5 rounded-2xl transition-all border border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                @if($asset->image_path)
                                <img src="{{ asset('storage/' . $asset->image_path) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm border border-white/10" alt="{{ $asset->name }}">
                                @else
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#076653]/40 text-[#E3EF26] shadow-inner">
                                    <i class="fas fa-cube text-lg"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-white group-hover:text-[#E3EF26] transition-colors">{{ $asset->name }}</h5>
                                <p class="text-xs text-gray-400">{{ $asset->category->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('assets.show', $asset->id) }}" class="px-3 py-1.5 rounded-lg bg-[#E3EF26] text-[#06231D] text-xs font-bold hover:bg-white transition-colors shadow-md">Ver</a>
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-500 border-2 border-dashed border-gray-700/50 rounded-3xl">
                        <i class="fas fa-folder-open text-3xl mb-3 block opacity-30"></i>
                        <p class="text-sm font-medium">No se encontraron registros recientes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Column: Quick Actions and System Health (1/3 width) -->
        <div class="space-y-8 mb-10">
            
            <!-- Quick Actions (Dark Forest Theme) -->
            <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#076653]/30" style="background: linear-gradient(135deg, #0C342C 0%, #031814 100%);">
                <h4 class="text-xl font-bold mb-6 text-white">Acciones Rápidas</h4>
                <div class="grid grid-cols-1 gap-4">
                    @if(Auth::user()->hasPermission('create_assets'))
                    <a href="{{ route('assets.create') }}" class="group flex items-center p-3 rounded-2xl bg-[#076653]/40 hover:bg-[#E3EF26] transition-all border border-[#E3EF26]/20 hover:border-[#E3EF26]">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm bg-[#E3EF26] text-[#06231D] group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-gray-200 group-hover:text-[#06231D] transition-colors">Nuevo Activo</span>
                            <span class="block text-[10px] font-bold text-gray-500 group-hover:text-[#06231D] uppercase tracking-wider">Registro Central</span>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('import_assets'))
                    <a href="{{ route('imports.create') }}" class="group flex items-center p-3 rounded-2xl bg-[#076653]/40 hover:bg-[#E3EF26] transition-all border border-[#E3EF26]/20 hover:border-[#E3EF26]">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm bg-[#E3EF26] text-[#06231D] group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-excel text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-gray-200 group-hover:text-[#06231D] transition-colors">Importar Excel</span>
                            <span class="block text-[10px] font-bold text-gray-500 group-hover:text-[#06231D] uppercase tracking-wider">Carga Masiva</span>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="group flex items-center p-3 rounded-2xl bg-[#076653]/40 hover:bg-[#E3EF26] transition-all border border-[#E3EF26]/20 hover:border-[#E3EF26]">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm bg-[#E3EF26] text-[#06231D] group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-bar text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-gray-200 group-hover:text-[#06231D] transition-colors">Mis Reportes</span>
                            <span class="block text-[10px] font-bold text-gray-500 group-hover:text-[#06231D] uppercase tracking-wider">Estadísticas</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assets by Location & Regional Occupation (Dark Forest Theme) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Assets by Location -->
        <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] h-full border border-[#076653]/30" style="background: linear-gradient(135deg, #020C0A 0%, #06231D 100%);">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-bold flex items-center gap-3 text-white">
                    Activos por Sede
                </h4>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#E3EF26] text-[#06231D] flex items-center gap-2 shadow-sm">
                    <i class="fas fa-map-marker-alt"></i> {{ $stats['total_locations'] }} Sedes
                </span>
            </div>
            
            <div class="space-y-4">
                @forelse($assets_per_location as $location)
                <div class="p-4 rounded-2xl transition-all hover:bg-white/5 border border-white/5 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-[#076653]/40 text-[#E3EF26] group-hover:scale-110 transition-transform shadow-inner">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-white text-sm">{{ $location->name }}</h5>
                                <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $location->address ?? 'Sin dirección' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-xl font-black text-[#E3EF26]">{{ $location->assets_count }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">ACTIVOS</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-500 border-2 border-dashed border-gray-700/50 rounded-3xl">
                    <i class="fas fa-map-marker-alt text-4xl mb-4 opacity-20"></i>
                    <p class="text-sm font-medium">No hay sedes registradas</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Regional Occupation (Categories) -->
        <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] h-full border border-[#076653]/30" style="background: linear-gradient(135deg, #031814 0%, #0C342C 100%);">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-bold text-white">Ocupación Regional</h4>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $stats['total_locations'] }} Sedes</span>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 rounded-2xl bg-[#076653]/20 hover:bg-[#076653]/30 transition-colors cursor-default group border border-white/5">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 rounded-full bg-[#E3EF26] ring-4 ring-[#E3EF26]/20"></span>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-200">Categorías</span>
                            <span class="text-xs text-gray-500">Total registradas</span>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-[#E3EF26] group-hover:scale-110 transition-transform">{{ $stats['total_categories'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-4 rounded-2xl bg-[#65A30D]/20 hover:bg-[#65A30D]/30 transition-colors cursor-default group border border-white/5">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 rounded-full bg-[#65A30D] ring-4 ring-[#65A30D]/20"></span>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-200">Mantenimientos</span>
                            <span class="text-xs text-gray-500">En proceso</span>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-[#65A30D] group-hover:scale-110 transition-transform">{{ $stats['total_maintenances'] }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
