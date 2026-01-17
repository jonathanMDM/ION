@extends('layouts.app')

@section('page-title', 'Reportes')

@section('content')
<!-- Tabs de Reportes -->
<div class="mb-6 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
        <li class="me-2">
            <a href="{{ route('reports.index') }}" class="inline-block p-4 border-b-2 {{ !request()->routeIs('reports.movements') ? 'text-indigo-600 border-indigo-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} rounded-t-lg">
                <i class="fas fa-box mr-2"></i>Inventario de Activos
            </a>
        </li>
        @if(auth()->user()->company->hasModule('transfers'))
        <li class="me-2">
            <a href="{{ route('reports.movements') }}" class="inline-block p-4 border-b-2 {{ request()->routeIs('reports.movements') ? 'text-indigo-600 border-indigo-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} rounded-t-lg">
                <i class="fas fa-exchange-alt mr-2"></i>Historial de Movimientos
            </a>
        </li>
        @endif
    </ul>
</div>

<!-- Search Bar -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 transition-colors">
    <form method="GET" action="{{ route('reports.index') }}" class="flex gap-4">
        <div class="flex-1">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Buscar por nombre, ID único, placa, especificaciones..."
                    class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm pl-10 py-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors"
                >
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-sm">
            <i class="fas fa-search mr-2"></i>Buscar
        </button>
        @if(request()->hasAny(['search', 'status', 'location_id', 'category_id', 'subcategory_id', 'date_from', 'date_to', 'cost_center_id', 'supplier_id']))
            <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow-sm">
                <i class="fas fa-times mr-2"></i>Limpiar
            </a>
        @endif
    </form>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
        <i class="fas fa-filter text-indigo-500 mr-2"></i>Filtros de Inventario
    </h3>
    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <input type="hidden" name="search" value="{{ request('search') }}">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
            <select name="status" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
                <option value="">Todos los Estados</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                <option value="decommissioned" {{ request('status') == 'decommissioned' ? 'selected' : '' }}>Dado de Baja</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ubicación</label>
            <select name="location_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
                <option value="">Todas las Ubicaciones</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(auth()->user()->company->hasModule('cost_centers'))
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Centro de Costo</label>
            <select name="cost_center_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
                <option value="">Todos los Centros</option>
                @foreach($costCenters as $center)
                    <option value="{{ $center->id }}" {{ request('cost_center_id') == $center->id ? 'selected' : '' }}>
                        {{ $center->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
            <select name="category_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
                <option value="">Todas las Categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Desde (Compra)</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Hasta (Compra)</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm">
        </div>

        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Placa Municipio</label>
            <input type="text" name="municipality_plate" value="{{ request('municipality_plate') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm" placeholder="Buscar por placa...">
        </div>
        @endif

        @php
            $customFields = \App\Models\CustomField::where('company_id', auth()->user()->company_id)->get();
        @endphp
        @foreach($customFields as $field)
            @if(\App\Helpers\FieldHelper::isVisible($field->name))
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $field->label }}</label>
                <input type="text" name="custom_{{ $field->name }}" value="{{ request('custom_' . $field->name) }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors text-sm" placeholder="Filtrar por {{ $field->label }}...">
            </div>
            @endif
        @endforeach

        <div class="flex items-end lg:col-span-2">
            <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow-sm transition-colors">
                <i class="fas fa-filter mr-2"></i>Aplicar Filtros Avanzados
            </button>
        </div>
    </form>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-indigo-500">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Activos</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_assets']) }}</p>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Valor Compra</p>
        <p class="text-2xl font-bold text-green-600">${{ number_format($stats['total_purchase_price'], 2) }}</p>
    </div>

    @if(auth()->user()->company->hasModule('depreciation'))
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Valor en Libros</p>
        <p class="text-2xl font-bold text-blue-600">${{ number_format($stats['total_current_value'], 2) }}</p>
        <p class="text-[10px] text-gray-400 mt-1">Suma de valores actuales (depreciados)</p>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-yellow-500">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">En Mantenimiento</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['maintenance'] }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dados de Baja</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['decommissioned'] }}</p>
    </div>
</div>

<!-- Export Buttons -->
<div class="flex flex-wrap gap-3 mb-6">
    <form method="POST" action="{{ route('reports.pdf') }}" class="inline">
        @csrf
        @foreach(request()->all() as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit" style="background-color: #b91c1c; border: 1px solid #991b1b; color: white; font-weight: bold; padding: 10px 24px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: background 0.2s;">
            <i class="fas fa-file-pdf" style="margin-right: 8px;"></i>Descargar PDF
        </button>
    </form>

    <form method="POST" action="{{ route('reports.excel') }}" class="inline">
        @csrf
        @foreach(request()->all() as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit" style="background-color: #15803d; border: 1px solid #166534; color: white; font-weight: bold; padding: 10px 24px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: background 0.2s;">
            <i class="fas fa-file-excel" style="margin-right: 8px;"></i>Exportar Excel (CSV)
        </button>
    </form>
</div>

<!-- Results Table -->
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="min-w-full w-full table-auto">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-[10px] font-black tracking-widest leading-normal">
                    <th class="py-4 px-6 text-left">Activo / ID</th>
                    <th class="py-4 px-6 text-left">Categoría</th>
                    <th class="py-4 px-6 text-left">Ubicación</th>
                    @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                    <th class="py-4 px-6 text-left">Placa M.</th>
                    @endif
                    @if(auth()->user()->company->hasModule('cost_centers'))
                    <th class="py-4 px-6 text-left">Centro Costo</th>
                    @endif
                    @foreach($customFields as $field)
                        @if(\App\Helpers\FieldHelper::isVisible($field->name))
                        <th class="py-4 px-6 text-left">{{ $field->label }}</th>
                        @endif
                    @endforeach
                    <th class="py-4 px-6 text-center">Cant.</th>
                    <th class="py-4 px-6 text-center">Estado</th>
                    <th class="py-4 px-6 text-right">P. Compra</th>
                    @if(auth()->user()->company->hasModule('depreciation'))
                    <th class="py-4 px-6 text-right">V. Actual</th>
                    @endif
                    <th class="py-4 px-6 text-center">Compra</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300 text-sm">
                @forelse($assets as $asset)
                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-indigo-50/30 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="py-4 px-6 text-left">
                        <div class="flex flex-col">
                            <a href="{{ route('assets.show', $asset->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold">
                                {{ $asset->name }}
                            </a>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $asset->custom_id }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-left">
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">
                            {{ $asset->subcategory->category->name }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-red-400 mr-2 text-xs"></i>
                            {{ $asset->location->name }}
                        </div>
                    </td>
                    @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                    <td class="py-4 px-6 text-left font-mono text-[10px]">
                        {{ $asset->municipality_plate ?? '-' }}
                    </td>
                    @endif
                    @if(auth()->user()->company->hasModule('cost_centers'))
                    <td class="py-4 px-6 text-left">
                        {{ $asset->costCenter->name ?? 'N/A' }}
                    </td>
                    @endif
                    @foreach($customFields as $field)
                        @if(\App\Helpers\FieldHelper::isVisible($field->name))
                        <td class="py-4 px-6 text-left text-xs">
                            {{ $asset->custom_attributes[$field->name] ?? '-' }}
                        </td>
                        @endif
                    @endforeach
                    <td class="py-4 px-6 text-center font-bold">
                        {{ $asset->quantity }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full
                            {{ $asset->status == 'active' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                            {{ $asset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : '' }}
                            {{ $asset->status == 'decommissioned' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                            {{ $asset->status == 'active' ? 'Activo' : ($asset->status == 'maintenance' ? 'Mantenimiento' : 'Baja') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right font-mono font-bold">
                        ${{ number_format($asset->purchase_price, 2) }}
                    </td>
                    @if(auth()->user()->company->hasModule('depreciation'))
                    <td class="py-4 px-6 text-right font-mono font-bold text-indigo-600">
                        ${{ number_format($asset->value, 2) }}
                    </td>
                    @endif
                    <td class="py-4 px-6 text-center text-xs">
                        {{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-gray-500 italic bg-gray-50">
                        No se encontraron activos con los filtros seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
