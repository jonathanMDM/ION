@extends('layouts.app')

@section('page-title', 'Reporte de Movimientos')

@section('content')
<!-- Tabs de Reportes -->
<div class="mb-6 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
        <li class="me-2">
            <a href="{{ route('reports.index') }}" class="inline-block p-4 border-b-2 {{ !request()->routeIs('reports.movements') ? 'text-[#5483B3] border-[#5483B3]/600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} rounded-t-lg">
                <i class="fas fa-box mr-2"></i>Inventario de Activos
            </a>
        </li>
        @if(auth()->user()->company->hasModule('transfers'))
        <li class="me-2">
            <a href="{{ route('reports.movements') }}" class="inline-block p-4 border-b-2 {{ request()->routeIs('reports.movements') ? 'text-[#5483B3] border-[#5483B3]/600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} rounded-t-lg">
                <i class="fas fa-exchange-alt mr-2"></i>Historial de Movimientos
            </a>
        </li>
        @endif
    </ul>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
        <i class="fas fa-search-location text-indigo-500 mr-2"></i>Filtrar Movimientos
    </h3>
    <form method="GET" action="{{ route('reports.movements') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Activo</label>
            <select name="asset_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
                <option value="">Todos los Activos</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>
                        {{ $asset->name }} ({{ $asset->custom_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Usuario (Realizado por)</label>
            <select name="user_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
                <option value="">Todos los Usuarios</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
        </div>

        <div class="flex items-end md:col-span-4">
            <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow-sm transition-colors uppercase tracking-widest text-xs">
                <i class="fas fa-search mr-2"></i>Consultar Historial de Trazabilidad
            </button>
        </div>
    </form>
</div>

<!-- Results Table -->
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="min-w-full w-full table-auto text-sm">
            <thead>
                <tr class="bg-[#5483B3] text-white uppercase text-[10px] font-black tracking-widest leading-normal">
                    <th class="py-4 px-6 text-left">Fecha / Hora</th>
                    <th class="py-4 px-6 text-left">Activo</th>
                    <th class="py-4 px-6 text-center">De (Origen)</th>
                    <th class="py-4 px-6 text-center"><i class="fas fa-arrow-right"></i></th>
                    <th class="py-4 px-6 text-center">A (Destino)</th>
                    <th class="py-4 px-6 text-left">Motivo / Razón</th>
                    <th class="py-4 px-6 text-left">Usuario</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300">
                @forelse($movements as $movement)
                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-indigo-50/50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="py-4 px-6 text-left font-mono text-xs">
                        {{ $movement->moved_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="py-4 px-6 text-left">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 dark:text-white">{{ $movement->asset->name ?? 'Activo Eliminado' }}</span>
                            <span class="text-[10px] text-gray-400">{{ $movement->asset->custom_id ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2 py-1 bg-red-50 text-red-700 rounded text-[10px] uppercase font-bold border border-red-100">
                            {{ $movement->fromLocation->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center text-gray-400">
                        <i class="fas fa-long-arrow-alt-right"></i>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2 py-1 bg-[#C1E8FF]/50 text-[#052659] rounded text-[10px] uppercase font-bold border border-[#5483B3]/100">
                            {{ $movement->toLocation->name }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-left text-xs italic">
                        {{ $movement->reason }}
                    </td>
                    <td class="py-4 px-6 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-300 mr-2"></i>
                            {{ $movement->user->name }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500 italic bg-gray-50">
                        No hay registros de movimientos que coincidan con los criterios.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600">
        {{ $movements->links() }}
    </div>
    @endif
</div>
@endsection
