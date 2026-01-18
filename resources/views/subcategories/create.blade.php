@extends('layouts.app')

@section('page-title', 'Agregar Subcategoría')

@section('content')
<div class="max-w-2xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Agregar Subcategoría</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Defina especificaciones detalladas para sus categorías principales.</p>
        </div>
        <a href="{{ route('subcategories.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-[#5483B3] transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('subcategories.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="space-y-6">
                <!-- Category Select -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="category_id">
                        Pertenece a la Categoría <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-folder-open text-gray-400 group-focus-within:text-violet-500 transition-colors"></i>
                        </div>
                        <select name="category_id" id="category_id" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 pl-11 pr-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-violet-500 transition-all font-bold" required>
                            <option value="" disabled selected>Seleccione una categoría principal</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="name">
                        Nombre de la Subcategoría <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-gray-400 group-focus-within:text-violet-500 transition-colors"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 pl-11 pr-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-violet-500 transition-all font-medium" 
                            placeholder="Ej: Laptops, Escritorios, Servidores..." required>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic flex items-center">
                        <i class="fas fa-info-circle mr-1 text-violet-400"></i> Ejemplos: Periféricos, Vehículos Livianos, Herramientas Manuales.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10 pt-8 border-t border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-center justify-end gap-4">
                <a href="{{ route('subcategories.index') }}" 
                    class="w-full md:w-auto text-center px-8 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                    class="w-full md:w-auto px-12 py-3 bg-[#5483B3] hover:bg-[#052659] text-white font-black rounded-xl transition-all shadow-xl shadow-[#5483B3]/20 transform hover:-translate-y-0.5 uppercase tracking-wide text-xs">
                    <i class="fas fa-plus mr-2 text-xs"></i> Guardar Subcategoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
