@extends('layouts.superadmin')

@section('page-title', 'Dashboard Global')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Companies -->
    <div class="p-6 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #052659 0%, #021024 100%);">
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-80 text-white">Empresas Registradas</p>
                <p class="text-3xl font-black tracking-tight text-white mb-1">{{ $stats['total_companies'] }}</p>
                <p class="text-xs font-medium text-white/90">Gestión global</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                <i class="fas fa-building"></i>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
    </div>

    <!-- Active Companies -->
    <div class="p-6 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #5483B3 0%, #052659 100%);">
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">Empresas Activas</p>
                <p class="text-3xl font-black tracking-tight text-white mb-1">{{ $stats['active_companies'] }}</p>
                <p class="text-xs font-medium text-white/90">En operación</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
    </div>

    <!-- Total Users -->
    <div class="p-6 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #7DA0CA 0%, #5483B3 100%);">
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-90 text-white">Usuarios Totales</p>
                <p class="text-3xl font-black tracking-tight text-white mb-1">{{ $stats['total_users'] }}</p>
                <p class="text-xs font-medium text-white/90">Plataforma ION</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
    </div>

    <!-- Total Assets -->
    <div class="p-6 rounded-3xl shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group" style="background: linear-gradient(135deg, #475569 0%, #0F172A 100%);">
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1 opacity-80 text-white">Activos Gestionados</p>
                <p class="text-3xl font-black tracking-tight text-white mb-1">{{ $stats['total_assets'] }}</p>
                <p class="text-xs font-medium text-white/90">Total consolidado</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-md backdrop-blur-md bg-white/10 text-white border border-white/10">
                <i class="fas fa-box"></i>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-2xl group-hover:bg-white/10 transition-all"></div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
    <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-history text-blue-medium"></i>
            Empresas Recientes
        </h3>
        <a href="{{ route('superadmin.companies.index') }}" class="text-blue-medium hover:text-blue-dark text-sm font-bold uppercase tracking-wider">Ver todas</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Empresa</th>
                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Email</th>
                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Estado</th>
                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Registro</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recent_companies as $company)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-8 py-5 whitespace-nowrap">
                        <div class="text-sm font-bold text-slate-800 dark:text-white">{{ $company->name }}</div>
                        <div class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ $company->nit ?? 'N/A' }}</div>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600">
                        {{ $company->email }}
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap">
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $company->status === 'active' ? 'bg-blue-lightest text-blue-dark' : 'bg-red-100 text-red-800' }}">
                            {{ $company->status === 'active' ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">
                        {{ $company->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-slate-500 font-medium">No hay empresas registradas aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
