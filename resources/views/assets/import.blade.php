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
        <div class="bg-blue-50 border-l-4 border-gray-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-gray-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Instrucciones</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Descarga la plantilla Excel haciendo clic en el botón de abajo</li>
                            <li>Llena la plantilla con los datos de tus activos</li>
                            <li>Asegúrate de que los nombres de ubicaciones, categorías y subcategorías coincidan exactamente con los registrados en el sistema</li>
                            <li>Sube el archivo completado usando el formulario</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de descarga de plantilla -->
        <div class="mb-6">
            <a href="{{ route('assets.import.template') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded">
                <i class="fas fa-download mr-2"></i>
                Descargar Plantilla Excel
            </a>
        </div>

        <!-- Campos requeridos -->
        <div class="bg-gray-50 p-4 rounded mb-6">
            <h3 class="font-bold text-gray-700 mb-3">Campos de la Plantilla</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="font-semibold text-red-600">* custom_id</span> - ID único (opcional)
                </div>
                <div>
                    <span class="font-semibold text-red-600">* name</span> - Nombre del activo
                </div>
                <div>
                    <span class="font-semibold">model</span> - Modelo (opcional)
                </div>
                <div>
                    <span class="font-semibold text-red-600">* location</span> - Nombre de ubicación
                </div>
                <div>
                    <span class="font-semibold text-red-600">* category</span> - Nombre de categoría
                </div>
                <div>
                    <span class="font-semibold text-red-600">* subcategory</span> - Nombre de subcategoría
                </div>
                <div>
                    <span class="font-semibold">supplier</span> - Nombre de proveedor (opcional)
                </div>
                <div>
                    <span class="font-semibold text-red-600">* value</span> - Valor numérico
                </div>
                <div>
                    <span class="font-semibold">quantity</span> - Cantidad (default: 1)
                </div>
                <div>
                    <span class="font-semibold">purchase_date</span> - Fecha (YYYY-MM-DD)
                </div>
                <div>
                    <span class="font-semibold">municipality_plate</span> - Placa municipio
                </div>
                <div>
                    <span class="font-semibold">specifications</span> - Especificaciones
                </div>
                <div>
                    <span class="font-semibold">status</span> - Estado (active/maintenance/decommissioned)
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-3">* Campos requeridos</p>
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
