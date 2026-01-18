@extends('layouts.app')

@section('page-title', 'Panel de Control')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-black tracking-tight" style="color: var(--text-main);">Panel Principal</h2>
        <p class="mt-1 font-medium" style="color: var(--text-secondary);">Gestión integral de activos y métricas operativas.</p>
    </div>
    
    <!-- Top Stats Banner -->
    <div class="relative overflow-hidden rounded-3xl shadow-xl mb-10 group" style="background: #FFFFFF; border: 1px solid var(--border-light); box-shadow: var(--card-shadow);">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 p-6">
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center px-4 py-2.5 rounded-2xl" style="background: #F4F7FE;">
                    <i class="far fa-calendar-alt mr-2.5" style="color: var(--color-primary);"></i>
                    <span class="text-sm font-bold" style="color: var(--text-main);">
                        {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM, YYYY') }}
                    </span>
                </div>
                <button onclick="window.location.reload()" class="p-2.5 rounded-xl shadow-sm transition-all hover:bg-white" style="background: #F4F7FE; color: var(--text-secondary);">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
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

    <!-- Metrics Grid (Pastel Gradients) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Assets (Blue) -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 relative overflow-hidden" style="background: var(--gradient-blue);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-80" style="color: #FFFFFF;">Activos Totales</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: #FFFFFF;">
                        {{ $stats['total_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: rgba(255,255,255,0.8);">Unidades en sistema</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg glass-icon" style="background: rgba(255,255,255,0.2); color: #FFFFFF;">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <!-- Decorative Circle -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white opacity-10 blur-xl"></div>
        </div>

        <!-- Operative (Green) -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 relative overflow-hidden" style="background: var(--gradient-green);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-80" style="color: #FFFFFF;">Operativos</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: #FFFFFF;">
                        {{ $stats['active_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: rgba(255,255,255,0.8);">Estado: Activo</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg glass-icon" style="background: rgba(255,255,255,0.2); color: #FFFFFF;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white opacity-10 blur-xl"></div>
        </div>

        <!-- Maintenance (Orange) -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 relative overflow-hidden" style="background: var(--gradient-orange);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-80" style="color: #FFFFFF;">En Taller</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: #FFFFFF;">
                        {{ $stats['maintenance_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: rgba(255,255,255,0.8);">Requiere atención</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg glass-icon" style="background: rgba(255,255,255,0.2); color: #FFFFFF;">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white opacity-10 blur-xl"></div>
        </div>

        <!-- Decommissioned (Dark/Red) -->
        <div class="p-6 rounded-3xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 relative overflow-hidden" style="background: #FFFFFF; border: 1px solid var(--border-light);">
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: var(--text-secondary);">Bajas</p>
                    <h3 class="text-4xl font-extrabold leading-tight" style="color: var(--text-main);">
                        {{ $stats['decommissioned_assets'] ?? 0 }}
                    </h3>
                    <p class="text-xs font-bold mt-1" style="color: var(--color-lust);">Fuera de servicio</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg" style="background: rgba(238, 93, 80, 0.1); color: var(--color-lust);">
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
            <div class="rounded-3xl shadow-sm p-6" style="background: #FFFFFF; box-shadow: var(--card-shadow); border: 1px solid #FEE2E2;">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold" style="color: var(--text-main);">Alertas de Stock</h4>
                    <span class="text-xs px-2 py-1 rounded flex items-center gap-1 font-bold" style="background: #FEF2F2; color: var(--color-lust);">
                        <i class="fas fa-exclamation-triangle"></i>
                        Atención Requerida
                    </span>
                </div>
                <div class="space-y-3">
                    @foreach($lowStockAssets as $asset)
                    <div class="flex items-center justify-between p-3 rounded-xl border transition-colors hover:bg-red-50" style="background: #FFF5F5; border-color: #FECACA;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white shadow-sm" style="color: var(--color-lust);">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold" style="color: var(--text-main);">{{ $asset->name }}</h5>
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
            <div class="rounded-3xl shadow-sm p-6" style="background: #FFFFFF; box-shadow: var(--card-shadow);">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold" style="color: var(--text-main);">Activos Recientes</h4>
                    <a href="{{ route('assets.index') }}" class="text-xs font-bold hover:opacity-80 transition-opacity" style="color: var(--color-primary);">Ver Todo</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentAssets as $asset)
                    <div class="flex items-center justify-between group p-2 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                @if($asset->image_path)
                                <img src="{{ asset('storage/' . $asset->image_path) }}" class="w-12 h-12 rounded-xl object-cover border shadow-sm" style="border-color: var(--border-light);" alt="{{ $asset->name }}">
                                @else
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm" style="background: #F4F7FE; border-color: var(--border-light); color: var(--color-secondary);">
                                    <i class="fas fa-cube text-lg"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold" style="color: var(--text-main);">{{ $asset->name }}</p>
                                <p class="text-[10px] font-medium" style="color: var(--text-secondary);">{{ $asset->category->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('assets.show', $asset->id) }}" class="text-xs font-bold hover:underline" style="color: var(--color-primary);">Ver</a>
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
            <div class="rounded-3xl shadow-sm p-6" style="background: #FFFFFF; box-shadow: var(--card-shadow);">
                <h4 class="text-lg font-bold mb-6" style="color: var(--text-main);">Acciones Rápidas</h4>
                <div class="grid grid-cols-1 gap-3">
                    @if(Auth::user()->hasPermission('create_assets'))
                    <a href="{{ route('assets.create') }}" class="group flex items-center p-4 rounded-2xl transition-all hover:translate-x-1" style="background: #F4F7FE;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background: var(--gradient-orange); color: #FFFFFF;">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-bold group-hover:text-amber-600 transition-colors" style="color: var(--text-main);">Nuevo Activo</span>
                            <span class="block text-[10px] uppercase tracking-widest font-bold" style="color: var(--text-secondary);">Registro Central</span>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('import_assets'))
                    <a href="{{ route('imports.create') }}" class="group flex items-center p-4 rounded-2xl transition-all hover:translate-x-1" style="background: #F4F7FE;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background: var(--gradient-blue); color: #FFFFFF;">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-bold group-hover:text-blue-600 transition-colors" style="color: var(--text-main);">Importar Excel</span>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="group flex items-center p-4 rounded-2xl transition-all hover:translate-x-1" style="background: #F4F7FE;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background: #FFFFFF; color: var(--color-primary);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="ml-4 text-gray-900 group-hover:text-indigo-600 transition-colors">
                            <span class="text-sm font-bold" style="color: var(--text-main);">Mis Reportes</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assets by Location & Regional Occupation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Assets by Location -->
        <div class="rounded-3xl shadow-lg p-6 relative overflow-hidden group" style="background: #FFFFFF; box-shadow: var(--card-shadow);">
            <div class="flex items-center justify-between mb-6 relative z-10">
                <h4 class="text-lg font-bold flex items-center" style="color: var(--text-main);">
                    Activos por Sede
                </h4>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $stats['total_locations'] }} Sedes
                </span>
            </div>
            
            <div class="space-y-4 relative z-10">
                @forelse($assets_per_location as $location)
                <div class="p-4 rounded-xl transition-all hover:translate-x-1" style="background: #F4F7FE;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background: #FFFFFF; color: var(--color-primary);">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h5 class="font-bold" style="color: var(--text-main);">{{ $location->name }}</h5>
                                <p class="text-[10px] text-gray-500 font-medium">{{ $location->address ?? 'Sin dirección' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-xl font-black" style="color: var(--color-primary);">{{ $location->assets_count }}</span>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">ACTIVOS</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl">
                    <i class="fas fa-map-marked-alt text-3xl mb-3 opacity-50"></i>
                    <p class="text-sm font-medium">No hay sedes registradas</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Regional Occupation (Categories) -->
        <div class="rounded-3xl shadow-lg p-6 relative overflow-hidden" style="background: #FFFFFF; box-shadow: var(--card-shadow);">
            <div class="flex items-center justify-between mb-6 relative z-10">
                <h4 class="text-lg font-bold tracking-widest uppercase text-xs" style="color: var(--text-main);">Ocupación Regional</h4>
                <span class="text-xs font-bold text-gray-400">{{ $stats['total_locations'] }} Sedes</span>
            </div>
            
            <div class="space-y-6 relative z-10">
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full ring-2 ring-indigo-100" style="background: var(--color-primary);"></span>
                        <span class="text-sm font-bold text-gray-600 group-hover:text-indigo-600 transition-colors">Categorías</span>
                    </div>
                    <span class="text-lg font-black" style="color: var(--text-main);">{{ $stats['total_categories'] }}</span>
                </div>
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full ring-2 ring-orange-100" style="background: var(--color-accent-orange);"></span>
                        <span class="text-sm font-bold text-gray-600 group-hover:text-orange-600 transition-colors">Mantenimientos</span>
                    </div>
                    <span class="text-lg font-black" style="color: var(--text-main);">{{ $stats['total_maintenances'] }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
