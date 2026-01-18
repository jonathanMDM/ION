@extends('layouts.app')

@section('page-title', 'Centros de Costo')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Centros de Costo</h2>
            <p class="text-gray-600 mt-1">Gestiona los centros de costo y su presupuesto</p>
        </div>
        <a href="{{ route('cost-centers.create') }}" class="bg-[#5483B3] hover:bg-[#052659] text-white px-4 py-2 rounded font-bold">
            <i class="fas fa-plus mr-2"></i>Nuevo Centro de Costo
        </a>
    </div>
</div>

@if($costCenters->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay centros de costo registrados</h3>
        <p class="text-gray-500 mb-6">Comienza creando tu primer centro de costo para organizar tus activos</p>
        <a href="{{ route('cost-centers.create') }}" class="inline-block bg-[#5483B3] hover:bg-[#052659] text-white px-6 py-3 rounded font-bold">
            <i class="fas fa-plus mr-2"></i>Crear Centro de Costo
        </a>
    </div>
@else
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presupuesto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($costCenters as $center)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-semibold text-gray-900">{{ $center->code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $center->name }}</div>
                        @if($center->description)
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $center->description }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($center->manager)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-[#5483B3] font-semibold text-xs">{{ substr($center->manager->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $center->manager->name }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-sm text-gray-400">Sin asignar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($center->budget)
                            <div class="text-sm font-semibold text-gray-900">${{ number_format($center->budget, 0) }}</div>
                            @php
                                $used = $center->total_asset_value;
                                $percentage = $center->budget > 0 ? ($used / $center->budget) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full {{ $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-yellow-500' : 'bg-[#5483B3]') }}" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% usado</div>
                        @else
                            <span class="text-sm text-gray-400">Sin presupuesto</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $center->assets_count }} activos
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($center->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#C1E8FF] text-[#052659]">
                                Activo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('cost-centers.show', $center) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('cost-centers.edit', $center) }}" class="text-[#5483B3] hover:text-indigo-900 mr-3" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('cost-centers.toggle-status', $center) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3" title="{{ $center->is_active ? 'Desactivar' : 'Activar' }}">
                                <i class="fas fa-{{ $center->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                            </button>
                        </form>
                        @if($center->assets_count == 0)
                            <form action="{{ route('cost-centers.destroy', $center) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este centro de costo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $costCenters->links() }}
    </div>
@endif
@endsection
