@extends('layouts.app')

@section('page-title', 'Registrar Costo')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Registrar Costo</h2>
            <p class="text-gray-600 mt-1">Activo: <span class="font-semibold">{{ $asset->name }}</span> ({{ $asset->custom_id }})</p>
        </div>
        <a href="{{ route('assets.show', $asset) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('assets.costs.store', $asset) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tipo de Costo -->
            <div>
                <label for="cost_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Costo <span class="text-red-500">*</span>
                </label>
                <select name="cost_type" 
                        id="cost_type" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('cost_type') border-red-500 @enderror"
                        required>
                    <option value="">Seleccionar tipo</option>
                    <option value="maintenance" {{ old('cost_type') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="repair" {{ old('cost_type') == 'repair' ? 'selected' : '' }}>Reparación</option>
                    <option value="insurance" {{ old('cost_type') == 'insurance' ? 'selected' : '' }}>Seguro</option>
                    <option value="spare_parts" {{ old('cost_type') == 'spare_parts' ? 'selected' : '' }}>Repuestos</option>
                    <option value="upgrade" {{ old('cost_type') == 'upgrade' ? 'selected' : '' }}>Mejora/Actualización</option>
                    <option value="other" {{ old('cost_type') == 'other' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('cost_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Monto -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                    Monto <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                    <input type="number" 
                           name="amount" 
                           id="amount" 
                           value="{{ old('amount') }}"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('amount') border-red-500 @enderror"
                           placeholder="0.00"
                           required>
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="date" 
                       id="date" 
                       value="{{ old('date', date('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('date') border-red-500 @enderror"
                       required>
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Número de Factura -->
            <div>
                <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Factura
                </label>
                <input type="text" 
                       name="invoice_number" 
                       id="invoice_number" 
                       value="{{ old('invoice_number') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('invoice_number') border-red-500 @enderror"
                       placeholder="Ej: F-12345">
                @error('invoice_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Proveedor -->
            <div>
                <label for="vendor" class="block text-sm font-medium text-gray-700 mb-2">
                    Proveedor
                </label>
                <input type="text" 
                       name="vendor" 
                       id="vendor" 
                       value="{{ old('vendor') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('vendor') border-red-500 @enderror"
                       placeholder="Nombre del proveedor">
                @error('vendor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documento -->
            <div>
                <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                    Documento/Comprobante
                </label>
                <input type="file" 
                       name="document" 
                       id="document" 
                       accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('document') border-red-500 @enderror">
                @error('document')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">PDF, JPG, JPEG o PNG. Máximo 5MB</p>
            </div>
        </div>

        <!-- Descripción -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Descripción <span class="text-red-500">*</span>
            </label>
            <textarea name="description" 
                      id="description" 
                      rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5483B3] focus:border-transparent @error('description') border-red-500 @enderror"
                      placeholder="Describe el costo o servicio realizado..."
                      required>{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('assets.show', $asset) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-[#5483B3] hover:bg-[#052659] text-white rounded-lg font-medium">
                <i class="fas fa-save mr-2"></i>Registrar Costo
            </button>
        </div>
    </form>
</div>
@endsection
