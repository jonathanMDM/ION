@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Agregar Registro de Mantenimiento</h2>
    
    <form action="{{ route('maintenances.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="asset_id">Activo</label>
            <select name="asset_id" id="asset_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Buscar por ID, Nombre o Ubicación...</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" 
                            data-custom-id="{{ $asset->custom_id }}"
                            data-location="{{ $asset->location ? $asset->location->name : 'Sin ubicación' }}">
                        {{ $asset->custom_id }} - {{ $asset->name }} ({{ $asset->location ? $asset->location->name : 'Sin ubicación' }})
                    </option>
                @endforeach
            </select>
            <p class="text-gray-500 text-xs mt-1">Escribe para buscar por ID, nombre o ubicación</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Fecha</label>
            <input type="date" name="date" id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="cost">Costo</label>
            <input type="number" step="0.01" name="cost" id="cost" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Descripción</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Guardar Registro
            </button>
            <a href="{{ route('maintenances.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#asset_id').select2({
        placeholder: 'Buscar por ID, Nombre o Ubicación...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron resultados";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
});
</script>

<style>
/* Personalizar Select2 para que coincida con el diseño */
.select2-container--default .select2-selection--single {
    height: 42px;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    padding-left: 12px;
    color: #374151;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
}

.select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #1f2937;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    padding: 8px;
}
</style>
@endsection
