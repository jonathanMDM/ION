@extends('layouts.app')

@section('page-title', 'Registrar Mantenimiento')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Registro de Mantenimiento</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Documente intervenciones técnicas para prolongar la vida útil de sus activos.</p>
        </div>
        <a href="{{ route('maintenances.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>

    <form action="{{ route('maintenances.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Side: Asset & Description -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-tools text-indigo-500 mr-3"></i>
                        <h3 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-widest">Detalles del Servicio</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <!-- Asset Search (Select2) -->
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3" for="asset_id">Activo Intervenido <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <select name="asset_id" id="asset_id" required 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-4 px-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                                    <option value=""></option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}" 
                                                data-custom-id="{{ $asset->custom_id }}"
                                                data-location="{{ $asset->location ? $asset->location->name : 'Sin ubicación' }}">
                                            {{ $asset->custom_id }} — {{ $asset->name }} ({{ $asset->location ? $asset->location->name : 'Sin ubicación' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('asset_id') <p class="text-red-500 text-[10px] mt-2 italic font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="description">Descripción del Trabajo Realizado <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="5" required 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-4 px-5 text-sm font-medium focus:ring-2 focus:ring-indigo-500 transition-all" 
                                placeholder="Describa detalladamente el mantenimiento realizado, repuestos cambiados, fallas detectadas, etc...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Logistics & Cost -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-file-invoice-dollar text-emerald-500 mr-2"></i>
                        <h3 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Datos Financieros</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Fecha del Servicio</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-xs font-black text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Inversión / Costo</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold">$</span>
                                </div>
                                <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost') }}" required 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 pl-8 pr-4 text-sm font-black text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all" 
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-3xl border border-indigo-100 dark:border-indigo-900/30">
                    <p class="text-[10px] text-indigo-700 dark:text-indigo-400 font-medium leading-relaxed italic">
                        <i class="fas fa-lightbulb mr-1"></i> El costo registrado impactará el valor contable y el ROI del activo automáticamente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
            <a href="{{ route('maintenances.index') }}" 
                class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all active:scale-95">
                Cancelar
            </a>
            <button type="submit" 
                class="w-full md:w-auto px-16 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-indigo-500/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm">
                <i class="fas fa-save mr-2 text-lg"></i> Registrar Mantenimiento
            </button>
        </div>
    </form>
</div>

<!-- Select2 Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#asset_id').select2({
        placeholder: 'Buscar por ID, Nombre o Ubicación...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "No se encontraron resultados"; },
            searching: function() { return "Buscando..."; }
        }
    });
});
</script>

<style>
/* Modernize Select2 with the premium theme */
.select2-container--default .select2-selection--single {
    height: 60px !important;
    background-color: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 1.25rem !important;
    display: flex;
    align-items: center;
}
.dark .select2-container--default .select2-selection--single {
    background-color: #111827 !important;
    border-color: #374151 !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #111827 !important;
    font-weight: 700 !important;
    padding-left: 1.25rem !important;
}
.dark .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #f9fafb !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 15px !important;
}
.select2-dropdown {
    border: 1px solid #e5e7eb !important;
    border-radius: 1rem !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    margin-top: 4px !important;
    overflow: hidden;
}
.dark .select2-dropdown {
    background-color: #1f2937 !important;
    border-color: #374151 !important;
}
.select2-results__option {
    padding: 10px 1.25rem !important;
    font-size: 0.875rem !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #4f46e5 !important;
}
.select2-search--dropdown .select2-search__field {
    border-radius: 0.75rem !important;
    padding: 8px 12px !important;
}
</style>
@endsection
