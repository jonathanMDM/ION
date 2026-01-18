@extends('layouts.app')

@section('page-title', 'Editar Centro de Costo')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Editar Centro de Costo</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Actualice los valores para: <strong>{{ $costCenter->name }}</strong>.</p>
        </div>
        <a href="{{ route('cost-centers.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
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

    <form action="{{ route('cost-centers.update', $costCenter) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Side -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-edit text-indigo-500 mr-3"></i>
                            <h3 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-widest">Ajustes Generales</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $costCenter->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-medium"></div>
                            <span class="ml-3 text-[10px] font-black uppercase text-gray-500 tracking-wider">Activo</span>
                        </label>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="code">Código</label>
                                <input type="text" name="code" id="code" value="{{ old('code', $costCenter->code) }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-medium transition-all font-mono text-sm" required>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="name">Nombre</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $costCenter->name) }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-medium transition-all font-bold" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="description">Descripción</label>
                            <textarea name="description" id="description" rows="3" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-medium transition-all">{{ old('description', $costCenter->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-coins text-amber-500 mr-2"></i>
                        <h3 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Presupuesto</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="budget">Monto Asignado</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold">$</span>
                                </div>
                                <input type="number" name="budget" id="budget" value="{{ old('budget', $costCenter->budget) }}" step="0.01" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 pl-8 pr-4 text-sm font-black text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="manager_id">Responsable</label>
                            <select name="manager_id" id="manager_id" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-xs font-bold focus:ring-2 focus:ring-blue-medium transition-all">
                                <option value="">Sin responsable</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id', $costCenter->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
            <a href="{{ route('cost-centers.index') }}" 
                class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all active:scale-95">
                Cancelar
            </a>
            <button type="submit" 
                class="w-full md:w-auto px-16 py-4 text-white font-black rounded-2xl transition-all shadow-xl shadow-blue-medium/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm border-none cursor-pointer" style="background: linear-gradient(135deg, #5483B3 0%, #052659 100%); box-shadow: 0 10px 20px rgba(84, 131, 179, 0.3);">
                <i class="fas fa-sync-alt mr-2 text-lg"></i> Actualizar Centro de Costo
            </button>
        </div>
    </form>
</div>
@endsection
