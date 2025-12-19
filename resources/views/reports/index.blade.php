@extends('layouts.app')

@section('page-title', 'Reportes')

@section('content')
<!-- Search Bar -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 mb-6 transition-colors">
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
        <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded">
            <i class="fas fa-search mr-2"></i>Buscar
        </button>
        @if(request()->hasAny(['search', 'status', 'location_id', 'category_id', 'subcategory_id', 'date_from', 'date_to', 'custom_id', 'municipality_plate']))
            <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                <i class="fas fa-times mr-2"></i>Limpiar
            </a>
        @endif
    </form>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-6 mb-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filtros Avanzados</h3>
    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
            <select name="status" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                <option value="">Todos los Estados</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                <option value="decommissioned" {{ request('status') == 'decommissioned' ? 'selected' : '' }}>Dado de Baja</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ubicación</label>
            <select name="location_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                <option value="">Todas las Ubicaciones</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
            <select name="category_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                <option value="">Todas las Categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subcategoría</label>
            <select name="subcategory_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                <option value="">Todas las Subcategorías</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ request('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                        {{ $subcategory->category->name }} - {{ $subcategory->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Proveedor</label>
            <select name="supplier_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                <option value="">Todos los Proveedores</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Modelo</label>
            <input type="text" name="model" value="{{ request('model') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ID Único</label>
            <input type="text" name="custom_id" value="{{ request('custom_id') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
        </div>

        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Placa Municipio</label>
            <input type="text" name="municipality_plate" value="{{ request('municipality_plate') }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
        </div>
        @endif

        @php
            $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
        @endphp
        @foreach($customFields as $field)
            @if(\App\Helpers\FieldHelper::isVisible($field->name))
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $field->label }}</label>
                <input type="text" name="custom_{{ $field->name }}" value="{{ request('custom_' . $field->name) }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
            </div>
            @endif
        @endforeach

        <div class="flex items-end">
            <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-filter mr-2"></i>Aplicar Filtros
            </button>
        </div>
    </form>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 transition-colors">
        <p class="text-sm text-gray-600 dark:text-gray-400">Total de Activos</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_assets'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 transition-colors">
        <p class="text-sm text-gray-600 dark:text-gray-400">Valor Total</p>
        <p class="text-2xl font-bold text-green-600">${{ number_format($stats['total_value'], 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 transition-colors">
        <p class="text-sm text-gray-600 dark:text-gray-400">Activos</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 transition-colors">
        <p class="text-sm text-gray-600 dark:text-gray-400">Mantenimiento</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['maintenance'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 transition-colors">
        <p class="text-sm text-gray-600 dark:text-gray-400">Dados de Baja</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['decommissioned'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900 p-4 transition-colors">
        <p class="text-sm text-gray-600 dark:text-gray-400">Con Placa</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['with_plate'] }}</p>
    </div>
</div>

<!-- Export Buttons -->
<div class="flex gap-4 mb-6">
    <form method="POST" action="{{ route('reports.pdf') }}">
        @csrf
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="location_id" value="{{ request('location_id') }}">
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        <input type="hidden" name="subcategory_id" value="{{ request('subcategory_id') }}">
        <input type="hidden" name="supplier_id" value="{{ request('supplier_id') }}">
        <input type="hidden" name="model" value="{{ request('model') }}">
        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
        <input type="hidden" name="custom_id" value="{{ request('custom_id') }}">
        <input type="hidden" name="municipality_plate" value="{{ request('municipality_plate') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-file-pdf mr-2"></i>Exportar a PDF
        </button>
    </form>

    <form method="POST" action="{{ route('reports.excel') }}">
        @csrf
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="location_id" value="{{ request('location_id') }}">
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        <input type="hidden" name="subcategory_id" value="{{ request('subcategory_id') }}">
        <input type="hidden" name="supplier_id" value="{{ request('supplier_id') }}">
        <input type="hidden" name="model" value="{{ request('model') }}">
        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
        <input type="hidden" name="custom_id" value="{{ request('custom_id') }}">
        <input type="hidden" name="municipality_plate" value="{{ request('municipality_plate') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-file-excel mr-2"></i>Exportar a Excel
        </button>
    </form>
</div>

<!-- Results Table -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded overflow-x-auto transition-colors">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">ID Único</th>
                <th class="py-3 px-6 text-left">Nombre</th>
                <th class="py-3 px-6 text-left">Modelo</th>
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <th class="py-3 px-6 text-left">Placa Municipio</th>
                @endif
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <th class="py-3 px-6 text-left">{{ $field->label }}</th>
                    @endif
                @endforeach
                <th class="py-3 px-6 text-left">Ubicación</th>
                <th class="py-3 px-6 text-left">Categoría</th>
                <th class="py-3 px-6 text-left">Proveedor</th>
                <th class="py-3 px-6 text-center">Estado</th>
                <th class="py-3 px-6 text-center">Cantidad</th>
                <th class="py-3 px-6 text-center">Valor</th>
                <th class="py-3 px-6 text-center">Fecha de Compra</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
            @foreach($assets as $asset)
            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                <td class="py-3 px-6 text-left">{{ $asset->custom_id }}</td>
                <td class="py-3 px-6 text-left">
                    <a href="{{ route('assets.show', $asset->id) }}" class="text-gray-800 hover:text-gray-900 font-medium">
                        {{ $asset->name }}
                    </a>
                </td>
                <td class="py-3 px-6 text-left">{{ $asset->model ?? '-' }}</td>
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <td class="py-3 px-6 text-left">{{ $asset->municipality_plate }}</td>
                @endif
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <td class="py-3 px-6 text-left">{{ $asset->custom_attributes[$field->name] ?? '-' }}</td>
                    @endif
                @endforeach
                <td class="py-3 px-6 text-left">{{ $asset->location->name }}</td>
                <td class="py-3 px-6 text-left">{{ $asset->subcategory->category->name }}</td>
                <td class="py-3 px-6 text-left">{{ $asset->supplier->name ?? 'N/A' }}</td>
                <td class="py-3 px-6 text-center">
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $asset->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $asset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $asset->status == 'decommissioned' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $asset->status == 'active' ? 'Activo' : ($asset->status == 'maintenance' ? 'Mantenimiento' : 'Dado de Baja') }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    <span class="font-semibold">{{ $asset->quantity }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                    <span class="font-semibold">${{ number_format($asset->value, 2) }}</span>
                </td>
                <td class="py-3 px-6 text-center">{{ $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
