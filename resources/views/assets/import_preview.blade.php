@extends('layouts.app')

@section('page-title', 'Vista Previa de Importación')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('assets.import') }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6 transition-colors">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Vista Previa de Importación</h2>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded transition-colors">
                <p class="text-sm text-gray-600 dark:text-gray-400">Total de Filas</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $total }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded transition-colors">
                <p class="text-sm text-gray-600 dark:text-gray-400">Válidas</p>
                <p class="text-2xl font-bold text-[#5483B3] dark:text-green-400">{{ $valid_count }}</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded transition-colors">
                <p class="text-sm text-gray-600 dark:text-gray-400">Con Errores</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $error_count }}</p>
            </div>
        </div>

        @if($error_count > 0)
        <!-- Errores -->
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-6 transition-colors">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Se encontraron {{ $error_count }} filas con errores</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                        <p>Revisa los errores a continuación y corrige el archivo antes de importar.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-3">Filas con Errores</h3>
            <div class="overflow-x-auto shadow-sm rounded-lg">
                <table class="min-w-full table-auto text-sm transition-colors">
                    <thead>
                        <tr class="bg-red-100 dark:bg-red-900/40 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <th class="py-2 px-4 text-left">Fila</th>
                            <th class="py-2 px-4 text-left">Nombre</th>
                            <th class="py-2 px-4 text-left">Errores</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($errors as $error)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $error['row_number'] }}</td>
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $error['data']['name'] ?? 'N/A' }}</td>
                            <td class="py-2 px-4">
                                <ul class="list-disc list-inside text-red-600 dark:text-red-400">
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
            <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-3">Datos Válidos ({{ $valid_count }} activos)</h3>
            <div class="overflow-x-auto shadow-sm rounded-lg">
                <table class="min-w-full table-auto text-sm transition-colors">
                    <thead>
                        <tr class="bg-green-100 dark:bg-green-900/40 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <th class="py-2 px-4 text-left">Fila</th>
                            <th class="py-2 px-4 text-left">ID</th>
                            <th class="py-2 px-4 text-left">Nombre</th>
                            <th class="py-2 px-4 text-left">Ubicación</th>
                            <th class="py-2 px-4 text-left">Categoría</th>
                            <th class="py-2 px-4 text-left">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($valid_rows as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $row['row_number'] }}</td>
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $row['data']['custom_id'] ?? '-' }}</td>
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $row['data']['name'] }}</td>
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $row['data']['location'] }}</td>
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">{{ $row['data']['category'] }} / {{ $row['data']['subcategory'] }}</td>
                            <td class="py-2 px-4 text-gray-800 dark:text-gray-300">${{ number_format($row['data']['value'], 2) }}</td>
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
                <button type="submit" class="bg-[#5483B3] hover:bg-[#052659] text-white font-bold py-2 px-6 rounded">
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
