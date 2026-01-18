@extends('layouts.app')

@section('content')
<div id="tour-import-form" class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded shadow mb-6 transition-colors">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Importar Activos desde Excel</h2>
        
        <form action="{{ route('imports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <div class="mb-4">
                    <a href="{{ route('assets.import.template') }}" class="bg-blue-medium hover:bg-blue-dark text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>Descargar Plantilla Excel
                    </a>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Descarga la plantilla con todos los encabezados correctos (incluyendo campos personalizados).</p>
                </div>

                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="file">Archivo Excel</label>
                <input type="file" name="file" id="file" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required>
                <p class="text-gray-600 dark:text-gray-400 text-xs italic mt-2">Formatos soportados: .xlsx, .xls, .csv</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-upload mr-2"></i>Importar Activos
                </button>
                <a href="{{ route('assets.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Field Requirements -->
    <div class="bg-blue-50 dark:bg-blue-900/10 border-l-4 border-gray-500 dark:border-gray-600 p-4 md:p-6 rounded shadow mb-6 transition-colors">
        <h3 class="text-lg font-bold text-blue-900 dark:text-blue-300 mb-4">
            <i class="fas fa-info-circle mr-2"></i>Campos Requeridos
        </h3>
        
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded transition-colors">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Columnas Obligatorias:</h4>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-700 dark:text-gray-300">
                    <li><strong>name</strong> - Nombre del activo</li>
                    <li><strong>location_id</strong> - ID de la ubicación (número)</li>
                    <li><strong>subcategory_id</strong> - ID de la subcategoría (número)</li>
                    <li><strong>value</strong> - Valor del activo (número decimal)</li>
                    <li><strong>status</strong> - Estado: active, maintenance, o decommissioned</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded transition-colors">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Columnas Opcionales:</h4>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-700 dark:text-gray-300">
                    <li><strong>custom_id</strong> - ID único personalizado (se autogenera si está vacío)</li>
                    <li><strong>purchase_date</strong> - Fecha de compra (formato: YYYY-MM-DD)</li>
                    @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                    <li><strong>municipality_plate</strong> - Placa del municipio</li>
                    @endif
                    <li><strong>specifications</strong> - Especificaciones técnicas</li>
                    <li><strong>quantity</strong> - Cantidad (por defecto 1)</li>
                </ul>
            </div>

            @php
                $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
                $visibleCustomFields = $customFields->filter(function($field) {
                    return \App\Helpers\FieldHelper::isVisible($field->name);
                });
            @endphp

            @if($visibleCustomFields->count() > 0)
            <div class="bg-white dark:bg-gray-800 p-4 rounded transition-colors">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Campos Personalizados (Opcionales):</h4>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-700 dark:text-gray-300">
                    @foreach($visibleCustomFields as $field)
                    <li><strong>{{ $field->name }}</strong> - {{ $field->label }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Example Template -->
    <div class="bg-green-50 dark:bg-green-900/10 border-l-4 border-blue-medium/500 dark:border-blue-medium/600 p-4 md:p-6 rounded shadow transition-colors">
        <h3 class="text-lg font-bold text-green-900 dark:text-green-300 mb-4">
            <i class="fas fa-table mr-2"></i>Ejemplo de Formato
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-sm transition-colors">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-3 py-2 text-left">name</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">location</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">category</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">subcategory</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">value</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">status</th>
                        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                        <th class="border border-gray-300 px-3 py-2 text-left">municipality_plate</th>
                        @endif
                        @foreach($visibleCustomFields as $field)
                        <th class="border border-gray-300 px-3 py-2 text-left">{{ $field->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2">Laptop Dell</td>
                        <td class="border border-gray-300 px-3 py-2">Oficina Principal</td>
                        <td class="border border-gray-300 px-3 py-2">Equipos</td>
                        <td class="border border-gray-300 px-3 py-2">Laptops</td>
                        <td class="border border-gray-300 px-3 py-2">1500.00</td>
                        <td class="border border-gray-300 px-3 py-2">active</td>
                        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                        <td class="border border-gray-300 px-3 py-2">MUN-001</td>
                        @endif
                        @foreach($visibleCustomFields as $field)
                        <td class="border border-gray-300 px-3 py-2">Valor ejemplo</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded transition-colors">
            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Nota:</strong> Asegúrate de que los IDs de ubicación y subcategoría existan en el sistema antes de importar.
            </p>
        </div>
    </div>
</div>
@endsection
