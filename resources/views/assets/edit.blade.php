@extends('layouts.app')

@section('page-title', 'Editar Activo')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-4 md:p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Editar Activo</h2>
    
    <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="custom_id">ID Único</label>
            <input type="text" name="custom_id" id="custom_id" value="{{ $asset->custom_id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100" readonly>
            <p class="text-gray-600 text-xs italic mt-1">El ID único no puede ser modificado una vez creado el activo</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre del Activo</label>
            <input type="text" name="name" id="name" value="{{ $asset->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="location_id">Ubicación</label>
            <select name="location_id" id="location_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ $asset->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="subcategory_id">Categoría / Subcategoría</label>
            <select name="subcategory_id" id="subcategory_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ $asset->subcategory_id == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->category->name }} - {{ $subcategory->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="supplier_id">Proveedor</label>
            <select name="supplier_id" id="supplier_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Sin proveedor</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $asset->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="value">Valor</label>
            <input type="number" step="0.01" name="value" id="value" value="{{ $asset->value }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="model" class="block text-gray-700 font-bold mb-2">Modelo</label>
            <input type="text" name="model" id="model" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('model', $asset->model) }}">
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-bold mb-2">Cantidad</label>
            <input type="number" name="quantity" id="quantity" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('quantity', $asset->quantity) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="purchase_date">Fecha de Compra</label>
            <input type="date" name="purchase_date" id="purchase_date" value="{{ $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Estado
                </label>
                <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="active" {{ old('status', $asset->status) == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                    <option value="decommissioned" {{ old('status', $asset->status) == 'decommissioned' ? 'selected' : '' }}>De Baja</option>
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
                    <input type="date" name="next_maintenance_date" id="next_maintenance_date" value="{{ old('next_maintenance_date', $asset->next_maintenance_date ? $asset->next_maintenance_date->format('Y-m-d') : '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('next_maintenance_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="maintenance_frequency_days">
                        Frecuencia (Días)
                    </label>
                    <input type="number" name="maintenance_frequency_days" id="maintenance_frequency_days" value="{{ old('maintenance_frequency_days', $asset->maintenance_frequency_days) }}" min="1" placeholder="Ej: 90" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('maintenance_frequency_days')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="municipality_plate">Placa Municipio</label>
            <input type="text" name="municipality_plate" id="municipality_plate" value="{{ $asset->municipality_plate }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        @endif

        <!-- Custom Fields -->
        @php
            $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
            $customAttributes = $asset->custom_attributes ?? [];
        @endphp
        @foreach($customFields as $field)
            @if(\App\Helpers\FieldHelper::isVisible($field->name))
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="custom_{{ $field->name }}">
                    {{ $field->label }}
                    @if($field->is_required) <span class="text-red-500">*</span> @endif
                </label>
                
                @php
                    $value = $customAttributes[$field->name] ?? '';
                @endphp

                @if($field->type === 'textarea')
                    <textarea 
                        name="custom_attributes[{{ $field->name }}]" 
                        id="custom_{{ $field->name }}" 
                        rows="3" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        {{ $field->is_required ? 'required' : '' }}
                    >{{ $value }}</textarea>
                @elseif($field->type === 'select')
                    <select 
                        name="custom_attributes[{{ $field->name }}]" 
                        id="custom_{{ $field->name }}" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        {{ $field->is_required ? 'required' : '' }}
                    >
                        <option value="">Seleccione...</option>
                        @foreach($field->options as $option)
                            <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                @else
                    <input 
                        type="{{ $field->type }}" 
                        name="custom_attributes[{{ $field->name }}]" 
                        id="custom_{{ $field->name }}" 
                        value="{{ $value }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        {{ $field->is_required ? 'required' : '' }}
                    >
                @endif
            </div>
            @endif
        @endforeach

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="specifications">Especificaciones</label>
            <textarea name="specifications" id="specifications" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g., Processor: Intel i7, RAM: 16GB, Storage: 512GB SSD">{{ $asset->specifications }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Imagen del Activo</label>
            @if($asset->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $asset->image) }}" alt="Current image" class="w-32 h-32 object-cover rounded">
                    <p class="text-sm text-gray-600 mt-1">Imagen actual</p>
                </div>
            @endif
            <input type="file" name="image" id="image" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-xs italic mt-1">Tamaño máx: 2MB. Dejar vacío para mantener la imagen actual.</p>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Actualizar Activo
            </button>
            <a href="{{ route('assets.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
