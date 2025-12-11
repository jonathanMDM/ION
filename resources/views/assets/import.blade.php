@extends('layouts.app')

@section('page-title', 'Importar Activos')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('assets.index') }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Activos
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Importación Masiva de Activos</h2>

        <!-- Instrucciones -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-blue-900">📋 Instrucciones de Importación</h3>
                    <div class="mt-2 text-sm text-blue-800 space-y-2">
                        <ol class="list-decimal list-inside space-y-2">
                            <li><strong>Descarga la plantilla</strong> haciendo clic en el botón verde de abajo</li>
                            <li><strong>Abre el archivo</strong> con Excel (se abrirá automáticamente)</li>
                            <li><strong>Llena los datos</strong> de tus activos (puedes borrar las filas de ejemplo)</li>
                            <li><strong>Guarda el archivo</strong> (Ctrl+S o Cmd+S)</li>
                            <li><strong>Sube el archivo</strong> usando el formulario de abajo</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tip sobre cantidades -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-yellow-900">💡 Importar Múltiples Unidades del Mismo Activo</h3>
                    <div class="mt-2 text-sm text-yellow-800">
                        <p class="mb-2"><strong>Opción 1 - Campo Cantidad:</strong></p>
                        <p class="mb-3">Usa el campo "Cantidad" para especificar cuántas unidades del mismo activo tienes. Por ejemplo:</p>
                        <div class="bg-white p-2 rounded text-xs font-mono mb-3">
                            ACT-001, Silla Ejecutiva, ..., <strong class="text-red-600">10</strong>, ...
                        </div>
                        <p class="mb-2"><strong>Opción 2 - Filas Separadas:</strong></p>
                        <p>Si necesitas IDs únicos para cada unidad, crea una fila por cada activo:</p>
                        <div class="bg-white p-2 rounded text-xs font-mono">
                            ACT-001, Silla Ejecutiva, ..., 1, ...<br>
                            ACT-002, Silla Ejecutiva, ..., 1, ...<br>
                            ACT-003, Silla Ejecutiva, ..., 1, ...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de descarga de plantilla -->
        <div class="mb-6 text-center">
            <a href="{{ route('assets.import.template') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                <i class="fas fa-download mr-2"></i>
                📥 Descargar Plantilla
            </a>
            <p class="text-xs text-gray-500 mt-2">Archivo TXT que Excel abre con columnas separadas - Incluye 3 ejemplos</p>
            <p class="text-xs text-blue-600 mt-1">💡 Doble clic en el archivo descargado y Excel lo abrirá automáticamente</p>
        </div>

        <!-- Campos de la plantilla -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-table mr-2"></i>
                Campos de la Plantilla
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="flex items-start">
                    <span class="text-red-600 mr-1">*</span>
                    <div>
                        <span class="font-semibold text-gray-800">ID Único:</span>
                        <span class="text-gray-600"> Código único del activo (Ej: ACT-001)</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-red-600 mr-1">*</span>
                    <div>
                        <span class="font-semibold text-gray-800">Nombre:</span>
                        <span class="text-gray-600"> Nombre del activo</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Especificaciones:</span>
                        <span class="text-gray-600"> Detalles técnicos</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Cantidad:</span>
                        <span class="text-gray-600"> Número de unidades (default: 1)</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-red-600 mr-1">*</span>
                    <div>
                        <span class="font-semibold text-gray-800">Valor:</span>
                        <span class="text-gray-600"> Precio en pesos (Ej: 1250000)</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Fecha de Compra:</span>
                        <span class="text-gray-600"> Formato: AAAA-MM-DD</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Estado:</span>
                        <span class="text-gray-600"> active, maintenance o decommissioned</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-red-600 mr-1">*</span>
                    <div>
                        <span class="font-semibold text-gray-800">Ubicación:</span>
                        <span class="text-gray-600"> Nombre de la ubicación</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-red-600 mr-1">*</span>
                    <div>
                        <span class="font-semibold text-gray-800">Categoría:</span>
                        <span class="text-gray-600"> Nombre de la categoría</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-red-600 mr-1">*</span>
                    <div>
                        <span class="font-semibold text-gray-800">Subcategoría:</span>
                        <span class="text-gray-600"> Nombre de la subcategoría</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Proveedor:</span>
                        <span class="text-gray-600"> Nombre del proveedor</span>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Placa Municipio:</span>
                        <span class="text-gray-600"> Para vehículos</span>
                    </div>
                </div>
                <div class="flex items-start col-span-2">
                    <span class="text-gray-400 mr-1">○</span>
                    <div>
                        <span class="font-semibold text-gray-800">Notas:</span>
                        <span class="text-gray-600"> Información adicional</span>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-3 flex items-center">
                <span class="text-red-600 mr-1">*</span> Campos obligatorios
                <span class="text-gray-400 ml-3 mr-1">○</span> Campos opcionales
            </p>
        </div>

        <!-- Formulario de carga -->
        <form action="{{ route('assets.import.preview') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Archivo Excel/CSV
                </label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                    class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-gray-200">
                <p class="text-xs text-gray-500 mt-1">Formatos aceptados: .xlsx, .xls, .csv (máx. 10MB)</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-eye mr-2"></i>Vista Previa
                </button>
                <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
