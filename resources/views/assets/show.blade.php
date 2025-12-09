@extends('layouts.app')

@section('page-title', 'Detalles del Activo')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-4 md:p-6 rounded shadow">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Detalles del Activo: {{ $asset->name }}</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('assets.qr', $asset->id) }}" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow transition duration-200 flex items-center">
                <i class="fas fa-qrcode mr-2"></i> Imprimir QR
            </a>
            @if(Auth::user()->hasPermission('edit_assets'))
            <a href="{{ route('assets.edit', $asset->id) }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">ID Único:</label>
                <p>{{ $asset->custom_id }}</p>
            </div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Modelo:</label>
                <p>{{ $asset->model ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Ubicación:</label>
                <p>{{ $asset->location->name }}</p>
            </div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Categoría:</label>
                <p>{{ $asset->subcategory->category->name }}</p>
            </div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Subcategoría:</label>
                <p>{{ $asset->subcategory->name }}</p>
            </div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Estado:</label>
                <span class="bg-{{ $asset->status == 'active' ? 'green' : ($asset->status == 'maintenance' ? 'yellow' : 'red') }}-200 text-{{ $asset->status == 'active' ? 'green' : ($asset->status == 'maintenance' ? 'yellow' : 'red') }}-600 py-1 px-3 rounded-full text-xs">
                    {{ ucfirst($asset->status) }}
                </span>
            </div>
        </div>
        <div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Valor:</label>
                <p>${{ number_format($asset->value, 2) }}</p>
            </div>
            <div class="mb-4">
                <label class="font-bold text-gray-700">Fecha de Compra:</label>
                <p>{{ $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : 'N/A' }}</p>
            </div>
            @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
            <div class="mb-4">
                <label class="font-bold text-gray-700">Placa Municipio:</label>
                <p>{{ $asset->municipality_plate }}</p>
            </div>
            @endif

            <!-- Custom Fields -->
            @php
                $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
                $customAttributes = $asset->custom_attributes ?? [];
            @endphp
            @foreach($customFields as $field)
                @if(\App\Helpers\FieldHelper::isVisible($field->name) && !empty($customAttributes[$field->name]))
                <div class="mb-4">
                    <label class="font-bold text-gray-700">{{ $field->label }}:</label>
                    <p>{{ $customAttributes[$field->name] }}</p>
                </div>
                @endif
            @endforeach
            <div class="mb-4">
                <label class="font-bold text-gray-700">Código QR:</label>
                <div class="mt-2">
                    {!! QrCode::size(150)->generate(route('assets.show', $asset->id)) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="mt-6">
        <label class="font-bold text-gray-700">Especificaciones:</label>
        <p class="bg-gray-100 p-4 rounded mt-2">{{ $asset->specifications ?? 'No se proporcionaron especificaciones' }}</p>
    </div>

    @if($asset->image)
    <div class="mt-6 mb-6">
        <label class="font-bold text-gray-700">Imagen del Activo:</label>
        <div class="mt-2">
            <img src="{{ asset('storage/' . $asset->image) }}" alt="{{ $asset->name }}" class="max-w-md rounded-lg shadow-lg">
        </div>
    </div>
    @endif

    <!-- Sección de Asignación -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-t-4 border-gray-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-user-check mr-2"></i>Gestión de Asignación
            </h3>
            
            
            @if(Auth::user()->hasPermission('create_movements'))
                @if($asset->isAssigned())
                    <button onclick="document.getElementById('returnModal').classList.remove('hidden')" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 px-4 rounded shadow border border-yellow-500">
                        <i class="fas fa-undo mr-2"></i>Devolver Activo
                    </button>
                @elseif($asset->status == 'active')
                    <a href="{{ route('assets.assign', $asset->id) }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow">
                        <i class="fas fa-user-plus mr-2"></i>Asignar a Empleado
                    </a>
                @else
                    <span class="bg-gray-200 text-gray-600 py-2 px-4 rounded font-semibold cursor-not-allowed" title="El activo debe estar activo para ser asignado">
                        <i class="fas fa-ban mr-2"></i>No disponible ({{ ucfirst($asset->status) }})
                    </span>
                @endif
            @endif
        </div>

        @if($asset->isAssigned())
            @php $assignment = $asset->currentAssignment; @endphp
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-gray-200 rounded-full p-2">
                            <i class="fas fa-user text-gray-800 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-lg font-bold text-blue-900">{{ $assignment->employee->full_name }}</h4>
                        <p class="text-blue-700">{{ $assignment->employee->position ?? 'Sin cargo' }} - {{ $assignment->employee->department ?? 'Sin departamento' }}</p>
                        
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Fecha Asignación:</span>
                                <span class="font-semibold text-gray-800">{{ $assignment->assigned_date->format('d/m/Y') }}</span>
                            </div>
                            @if($assignment->expected_return_date)
                            <div>
                                <span class="text-gray-500">Retorno Esperado:</span>
                                <span class="font-semibold {{ $assignment->expected_return_date->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ $assignment->expected_return_date->format('d/m/Y') }}
                                </span>
                            </div>
                            @endif
                        </div>

                        @if($assignment->notes)
                            <div class="mt-3 bg-white p-3 rounded border border-blue-100 text-sm text-gray-600">
                                <i class="fas fa-sticky-note mr-1 text-blue-400"></i> {{ $assignment->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal de Devolución -->
            <div id="returnModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
                <div class="relative p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Devolver Activo</h3>
                            <button onclick="document.getElementById('returnModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form action="{{ route('assignments.return', $assignment->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Devolución</label>
                                <input type="date" name="return_date" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notas de Devolución</label>
                                <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500" placeholder="Estado del activo al devolver..."></textarea>
                            </div>
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancelar
                                </button>
                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 px-4 rounded border border-yellow-500">
                                    Confirmar Devolución
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg text-center">
                <div class="text-gray-400 mb-2">
                    <i class="fas fa-box-open text-4xl"></i>
                </div>
                <p class="text-gray-600 font-medium">Este activo no está asignado a ningún empleado.</p>
                @if($asset->status == 'active')
                    <p class="text-sm text-gray-500 mt-1">Está disponible para ser asignado.</p>
                @else
                    <p class="text-sm text-red-500 mt-1">No se puede asignar porque su estado es: {{ ucfirst($asset->status) }}</p>
                @endif
            </div>
        @endif
    </div>

    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Historial de Mantenimiento</h3>
        @if($asset->maintenances->count() > 0)
            <table class="min-w-full w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Fecha</th>
                        <th class="py-3 px-6 text-left">Descripción</th>
                        <th class="py-3 px-6 text-center">Costo</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($asset->maintenances as $maintenance)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $maintenance->date->format('Y-m-d') }}</td>
                        <td class="py-3 px-6 text-left">{{ $maintenance->description }}</td>
                        <td class="py-3 px-6 text-center">${{ number_format($maintenance->cost, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 italic">No se encontraron registros de mantenimiento.</p>
        @endif
    </div>

    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Historial de Movimientos</h3>
        @if($asset->movements->count() > 0)
            <table class="min-w-full w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">De</th>
                        <th class="py-3 px-6 text-center"><i class="fas fa-arrow-right"></i></th>
                        <th class="py-3 px-6 text-left">A</th>
                        <th class="py-3 px-6 text-left">Usuario</th>
                        <th class="py-3 px-6 text-left">Fecha</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($asset->movements as $movement)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                                {{ $movement->fromLocation->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <i class="fas fa-arrow-right text-gray-800"></i>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                {{ $movement->toLocation->name }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-left">{{ $movement->user->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $movement->moved_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 italic">No se encontraron movimientos.</p>
        @endif
    </div>
</div>
@endsection
