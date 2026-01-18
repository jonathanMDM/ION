@extends('layouts.app')

@section('page-title', 'Agregar Categoría')

@section('content')
<div class="max-w-2xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Agregar Categoría</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Clasifique sus activos en grupos lógicos para un mejor control.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('categories.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="space-y-6">
                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="name">
                        Nombre de la Categoría <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-tags text-gray-400 group-focus-within:text-violet-500 transition-colors"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 pl-11 pr-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all font-medium" 
                            placeholder="Ej: Tecnología, Mobiliario..." required>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic flex items-center">
                        <i class="fas fa-info-circle mr-1 text-violet-400"></i> Ejemplos: Vehículos, Equipos de Oficina, Herramientas.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10 pt-8 border-t border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-center justify-end gap-4">
                <a href="{{ route('categories.index') }}" 
                    class="w-full md:w-auto text-center px-8 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                    class="w-full md:w-auto px-12 py-3 bg-blue-medium hover:bg-blue-dark text-white font-black rounded-xl transition-all shadow-xl shadow-blue-medium/20 transform hover:-translate-y-0.5 uppercase tracking-wide text-xs">
                    <i class="fas fa-save mr-2"></i> Guardar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
