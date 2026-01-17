@extends('layouts.app')

@section('page-title', 'Panel')

@section('content')

<!-- Global Dashboard Header -->
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-none mb-1">
                Panel de Control
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium tracking-wide">
                Resumen ejecutivo de su inventario y operaciones.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center">
                <i class="far fa-calendar-alt mr-2 text-indigo-500"></i>
                {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM, YYYY') }}
            </span>
            <button onclick="window.location.reload()" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:rotate-180 duration-500">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
</div>

@if(!auth()->user()->company_id)
<div class="mb-10">
    <div class="bg-gradient-to-br from-indigo-900 via-indigo-950 to-violet-950 rounded-3xl p-10 shadow-2xl relative overflow-hidden border border-white/5">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-60 h-60 bg-purple-500/20 rounded-full blur-[80px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-shrink-0 bg-white/5 p-6 rounded-2xl backdrop-blur-md border border-white/10 shadow-2xl">
                <i class="fas fa-building-circle-exclamation text-5xl text-indigo-300"></i>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-3xl font-black text-white mb-3 tracking-tight">Acceso Restringido</h2>
                <p class="text-indigo-100 text-lg leading-relaxed max-w-2xl opacity-90 font-medium">
                    Su cuenta está activa, pero falta la vinculación a una organización para comenzar a registrar activos y equipos.
                </p>
            </div>
            <a href="https://wa.me/573145781261" target="_blank" class="px-8 py-4 bg-white text-indigo-900 font-black rounded-2xl hover:bg-indigo-50 transition-all shadow-xl hover:shadow-white/10 transform hover:-translate-y-1 active:scale-95 flex items-center justify-center uppercase text-sm tracking-widest">
                <i class="fas fa-headset mr-3 text-lg"></i>
                Contactar Soporte
            </a>
        </div>
    </div>
</div>
@endif

<!-- Subscription Warning -->
@if(isset($subscriptionWarning))
<div class="mb-10">
    @if(isset($subscriptionWarning['is_expired']) && $subscriptionWarning['is_expired'])
    <div class="bg-gradient-to-r from-red-600 to-rose-700 rounded-3xl shadow-2xl shadow-red-500/20 p-8 text-white relative overflow-hidden group">
        <div class="absolute top-1/2 right-0 -translate-y-1/2 opacity-10 transform scale-150 rotate-12 group-hover:rotate-6 transition-transform duration-700 font-black text-9xl">EXPIRADO</div>
        <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
            <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm border border-white/20">
                <i class="fas fa-calendar-times text-4xl"></i>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-black mb-1">Suscripción Expirada</h3>
                <p class="text-red-50 opacity-90 font-medium">El acceso a los módulos está suspendido desde el {{ $subscriptionWarning['expires_at'] }}.</p>
            </div>
            <button class="px-8 py-4 bg-red-950 text-white font-black rounded-2xl hover:bg-black transition-all flex items-center tracking-widest text-xs uppercase">
                Renovar Ahora
            </button>
        </div>
    </div>
    @elseif(isset($subscriptionWarning['is_critical']) && $subscriptionWarning['is_critical'])
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-3xl shadow-2xl shadow-orange-500/20 p-8 text-white relative overflow-hidden animate-pulse">
        <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
            <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm border border-white/20">
                <i class="fas fa-clock text-4xl"></i>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-black mb-1 italic tracking-tight">¡ALERTA DE VENCIMIENTO!</h3>
                <p class="text-orange-50 text-lg">Quedan solo <span class="font-black text-3xl">{{ $subscriptionWarning['days_left'] }}</span> {{ $subscriptionWarning['days_left'] == 1 ? 'día' : 'días' }} de servicio contratado.</p>
            </div>
            <p class="text-xs font-black uppercase tracking-widest border-2 border-white/30 px-4 py-2 rounded-lg">Acción Requerida</p>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 mb-12">
    <!-- Total Asset Master Card -->
    <div class="group bg-gradient-to-br from-gray-900 to-indigo-950 rounded-[2rem] p-8 shadow-2xl relative overflow-hidden transition-all duration-500 hover:scale-[1.02] border border-white/5 min-h-[160px] flex flex-col justify-center">
        <div class="absolute -right-2 -bottom-2 text-white/5 transform group-hover:-translate-x-2 group-hover:-translate-y-2 transition-transform duration-700">
            <i class="fas fa-box-open text-8xl"></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-3 opacity-60">
                <i class="fas fa-inventory text-indigo-400"></i>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white">Inventario Total</span>
            </div>
            <div class="flex items-baseline gap-3">
                <span class="text-6xl font-black text-white leading-none tracking-tighter">{{ $stats['total_assets'] ?? 0 }}</span>
                <span class="text-indigo-400 font-bold text-xs tracking-widest uppercase">Unidades</span>
            </div>
        </div>
    </div>

    <!-- Active Status Card -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-xl shadow-gray-200/50 dark:shadow-none relative overflow-hidden transition-all duration-500 hover:scale-[1.02] border border-gray-50 dark:border-gray-700 min-h-[160px] flex flex-col justify-center">
        <div class="absolute -right-2 -bottom-2 text-green-500/5 dark:text-green-500/10 transition-transform duration-700 group-hover:scale-110">
            <i class="fas fa-check-double text-8xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 mb-3 flex items-center uppercase tracking-widest">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                Activos Operativos
            </p>
            <p class="text-6xl font-black text-gray-900 dark:text-white tracking-tighter leading-none">{{ $stats['active_assets'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Maintenance Status Card -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-xl shadow-gray-200/50 dark:shadow-none relative overflow-hidden transition-all duration-500 hover:scale-[1.02] border border-gray-50 dark:border-gray-700 min-h-[160px] flex flex-col justify-center">
        <div class="absolute -right-2 -bottom-2 text-orange-500/5 dark:text-orange-500/10 transition-transform duration-700 group-hover:rotate-12">
            <i class="fas fa-screwdriver-wrench text-8xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 mb-3 flex items-center uppercase tracking-widest">
                <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                En Servicio Técnico
            </p>
            <p class="text-6xl font-black text-orange-600 tracking-tighter leading-none">{{ $stats['maintenance_assets'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Security/Withdrawal Card -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-xl shadow-gray-200/50 dark:shadow-none relative overflow-hidden transition-all duration-500 hover:scale-[1.02] border border-gray-50 dark:border-gray-700 min-h-[160px] flex flex-col justify-center">
        <div class="absolute -right-2 -bottom-2 text-rose-500/5 dark:text-rose-500/10 transition-transform duration-700 group-hover:-rotate-6">
            <i class="fas fa-trash-can text-8xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 mb-3 uppercase tracking-widest">Activos de Baja</p>
            <div class="flex items-baseline gap-3">
                <p class="text-6xl font-black text-rose-600 tracking-tighter leading-none">{{ $stats['decommissioned_assets'] ?? 0 }}</p>
                <span class="text-rose-400 font-bold text-xs uppercase tracking-tighter">Histórico</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid: Asymmetrical Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
    
    <!-- Left Area: Alerts and Recent Activity (8 columns) -->
    <div class="lg:col-span-8 space-y-10">
        
        <!-- Premium Low Stock Widget -->
        @if($lowStockAssets->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-indigo-500/5 border border-indigo-100 dark:border-indigo-900/30 overflow-hidden">
            <div class="px-10 py-8 bg-gradient-to-r from-red-50/50 to-orange-50/50 dark:from-red-900/10 dark:to-orange-900/10 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-red-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/30 mr-5 transform -rotate-3">
                        <i class="fas fa-boxes-stacked text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-none mb-1">Stock Crítico</h3>
                        <p class="text-gray-500 font-bold text-sm tracking-wide">{{ $lowStockAssets->count() }} ítems requieren reposición inmediata</p>
                    </div>
                </div>
                <a href="{{ route('assets.index') }}" class="px-6 py-3 bg-white dark:bg-gray-700 text-red-600 dark:text-red-400 font-bold rounded-xl shadow-sm border border-red-100 dark:border-red-900/30 hover:scale-105 transition-all text-sm uppercase tracking-widest">
                    Ver Inventario
                </a>
            </div>
            
            <div class="p-4 overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach($lowStockAssets as $asset)
                        <tr class="group hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-all duration-300">
                            <td class="py-5 px-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-4 group-hover:bg-red-100 dark:group-hover:bg-red-800/20 transition-colors">
                                        <i class="fas fa-microchip text-gray-400 dark:text-gray-500 group-hover:text-red-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 dark:text-white tracking-tight">{{ $asset->name }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-500 tracking-tighter">{{ $asset->location->name }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-[10px] font-black uppercase text-indigo-500 tracking-tighter">{{ $asset->subcategory->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-xl font-black {{ $asset->quantity == 0 ? 'text-red-600' : 'text-orange-600' }} tracking-tighter leading-none">{{ $asset->quantity }}</span>
                                    <span class="text-[8px] font-black uppercase text-gray-400 mt-1">DISPONIBLE</span>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden max-w-[80px] mx-auto">
                                    <div class="bg-red-500 h-full rounded-full" style="width: {{ ($asset->quantity / ($asset->minimum_quantity ?: 1)) * 100 }}%"></div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-right">
                                <a href="{{ route('assets.edit', $asset->id) }}" class="p-3 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all inline-flex items-center justify-center">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Activity Table Redesign -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl dark:shadow-none border border-gray-100 dark:border-gray-700 p-8">
            <div class="flex items-center justify-between mb-8 px-2">
                <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Activos Recientes</h3>
                <a href="{{ route('assets.index') }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Ver catálogo completo</a>
            </div>
            
            <div class="space-y-4">
                @foreach($recent_assets as $asset)
                <div class="group flex items-center justify-between p-4 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-transparent hover:border-indigo-100 dark:hover:border-indigo-900/30 hover:bg-white dark:hover:bg-gray-900 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center overflow-hidden">
                            @if($asset->image)
                                <img src="{{ \Illuminate\Support\Str::startsWith($asset->image, 'http') ? $asset->image : asset('storage/' . $asset->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <i class="fas fa-image text-gray-300"></i>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('assets.show', $asset->id) }}" class="font-black text-gray-900 dark:text-white hover:text-indigo-600 transition-colors tracking-tight">{{ $asset->name }}</a>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="bg-gray-200/50 dark:bg-gray-700 px-2 py-0.5 rounded text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tighter">{{ $asset->subcategory->category->name }}</span>
                                <span class="text-[10px] font-bold text-gray-400">{{ $asset->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="hidden md:block text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Ubicación</p>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $asset->location->name }}</p>
                        </div>
                        <span class="px-3 py-1 text-[10px] font-black uppercase rounded-lg
                            {{ $asset->status == 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                            {{ $asset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                            {{ $asset->status == 'decommissioned' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                            {{ ucfirst($asset->status) }}
                        </span>
                    </div>
                </div>
                @endforeach

                @if($recent_assets->isEmpty())
                <div class="text-center py-20 px-10 bg-gray-50 rounded-[2rem] border border-dashed border-gray-200">
                    <i class="fas fa-folder-open text-6xl text-gray-200 mb-6"></i>
                    <h4 class="text-xl font-black text-gray-400 italic">No hay registros recientes</h4>
                    <p class="text-gray-400 text-sm mt-2 max-w-xs mx-auto">Comience agregando su primer activo para ver el historial aquí.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Area: Quick Actions & Mini Stats (4 columns) -->
    <div class="lg:col-span-4 space-y-10">
        
        <!-- Premium Quick Actions -->
        <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[2.5rem] shadow-2xl p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-bolt text-9xl"></i>
            </div>
            
            <h3 class="text-xl font-black tracking-tight mb-8 relative z-10 flex items-center">
                <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
                    <i class="fas fa-flash text-sm"></i>
                </span>
                Acciones Rápidas
            </h3>
            
            <div class="grid grid-cols-1 gap-4 relative z-10">
                @if(Auth::user()->hasPermission('create_assets'))
                <a href="{{ route('assets.create') }}" class="group flex items-center p-5 bg-white/10 hover:bg-white text-white hover:text-indigo-900 rounded-2xl border border-white/20 hover:border-white transition-all duration-500 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-white/10 group-hover:bg-indigo-100 flex items-center justify-center mr-4 transition-colors">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </div>
                    <div>
                        <span class="font-black text-sm tracking-tight block leading-none">Nuevo Activo</span>
                        <span class="text-[10px] font-bold opacity-60 group-hover:opacity-100 uppercase tracking-widest mt-1 block">Registro Central</span>
                    </div>
                </a>
                @endif

                @if(Auth::user()->hasPermission('import_assets'))
                <a href="{{ route('imports.create') }}" class="group flex items-center p-5 bg-white/10 hover:bg-emerald-500 text-white rounded-2xl border border-white/20 hover:border-emerald-400 transition-all duration-500 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mr-4">
                        <i class="fas fa-file-excel text-xl"></i>
                    </div>
                    <span class="font-black text-sm tracking-tight block">Importar Excel</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('create_maintenance'))
                <a href="{{ route('maintenances.create') }}" class="group flex items-center p-5 bg-white/10 hover:bg-amber-400 text-white rounded-2xl border border-white/20 hover:border-amber-300 transition-all duration-500 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mr-4">
                        <i class="fas fa-wrench text-xl"></i>
                    </div>
                    <span class="font-black text-sm tracking-tight block">Mantenimiento</span>
                </a>
                @endif
                
                <a href="{{ route('reports.index') }}" class="group flex items-center p-5 bg-white/10 hover:bg-sky-400 text-white rounded-2xl border border-white/20 hover:border-sky-300 transition-all duration-500 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mr-4">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <span class="font-black text-sm tracking-tight block">Centro de Reportes</span>
                </a>
            </div>
        </div>

        <!-- System Status Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-xl dark:shadow-none border border-gray-100 dark:border-gray-700 space-y-8">
            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Estado Regional</h4>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between group cursor-help">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xl border border-indigo-100 dark:border-indigo-900/30 group-hover:scale-110 transition-transform">
                            {{ $stats['total_locations'] }}
                        </div>
                        <span class="font-black text-gray-700 dark:text-gray-300 tracking-tight">Ubicaciones</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-200 text-xs"></i>
                </div>
                
                <div class="flex items-center justify-between group cursor-help">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center font-black text-xl border border-purple-100 dark:border-purple-900/30 group-hover:scale-110 transition-transform">
                            {{ $stats['total_categories'] }}
                        </div>
                        <span class="font-black text-gray-700 dark:text-gray-300 tracking-tight">Estrategias/Cat.</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-200 text-xs"></i>
                </div>

                <div class="flex items-center justify-between group cursor-help border-t border-gray-50 dark:border-gray-700 pt-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-xl border border-emerald-100 dark:border-emerald-900/30 group-hover:scale-110 transition-transform">
                            {{ $stats['total_maintenances'] }}
                        </div>
                        <span class="font-black text-gray-700 dark:text-gray-300 tracking-tight">Servicios Realizados</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-200 text-xs"></i>
                </div>
            </div>

            <!-- Mini Pulse indicator -->
            <div class="pt-4 flex items-center justify-center">
                <div class="flex items-center gap-3 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-full border border-emerald-100 dark:border-emerald-900/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase text-emerald-600 dark:text-emerald-400 tracking-widest">Sistema Operativo</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
