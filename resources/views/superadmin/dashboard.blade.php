@extends('layouts.superadmin')

@section('page-title', 'Dashboard Global')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Companies -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-6 border-l-4 border-[#5483B3]/500 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Empresas Registradas</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total_companies'] }}</p>
            </div>
        </div>
    </div>

    <!-- Active Companies -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-6 border-l-4 border-emerald-500 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-emerald-100 text-emerald-500 mr-4">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Empresas Activas</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['active_companies'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-6 border-l-4 border-violet-500 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-violet-100 text-violet-500 mr-4">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Usuarios Totales</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Assets -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-6 border-l-4 border-amber-500 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 text-amber-500 mr-4">
                <i class="fas fa-box text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Activos Gestionados</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total_assets'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 mb-8 transition-colors">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Empresas Recientes</h3>
        <a href="{{ route('superadmin.companies.index') }}" class="text-[#5483B3] hover:text-indigo-800 text-sm font-medium">Ver todas</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Registro</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($recent_companies as $company)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->nit ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $company->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $company->status === 'active' ? 'bg-[#C1E8FF] text-[#052659]' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($company->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $company->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay empresas registradas aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
