@extends('layouts.app')

@section('page-title', 'Resultados de Búsqueda')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Resultados para: "{{ $query }}"</h2>
        <p class="text-gray-600">{{ $totalResults }} resultado(s) encontrado(s)</p>
    </div>

    @if($totalResults == 0)
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-gray-300 mb-4">
                <i class="fas fa-search text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No se encontraron resultados</h3>
            <p class="text-gray-500 mt-1">Intenta con otros términos de búsqueda.</p>
        </div>
    @else
        <!-- Assets -->
        @if($assets->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-box mr-2 text-gray-600"></i>Activos ({{ $assets->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($assets as $asset)
                <a href="{{ route('assets.show', $asset->id) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $asset->name }}</p>
                            <p class="text-sm text-gray-500">ID: {{ $asset->custom_id }} | Modelo: {{ $asset->model ?? 'N/A' }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Locations -->
        @if($locations->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>Ubicaciones ({{ $locations->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($locations as $location)
                <a href="{{ route('locations.show', $location->id) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $location->name }}</p>
                            <p class="text-sm text-gray-500">{{ $location->address ?? 'Sin dirección' }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Categories -->
        @if($categories->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-folder mr-2 text-yellow-500"></i>Categorías ({{ $categories->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->id) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Employees -->
        @if($employees->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-users mr-2 text-purple-500"></i>Empleados ({{ $employees->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($employees as $employee)
                <a href="{{ route('employees.show', $employee->id) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $employee->email }} | {{ $employee->department ?? 'Sin departamento' }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Suppliers -->
        @if($suppliers->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-truck mr-2 text-red-500"></i>Proveedores ({{ $suppliers->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($suppliers as $supplier)
                <a href="{{ route('suppliers.show', $supplier->id) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $supplier->name }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    @endif
</div>
@endsection
