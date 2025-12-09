@extends('layouts.app')

@section('page-title', 'Vista Previa de Importación')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('assets.import') }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Vista Previa de Importación</h2>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded">
                <p class="text-sm text-gray-600">Total de Filas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded">
                <p class="text-sm text-gray-600">Válidas</p>
                <p class="text-2xl font-bold text-green-600">{{ $valid_count }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded">
                <p class="text-sm text-gray-600">Con Errores</p>
                <p class="text-2xl font-bold text-red-600">{{ $error_count }}</p>
            </div>
        </div>

        @if($error_count > 0)
        <!-- Errores -->
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Se encontraron {{ $error_count }} filas con errores</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>Revisa los errores a continuación y corrige el archivo antes de importar.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold text-gray-700 mb-3">Filas con Errores</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm">
                    <thead>
                        <tr class="bg-red-100 text-gray-600 uppercase text-xs">
                            <th class="py-2 px-4 text-left">Fila</th>
                            <th class="py-2 px-4 text-left">Nombre</th>
                            <th class="py-2 px-4 text-left">Errores</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($errors as $error)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $error['row_number'] }}</td>
                            <td class="py-2 px-4">{{ $error['data']['name'] ?? 'N/A' }}</td>
                            <td class="py-2 px-4">
                                <ul class="list-disc list-inside text-red-600">
                                    @foreach($error['errors'] as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($valid_count > 0)
        <!-- Datos válidos -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-700 mb-3">Datos Válidos ({{ $valid_count }} activos)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm">
                    <thead>
                        <tr class="bg-green-100 text-gray-600 uppercase text-xs">
                            <th class="py-2 px-4 text-left">Fila</th>
                            <th class="py-2 px-4 text-left">ID</th>
                            <th class="py-2 px-4 text-left">Nombre</th>
                            <th class="py-2 px-4 text-left">Ubicación</th>
                            <th class="py-2 px-4 text-left">Categoría</th>
                            <th class="py-2 px-4 text-left">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($valid_rows as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">{{ $row['row_number'] }}</td>
                            <td class="py-2 px-4">{{ $row['data']['custom_id'] ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $row['data']['name'] }}</td>
                            <td class="py-2 px-4">{{ $row['data']['location'] }}</td>
                            <td class="py-2 px-4">{{ $row['data']['category'] }} / {{ $row['data']['subcategory'] }}</td>
                            <td class="py-2 px-4">${{ number_format($row['data']['value'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex gap-3">
            @if($error_count == 0)
            <form action="{{ route('assets.import.execute') }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-check mr-2"></i>Confirmar e Importar {{ $valid_count }} Activos
                </button>
            </form>
            @endif
            <a href="{{ route('assets.import') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
