@extends('layouts.app')

@section('page-title', 'Editar Activo')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Editar Activo</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Actualice la información del activo <strong>{{ $asset->name }}</strong>.</p>
        </div>
        <a href="{{ route('assets.show', $asset->id) }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <i class="fas fa-eye mr-2"></i> Ver activo
        </a>
    </div>
    
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 dark:text-red-400 font-bold">Por favor corrija los siguientes errores:</p>
                    <ul class="mt-1 text-xs text-red-600 dark:text-red-400 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    
    <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Basic Information & Categorization -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Section: General Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-info-circle text-indigo-500 mr-2"></i> Información General
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="name">
                                    Nombre del Activo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $asset->name) }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-semibold" required>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="custom_id">
                                    ID Único / Placa
                                </label>
                                <input type="text" value="{{ $asset->custom_id }}" 
                                    class="w-full bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-500 dark:text-gray-500 cursor-not-allowed font-mono" readonly>
                                <p class="text-[10px] text-gray-400 mt-1 italic">El ID único no puede ser modificado</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="model">
                                    Modelo / Referencia
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model', $asset->model) }}"
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="quantity">
                                    Cantidad en Stock <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', $asset->quantity) }}"
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-bold" required>
                            </div>

                            @if(auth()->user()->company && auth()->user()->company->low_stock_alerts_enabled)
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="minimum_quantity">
                                    Stock Mínimo (Alerta)
                                </label>
                                <input type="number" name="minimum_quantity" id="minimum_quantity" min="0" value="{{ old('minimum_quantity', $asset->minimum_quantity ?? 0) }}"
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Section: Localization & Status -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-map-marked-alt text-emerald-500 mr-2"></i> Ubicación y Clasificación
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="location_id">
                                    Ubicación Física <span class="text-red-500">*</span>
                                </label>
                                <select name="location_id" id="location_id" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" required>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $asset->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="subcategory_id">
                                    Categoría / Subcategoría <span class="text-red-500">*</span>
                                </label>
                                <select name="subcategory_id" id="subcategory_id" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" required>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $asset->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->category->name }} → {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="supplier_id">
                                    Proveedor
                                </label>
                                <select name="supplier_id" id="supplier_id" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="">Sin proveedor asignado</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $asset->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="status">
                                    Estado Actual <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-bold" required>
                                    <option value="active" {{ old('status', $asset->status) == 'active' ? 'selected' : '' }} class="text-green-600">Activo</option>
                                    <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }} class="text-yellow-600">En Taller / Mantenimiento</option>
                                    <option value="decommissioned" {{ old('status', $asset->status) == 'decommissioned' ? 'selected' : '' }} class="text-red-600">Dado de Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Financial Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-money-bill-wave text-blue-500 mr-2"></i> Gestión Financiera
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="purchase_price">
                                    Precio de Compra <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <span class="absolute left-4 top-3.5 text-gray-400 group-focus-within:text-indigo-500 transition-colors">$</span>
                                    <input type="number" step="0.01" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}"
                                        class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 pl-8 pr-4 text-gray-700 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 transition-all" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="purchase_date">
                                    Fecha de Adquisición
                                </label>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}"
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            @if(auth()->user()->company->hasModule('financial_control'))
                                <div class="md:col-span-2 space-y-6 mt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="cost_center_id">Centro de Costo</label>
                                            <select name="cost_center_id" id="cost_center_id" class="w-full bg-gray-100 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                                <option value="">Sin centro asignado</option>
                                                @php
                                                    $costCenters = \App\Models\CostCenter::where('company_id', Auth::user()->company_id)->where('is_active', true)->orderBy('code')->get();
                                                @endphp
                                                @foreach($costCenters as $center)
                                                    <option value="{{ $center->id }}" {{ old('cost_center_id', $asset->cost_center_id) == $center->id ? 'selected' : '' }}>
                                                        {{ $center->code }} - {{ $center->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="depreciation_method">Método Depreciación</label>
                                            <select name="depreciation_method" id="depreciation_method" class="w-full bg-gray-100 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" onchange="toggleDepreciationFields()">
                                                <option value="none" {{ old('depreciation_method', $asset->depreciation_method) == 'none' ? 'selected' : '' }}>Sin depreciar</option>
                                                <option value="straight_line" {{ old('depreciation_method', $asset->depreciation_method) == 'straight_line' ? 'selected' : '' }}>Línea Recta</option>
                                                <option value="declining_balance" {{ old('depreciation_method', $asset->depreciation_method) == 'declining_balance' ? 'selected' : '' }}>Saldos Decrecientes</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="depreciation_fields" class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl border border-indigo-100 dark:border-indigo-900/30" 
                                        style="display: {{ old('depreciation_method', $asset->depreciation_method) != 'none' ? 'grid' : 'none' }}">
                                        <div>
                                            <label class="block text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-2 uppercase">Vida Útil (Años)</label>
                                            <input type="number" name="useful_life_years" id="useful_life_years" min="1" value="{{ old('useful_life_years', $asset->useful_life_years) }}"
                                                class="w-full bg-white dark:bg-gray-900 border-indigo-100 dark:border-indigo-900/50 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-2 uppercase">V. Salvamento</label>
                                            <input type="number" step="0.01" name="salvage_value" id="salvage_value" value="{{ old('salvage_value', $asset->salvage_value) }}"
                                                class="w-full bg-white dark:bg-gray-900 border-indigo-100 dark:border-indigo-900/50 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-2 uppercase">Fecha Inicio</label>
                                            <input type="date" name="depreciation_start_date" id="depreciation_start_date" value="{{ old('depreciation_start_date', $asset->depreciation_start_date ? $asset->depreciation_start_date->format('Y-m-d') : '') }}"
                                                class="w-full bg-white dark:bg-gray-900 border-indigo-100 dark:border-indigo-900/50 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Section: Custom Fields -->
                @php
                    $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
                    $customAttributes = $asset->custom_attributes ?? [];
                @endphp
                @if($customFields->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-tags text-orange-500 mr-2"></i> Campos Adicionales
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($customFields as $field)
                            @if(\App\Helpers\FieldHelper::isVisible($field->name))
                            @php $fieldVal = $customAttributes[$field->name] ?? ''; @endphp
                            <div class="{{ $field->type === 'textarea' ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="custom_{{ $field->name }}">
                                    {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
                                </label>
                                @if($field->type === 'textarea')
                                    <textarea name="custom_attributes[{{ $field->name }}]" id="custom_{{ $field->name }}" rows="3" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">{{ $fieldVal }}</textarea>
                                @elseif($field->type === 'select')
                                    <select name="custom_attributes[{{ $field->name }}]" id="custom_{{ $field->name }}" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                        <option value="">Seleccione...</option>
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}" {{ $fieldVal == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="{{ $field->type }}" name="custom_attributes[{{ $field->name }}]" id="custom_{{ $field->name }}" value="{{ $fieldVal }}" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Multimedia -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-camera text-purple-500 mr-2"></i> Multimedia
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            @php
                                $hasImage = (bool)$asset->image;
                                $displayUrl = $hasImage ? (\Illuminate\Support\Str::startsWith($asset->image, 'http') ? $asset->image : asset('storage/' . $asset->image)) : '#';
                            @endphp
                            <div id="image-preview-container" class="w-full h-48 mb-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 overflow-hidden relative group">
                                @if(!$hasImage)
                                    <i class="fas fa-image text-4xl text-gray-300 mb-2 group-hover:scale-110 transition-all"></i>
                                    <span class="text-xs text-gray-400">Sin imagen</span>
                                @endif
                                <img id="image-preview" src="{{ $displayUrl }}" alt="Vista previa" class="{{ $hasImage ? '' : 'hidden' }} w-full h-full object-cover">
                                @if($hasImage)
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">Cambiar imagen</span>
                                    </div>
                                @endif
                            </div>
                            <label class="w-full flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl cursor-pointer transition-colors shadow-lg shadow-indigo-500/20">
                                <i class="fas fa-edit mr-2"></i> Subir Nueva Imagen
                                <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewFile(this)">
                            </label>
                            <p class="text-[10px] text-gray-400 mt-2 italic text-center">Deje vacío para mantener la imagen actual</p>
                        </div>
                    </div>
                </div>

                <!-- Specs -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-microchip text-teal-500 mr-2"></i> Especificaciones
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="municipality_plate">Placa Municipio</label>
                            <input type="text" name="municipality_plate" id="municipality_plate" value="{{ $asset->municipality_plate }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="specifications">Detalles Técnicos</label>
                            <textarea name="specifications" id="specifications" rows="4" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">{{ $asset->specifications }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Maintenance -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-calendar-check text-red-500 mr-2"></i> Mantenimiento
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="next_maintenance_date">Próxima Fecha</label>
                            <input type="date" name="next_maintenance_date" id="next_maintenance_date" value="{{ old('next_maintenance_date', $asset->next_maintenance_date ? $asset->next_maintenance_date->format('Y-m-d') : '') }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="maintenance_frequency_days">Frecuencia (En días)</label>
                            <input type="number" name="maintenance_frequency_days" id="maintenance_frequency_days" min="1" value="{{ old('maintenance_frequency_days', $asset->maintenance_frequency_days) }}"
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-end gap-4">
                <a href="{{ route('assets.show', $asset->id) }}" 
                    class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                    class="w-full md:w-auto px-16 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-indigo-500/25 transform hover:-translate-y-0.5 flex items-center justify-center tracking-wide uppercase text-sm">
                    <i class="fas fa-sync-alt mr-2 text-lg"></i> ACTUALIZAR DATOS
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Remove padding added for sticky footer
document.body.style.paddingBottom = "0px";

function toggleDepreciationFields() {
    const method = document.getElementById('depreciation_method').value;
    const fields = document.getElementById('depreciation_fields');
    if (method === 'none') {
        fields.style.display = 'none';
    } else {
        fields.style.display = 'grid';
    }
}

function previewFile(input) {
    const container = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    const icon = container.querySelector('i');
    const span = container.querySelector('span');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if(icon) icon.classList.add('hidden');
            if(span) span.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.body.style.paddingBottom = "80px";
    if (document.getElementById('depreciation_method')) toggleDepreciationFields();
});
</script>
@endpush
@endsection
