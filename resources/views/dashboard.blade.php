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

    <!-- Metrics Grid (Deep Teal Reference Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Assets (Deep Teal Gradient) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #054F44 0%, #032722 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">Activos Totales</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['total_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-black/40 mix-blend-overlay">Unidades en sistema</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>

        <!-- Operative (Deep Emerald) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #065F46 0%, #064E3B 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">Operativos</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['active_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-black/40 mix-blend-overlay">Estado: Activo</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
             <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>

        <!-- Maintenance (Deep Olive) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #3F6212 0%, #1A2E05 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">En Taller</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['maintenance_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold text-black/40 mix-blend-overlay">Requiere atención</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
             <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
        </div>

        <!-- Decommissioned (Dark Tech Gray) -->
        <div class="p-5 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #334155 0%, #0F172A 100%);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-80 text-white">Bajas</p>
                    <h3 class="text-4xl font-black tracking-tight text-white mb-1">
                        {{ $stats['decommissioned_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-medium text-white/50">Fuera de servicio</p>
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
            <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#10B981]/20" style="background: linear-gradient(135deg, #0A3D33 0%, #0C4A3E 100%);">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-white tracking-wide">Activos Recientes</h4>
                    <a href="{{ route('assets.index') }}" class="text-sm font-bold text-[#5EEAD4] hover:text-white transition-colors uppercase tracking-wider flex items-center gap-2">
                        Ver Todo <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentAssets as $asset)
                    <div class="flex items-center justify-between group p-3 rounded-2xl transition-all border border-[#5EEAD4]/20 hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md bg-[#0F3F3C]">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                @if($asset->image_path)
                                <img src="{{ asset('storage/' . $asset->image_path) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm border border-white/20" alt="{{ $asset->name }}">
                                @else
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#2DD4BF] text-[#042F2E] shadow-sm">
                                    <i class="fas fa-cube text-lg"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-white group-hover:text-[#5EEAD4] transition-colors tracking-wide">{{ $asset->name }}</h5>
                                <p class="text-xs font-bold text-[#CCFBF1] opacity-80">{{ $asset->category->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('assets.show', $asset->id) }}" class="px-3 py-1.5 rounded-lg bg-[#5EEAD4] text-[#042F2E] text-xs font-bold hover:bg-white transition-all shadow-sm">Ver</a>
                    </div>
                    @empty
                    <div class="p-12 text-center text-[#CCFBF1]/50 border-2 border-dashed border-[#5EEAD4]/30 rounded-3xl">
                        <i class="fas fa-folder-open text-3xl mb-3 block opacity-50"></i>
                        <p class="text-sm font-medium">No se encontraron registros recientes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Column: Quick Actions and System Health (1/3 width) -->
        <div class="space-y-8 mb-10">
            
            <!-- Quick Actions (Deep Forest Theme) -->
            <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#10B981]/20" style="background: linear-gradient(135deg, #0A3D33 0%, #0C4A3E 100%);">
                <h4 class="text-xl font-bold mb-6 text-white tracking-wide">Acciones Rápidas</h4>
                <div class="grid grid-cols-1 gap-4">
                    @if(Auth::user()->hasPermission('create_assets'))
                    <a href="{{ route('assets.create') }}" class="group flex items-center p-3 rounded-2xl transition-all border border-[#5EEAD4]/20 hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md bg-[#0F3F3C]">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm bg-[#5EEAD4] text-[#042F2E] group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-sm font-bold"></i>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-white group-hover:text-[#5EEAD4] transition-colors">Nuevo Activo</span>
                            <span class="block text-[10px] font-bold text-[#CCFBF1] opacity-70 group-hover:opacity-100 uppercase tracking-wider">Registro Central</span>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('import_assets'))
                    <a href="{{ route('imports.create') }}" class="group flex items-center p-3 rounded-2xl transition-all border border-[#5EEAD4]/20 hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md bg-[#0F3F3C]">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm bg-[#5EEAD4] text-[#042F2E] group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-excel text-sm font-bold"></i>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-white group-hover:text-[#5EEAD4] transition-colors">Importar Excel</span>
                            <span class="block text-[10px] font-bold text-[#CCFBF1] opacity-70 group-hover:opacity-100 uppercase tracking-wider">Carga Masiva</span>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="group flex items-center p-3 rounded-2xl transition-all border border-[#5EEAD4]/20 hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md bg-[#0F3F3C]">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm bg-[#5EEAD4] text-[#042F2E] group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-bar text-sm font-bold"></i>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-white group-hover:text-[#5EEAD4] transition-colors">Mis Reportes</span>
                            <span class="block text-[10px] font-bold text-[#CCFBF1] opacity-70 group-hover:opacity-100 uppercase tracking-wider">Estadísticas</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assets by Location & Regional Occupation (Deep Forest Theme) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Assets by Location -->
        <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] h-full border border-[#10B981]/20" style="background: linear-gradient(135deg, #0A3D33 0%, #0C4A3E 100%);">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-bold flex items-center gap-3 text-white tracking-wide">
                    Activos por Sede
                </h4>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#5EEAD4] text-[#042F2E] flex items-center gap-2 shadow-sm">
                    <i class="fas fa-map-marker-alt"></i> {{ $stats['total_locations'] }} Sedes
                </span>
            </div>
            
            <div class="space-y-4">
                @forelse($assets_per_location as $location)
                <div class="p-4 rounded-2xl transition-all border border-[#5EEAD4]/20 hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md bg-[#0F3F3C] group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-[#5EEAD4] text-[#042F2E] group-hover:scale-110 transition-transform shadow-sm">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-white text-sm tracking-wide">{{ $location->name }}</h5>
                                <p class="text-xs text-[#CCFBF1] opacity-80 font-bold mt-0.5">{{ $location->address ?? 'Sin dirección' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-xl font-black text-[#5EEAD4] drop-shadow-sm">{{ $location->assets_count }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-white/50">ACTIVOS</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-[#CCFBF1]/50 border-2 border-dashed border-[#5EEAD4]/30 rounded-3xl">
                    <i class="fas fa-map-marker-alt text-4xl mb-4 opacity-50"></i>
                    <p class="text-sm font-medium">No hay sedes registradas</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Regional Occupation (Categories) -->
        <div class="rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.1)] h-full border border-[#10B981]/20" style="background: linear-gradient(135deg, #0A3D33 0%, #0C4A3E 100%);">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-bold text-white tracking-wide">Ocupación Regional</h4>
                <span class="text-xs font-bold text-[#CCFBF1] opacity-80 uppercase tracking-widest">{{ $stats['total_locations'] }} Sedes</span>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 rounded-2xl transition-colors cursor-default group border border-[#5EEAD4]/20 bg-[#0F3F3C] hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 rounded-full bg-[#5EEAD4] ring-4 ring-[#5EEAD4]/20 shadow-sm"></span>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-white">Categorías</span>
                            <span class="text-xs text-[#CCFBF1] opacity-70">Total registradas</span>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-[#5EEAD4] group-hover:scale-110 transition-transform drop-shadow-sm">{{ $stats['total_categories'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-4 rounded-2xl transition-colors cursor-default group border border-[#5EEAD4]/20 bg-[#0F3F3C] hover:border-[#5EEAD4] hover:bg-[#134E4A]/80 shadow-md">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 rounded-full bg-[#84CC16] ring-4 ring-[#84CC16]/20 shadow-sm"></span>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-white">Mantenimientos</span>
                            <span class="text-xs text-[#CCFBF1] opacity-70">En proceso</span>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-[#84CC16] group-hover:scale-110 transition-transform">{{ $stats['total_maintenances'] }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
