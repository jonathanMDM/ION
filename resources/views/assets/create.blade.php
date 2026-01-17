@extends('layouts.app')

@section('page-title', 'Agregar Nuevo Activo')

@section('content')
<div id="tour-asset-form" class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-4 md:p-6 rounded shadow transition-colors">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Agregar Nuevo Activo</h2>
    
    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="custom_id">ID Único (Opcional)</label>
            <input type="text" name="custom_id" id="custom_id" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" placeholder="Dejar en blanco para autogenerar">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">Nombre del Activo</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="location_id">Ubicación</label>
            <select name="location_id" id="location_id" class="shadow border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="subcategory_id">Categoría / Subcategoría</label>
            <select name="subcategory_id" id="subcategory_id" class="shadow border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}">{{ $subcategory->category->name }} - {{ $subcategory->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="supplier_id">Proveedor</label>
            <select name="supplier_id" id="supplier_id" class="shadow border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors">
                <option value="">Sin proveedor</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="value">Valor</label>
            <input type="number" step="0.01" name="value" id="value" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required>
        </div>

            <div class="mb-4">
                <label for="model" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Modelo</label>
                <input type="text" name="model" id="model" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" value="{{ old('model') }}">
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Cantidad</label>
                <input type="number" name="quantity" id="quantity" min="1" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" value="{{ old('quantity', 1) }}" required>
            </div>

            @if(auth()->user()->company && auth()->user()->company->low_stock_alerts_enabled)
            <div class="mb-4">
                <label for="minimum_quantity" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                    Cantidad Mínima (Stock Bajo)
                    <span class="text-gray-500 font-normal text-sm">- Opcional</span>
                </label>
                <input type="number" name="minimum_quantity" id="minimum_quantity" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('minimum_quantity', 0) }}" placeholder="0 = Sin alerta">
                <p class="text-gray-600 text-xs italic mt-1">Recibirás una alerta cuando la cantidad sea igual o menor a este valor</p>
            </div>
            @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="purchase_date">Fecha de Compra</label>
            <input type="date" name="purchase_date" id="purchase_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Estado
                </label>
                <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                    <option value="decommissioned" {{ old('status') == 'decommissioned' ? 'selected' : '' }}>De Baja</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="next_maintenance_date">
                        Próximo Mantenimiento
                    </label>
                    <input type="date" name="next_maintenance_date" id="next_maintenance_date" value="{{ old('next_maintenance_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('next_maintenance_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="maintenance_frequency_days">
                        Frecuencia (Días)
                    </label>
                    <input type="number" name="maintenance_frequency_days" id="maintenance_frequency_days" value="{{ old('maintenance_frequency_days') }}" min="1" placeholder="Ej: 90" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('maintenance_frequency_days')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="municipality_plate">Placa Municipio</label>
            <input type="text" name="municipality_plate" id="municipality_plate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        @endif

        <!-- Custom Fields -->
        @php
            $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
        @endphp
        @foreach($customFields as $field)
            @if(\App\Helpers\FieldHelper::isVisible($field->name))
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="custom_{{ $field->name }}">
                    {{ $field->label }}
                    @if($field->is_required) <span class="text-red-500">*</span> @endif
                </label>
                
                @if($field->type === 'textarea')
                    <textarea 
                        name="custom_attributes[{{ $field->name }}]" 
                        id="custom_{{ $field->name }}" 
                        rows="3" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        {{ $field->is_required ? 'required' : '' }}
                    ></textarea>
                @elseif($field->type === 'select')
                    <select 
                        name="custom_attributes[{{ $field->name }}]" 
                        id="custom_{{ $field->name }}" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        {{ $field->is_required ? 'required' : '' }}
                    >
                        <option value="">Seleccione...</option>
                        @foreach($field->options as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @else
                    <input 
                        type="{{ $field->type }}" 
                        name="custom_attributes[{{ $field->name }}]" 
                        id="custom_{{ $field->name }}" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        {{ $field->is_required ? 'required' : '' }}
                    >
                @endif
            </div>
            @endif
        @endforeach

        {{-- Financial & Depreciation Section --}}
        <div class="border-t-2 border-gray-300 pt-6 mt-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-dollar-sign text-indigo-600 mr-2"></i>Información Financiera y Depreciación
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Precio de Compra -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="purchase_price">
                        Precio de Compra <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                        <input type="number" 
                               step="0.01" 
                               name="purchase_price" 
                               id="purchase_price" 
                               value="{{ old('purchase_price') }}"
                               class="shadow appearance-none border rounded w-full py-2 pl-8 pr-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               placeholder="0.00"
                               required>
                    </div>
                    <p class="text-gray-600 text-xs italic mt-1">Valor original de compra del activo</p>
                </div>

                <!-- Centro de Costo -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cost_center_id">
                        Centro de Costo
                    </label>
                    <select name="cost_center_id" 
                            id="cost_center_id" 
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sin asignar</option>
                        @php
                            $costCenters = \App\Models\CostCenter::where('company_id', Auth::user()->company_id)
                                ->where('is_active', true)
                                ->orderBy('code')
                                ->get();
                        @endphp
                        @foreach($costCenters as $center)
                            <option value="{{ $center->id }}" {{ old('cost_center_id') == $center->id ? 'selected' : '' }}>
                                {{ $center->code }} - {{ $center->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-600 text-xs italic mt-1">Asignar a un centro de costo para control presupuestario</p>
                </div>
            </div>

            <!-- Depreciation Settings -->
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200 mb-4">
                <h4 class="font-semibold text-indigo-900 mb-3">Configuración de Depreciación</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Método de Depreciación -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="depreciation_method">
                            Método de Depreciación
                        </label>
                        <select name="depreciation_method" 
                                id="depreciation_method" 
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                onchange="toggleDepreciationFields()">
                            <option value="none" {{ old('depreciation_method', 'none') == 'none' ? 'selected' : '' }}>
                                Sin depreciación
                            </option>
                            <option value="straight_line" {{ old('depreciation_method') == 'straight_line' ? 'selected' : '' }}>
                                Línea Recta (Depreciación constante)
                            </option>
                            <option value="declining_balance" {{ old('depreciation_method') == 'declining_balance' ? 'selected' : '' }}>
                                Saldo Decreciente (Mayor depreciación inicial)
                            </option>
                            <option value="units_of_production" {{ old('depreciation_method') == 'units_of_production' ? 'selected' : '' }}>
                                Unidades de Producción
                            </option>
                        </select>
                    </div>

                    <div id="depreciation_fields" style="display: {{ old('depreciation_method', 'none') != 'none' ? 'contents' : 'none' }}">
                        <!-- Vida Útil -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="useful_life_years">
                                Vida Útil (años)
                            </label>
                            <input type="number" 
                                   name="useful_life_years" 
                                   id="useful_life_years" 
                                   value="{{ old('useful_life_years') }}"
                                   min="1"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   placeholder="Ej: 5">
                            <p class="text-gray-600 text-xs italic mt-1">Años de vida útil estimada</p>
                        </div>

                        <!-- Valor de Salvamento -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="salvage_value">
                                Valor de Salvamento
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="salvage_value" 
                                       id="salvage_value" 
                                       value="{{ old('salvage_value', 0) }}"
                                       min="0"
                                       class="shadow appearance-none border rounded w-full py-2 pl-8 pr-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                       placeholder="0.00">
                            </div>
                            <p class="text-gray-600 text-xs italic mt-1">Valor residual al final de la vida útil</p>
                        </div>

                        <!-- Fecha de Inicio de Depreciación -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="depreciation_start_date">
                                Fecha de Inicio de Depreciación
                            </label>
                            <input type="date" 
                                   name="depreciation_start_date" 
                                   id="depreciation_start_date" 
                                   value="{{ old('depreciation_start_date') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-gray-600 text-xs italic mt-1">Fecha desde la cual comenzará a depreciarse</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="specifications">Especificaciones</label>
            <textarea name="specifications" id="specifications" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g., Processor: Intel i7, RAM: 16GB, Storage: 512GB SSD"></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Imagen del Activo</label>
            <input type="file" name="image" id="image" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-xs italic mt-1">Tamaño máx: 2MB. Formatos: JPG, PNG, GIF, WebP</p>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Guardar Activo
            </button>
            <a href="{{ route('assets.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleDepreciationFields() {
    const method = document.getElementById('depreciation_method').value;
    const fields = document.getElementById('depreciation_fields');
    
    if (method === 'none') {
        fields.style.display = 'none';
    } else {
        fields.style.display = 'contents';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDepreciationFields();
});
</script>
@endpush
@endsection
