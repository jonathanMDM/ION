@extends('layouts.app')

@section('page-title', 'Detalle Centro de Costo')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $costCenter->name }}</h2>
            <p class="text-gray-600 mt-1">Código: <span class="font-mono font-semibold">{{ $costCenter->code }}</span></p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('cost-centers.edit', $costCenter) }}" class="bg-[#5483B3] hover:bg-[#052659] text-white px-4 py-2 rounded font-bold">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('cost-centers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded font-bold">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-box text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Activos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_assets'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-[#5483B3]">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Valor Total</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_value'], 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Valor en Libros</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_book_value'], 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full {{ $stats['budget_used_percentage'] > 90 ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600' }}">
                <i class="fas fa-chart-pie text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Presupuesto Usado</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['budget_used_percentage'], 1) }}%</p>
            </div>
        </div>
    </div>
</div>

<!-- Información del Centro -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información General</h3>
        <dl class="space-y-3">
            <div>
                <dt class="text-sm text-gray-500">Código</dt>
                <dd class="text-sm font-mono font-semibold text-gray-900">{{ $costCenter->code }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Nombre</dt>
                <dd class="text-sm font-semibold text-gray-900">{{ $costCenter->name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Estado</dt>
                <dd>
                    @if($costCenter->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#C1E8FF] text-[#052659]">
                            Activo
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Inactivo
                        </span>
                    @endif
                </dd>
            </div>
            @if($costCenter->description)
            <div>
                <dt class="text-sm text-gray-500">Descripción</dt>
                <dd class="text-sm text-gray-900">{{ $costCenter->description }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Presupuesto</h3>
        @if($costCenter->budget)
            <div class="mb-4">
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">Asignado</span>
                    <span class="text-sm font-semibold text-gray-900">${{ number_format($costCenter->budget, 0) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">Utilizado</span>
                    <span class="text-sm font-semibold text-gray-900">${{ number_format($stats['total_value'], 0) }}</span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-sm text-gray-600">Disponible</span>
                    <span class="text-sm font-semibold {{ $costCenter->budget - $stats['total_value'] < 0 ? 'text-red-600' : 'text-[#5483B3]' }}">
                        ${{ number_format($costCenter->budget - $stats['total_value'], 0) }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full {{ $stats['budget_used_percentage'] > 90 ? 'bg-red-500' : ($stats['budget_used_percentage'] > 70 ? 'bg-yellow-500' : 'bg-[#5483B3]') }}" 
                         style="width: {{ min($stats['budget_used_percentage'], 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2 text-center">{{ number_format($stats['budget_used_percentage'], 1) }}% del presupuesto</p>
            </div>
            @if($stats['budget_used_percentage'] > 90)
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong>Alerta:</strong> El presupuesto está casi agotado
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <p class="text-sm text-gray-500">No se ha asignado presupuesto a este centro de costo</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Responsable</h3>
        @if($costCenter->manager)
            <div class="flex items-center mb-4">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-[#5483B3] font-semibold text-lg">{{ substr($costCenter->manager->name, 0, 2) }}</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $costCenter->manager->name }}</p>
                    <p class="text-sm text-gray-500">{{ $costCenter->manager->email }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mt-1">
                        {{ ucfirst($costCenter->manager->role) }}
                    </span>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-500">No se ha asignado un responsable a este centro de costo</p>
        @endif
    </div>
</div>

<!-- Lista de Activos -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Activos Asignados ({{ $costCenter->assets->count() }})</h3>
    
    @if($costCenter->assets->isEmpty())
        <p class="text-gray-500 text-center py-8">No hay activos asignados a este centro de costo</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor Compra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor Libros</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($costCenter->assets as $asset)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $asset->custom_id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $asset->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $asset->category->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $asset->location->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($asset->purchase_price, 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($asset->book_value, 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('assets.show', $asset) }}" class="text-[#5483B3] hover:text-indigo-900">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
