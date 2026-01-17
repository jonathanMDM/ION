@extends('layouts.app')

@section('page-title', 'Crear Centro de Costo')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Nuevo Centro de Costo</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Defina una unidad contable para el seguimiento financiero de sus activos.</p>
        </div>
        <a href="{{ route('cost-centers.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>

    <form action="{{ route('cost-centers.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Side: Identification & Basic Info -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-file-invoice-dollar text-indigo-500 mr-3"></i>
                        <h3 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-widest">Información Contable</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="code">
                                    Código del Centro <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-mono text-sm" 
                                    placeholder="Ej: CC-001" required>
                                @error('code') <p class="text-red-500 text-[10px] mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="name">
                                    Nombre Identificador <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-bold" 
                                    placeholder="Ej: Departamento de Ventas" required>
                                @error('name') <p class="text-red-500 text-[10px] mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="description">
                                Descripción / Propósito
                            </label>
                            <textarea name="description" id="description" rows="3" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all" 
                                placeholder="Describa el alcance de este centro de costo...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Budget & Manager -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-coins text-amber-500 mr-2"></i>
                        <h3 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Presupuesto y Gestión</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="budget">Presupuesto Asignado</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold">$</span>
                                </div>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 pl-8 pr-4 text-sm font-black text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all" 
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" for="manager_id">Responsable Directo</label>
                            <select name="manager_id" id="manager_id" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">Sin responsable asignado</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }} ({{ ucfirst($manager->role) }})
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
                class="w-full md:w-auto px-16 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-indigo-500/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm">
                <i class="fas fa-save mr-2 text-lg"></i> Crear Centro de Costo
            </button>
        </div>
    </form>
</div>
@endsection
