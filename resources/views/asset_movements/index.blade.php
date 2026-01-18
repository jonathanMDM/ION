@extends('layouts.app')

@section('page-title', 'Historial de Movimientos')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Historial de Movimientos de Activos</h2>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h3>
    <form method="GET" action="{{ route('asset-movements.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Activo</label>
            <select name="asset_id" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Todos los Activos</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>
                        {{ $asset->custom_id }} - {{ $asset->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
            <select name="location_id" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Todas las Ubicaciones</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="md:col-span-4 flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded">
                <i class="fas fa-filter mr-2"></i>Aplicar Filtros
            </button>
            @if(request()->hasAny(['asset_id', 'location_id', 'date_from', 'date_to']))
                <a href="{{ route('asset-movements.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-times mr-2"></i>Limpiar
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabla de Movimientos -->
<div class="bg-white shadow-md rounded overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Activo</th>
                <th class="py-3 px-6 text-left">De</th>
                <th class="py-3 px-6 text-center"><i class="fas fa-arrow-right"></i></th>
                <th class="py-3 px-6 text-left">A</th>
                <th class="py-3 px-6 text-left">Usuario</th>
                <th class="py-3 px-6 text-left">Fecha</th>
                <th class="py-3 px-6 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse($movements as $movement)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left">
                    <div>
                        <span class="font-medium">{{ $movement->asset->custom_id }}</span>
                        <br>
                        <span class="text-xs text-gray-500">{{ $movement->asset->name }}</span>
                    </div>
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                        {{ $movement->fromLocation->name ?? 'N/A' }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    <i class="fas fa-arrow-right text-gray-800"></i>
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="px-2 py-1 bg-blue-lightest text-blue-dark rounded text-xs">
                        {{ $movement->toLocation->name }}
                    </span>
                </td>
                <td class="py-3 px-6 text-left">
                    {{ $movement->user->name }}
                </td>
                <td class="py-3 px-6 text-left">
                    {{ $movement->moved_at->format('d/m/Y H:i') }}
                </td>
                <td class="py-3 px-6 text-center">
                    <a href="{{ route('asset-movements.show', $movement->id) }}" class="text-gray-800 hover:text-gray-900">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-gray-500">
                    No se encontraron movimientos.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6">
    {{ $movements->links() }}
</div>
@endsection
