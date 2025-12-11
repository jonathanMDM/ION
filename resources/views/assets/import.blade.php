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
                            <li><strong>Abre el archivo</strong> con Excel o Google Sheets</li>
                            <li><strong>Llena los datos</strong> de tus activos (puedes borrar las filas de ejemplo)</li>
                            <li><strong>Guarda como Excel</strong> (.xlsx) - Archivo → Guardar como → Excel</li>
                            <li><strong>Sube el archivo Excel</strong> usando el formulario de abajo</li>
                        </ol>
                        <p class="text-xs bg-green-100 p-2 rounded mt-2">
                            ✅ <strong>Importante:</strong> Guarda el archivo como <strong>Excel (.xlsx)</strong> para evitar problemas con delimitadores
                        </p>
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

        <!-- Opciones de descarga de plantilla -->
        <div class="mb-6">
            <!-- Opción 1: Google Sheets (Recomendado) -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-500 rounded-lg p-6 mb-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-green-900 mb-2">
                            ⭐ Opción Recomendada: Google Sheets
                        </h3>
                        <p class="text-sm text-green-800 mb-4">
                            La forma más fácil - Edita directamente en tu navegador, sin descargar nada. Las columnas ya están separadas y listas para usar.
                        </p>
                        <div class="space-y-3">
                            @if(env('GOOGLE_SHEETS_TEMPLATE_URL'))
                                <a href="{{ env('GOOGLE_SHEETS_TEMPLATE_URL') }}" target="_blank" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                                    <i class="fab fa-google mr-2"></i>
                                    📊 Abrir Plantilla en Google Sheets
                                </a>
                            @else
                                <div class="bg-yellow-100 border border-yellow-400 rounded p-3 text-sm text-yellow-800">
                                    <p class="font-semibold mb-2">🔧 Configuración Pendiente</p>
                                    <p class="mb-2">Para habilitar esta opción, crea una plantilla de Google Sheets con estos encabezados:</p>
                                    <div class="bg-white p-2 rounded text-xs font-mono mb-2">
                                        ID Unico | Nombre | Especificaciones | Cantidad | Valor | Fecha de Compra | Estado | Ubicacion | Categoria | Subcategoria | Proveedor | Placa Municipio | Notas
                                    </div>
                                    <p class="text-xs">Luego configura la variable de entorno <code class="bg-yellow-200 px-1 rounded">GOOGLE_SHEETS_TEMPLATE_URL</code> con el enlace de tu plantilla.</p>
                                </div>
                            @endif
                            <div class="text-xs text-green-700 mt-2">
                                <p class="font-semibold mb-1">📝 Pasos:</p>
                                <ol class="list-decimal list-inside space-y-1 ml-2">
                                    <li>Haz clic en el botón verde</li>
                                    <li>Se abrirá Google Sheets con la plantilla</li>
                                    <li>Haz clic en "Hacer una copia" (se guardará en tu Google Drive)</li>
                                    <li>Edita los datos directamente en el navegador</li>
                                    <li>Descarga como Excel: Archivo → Descargar → Microsoft Excel (.xlsx)</li>
                                    <li>Sube el archivo descargado aquí</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opción 2: Descarga directa -->
            <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-download text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-base font-bold text-gray-900 mb-2">
                            Opción 2: Descargar Plantilla
                        </h3>
                        <p class="text-sm text-gray-700 mb-3">
                            Si prefieres trabajar offline, descarga la plantilla y ábrela con Excel.
                        </p>
                        <a href="{{ route('assets.import.template') }}" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Descargar Plantilla
                        </a>
                        <p class="text-xs text-gray-500 mt-2">
                            ⚠️ Nota: Al abrir en Excel, asegúrate de que las columnas estén separadas correctamente
                        </p>
                    </div>
                </div>
            </div>
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
