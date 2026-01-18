@extends('layouts.app')

@section('page-title', 'Agregar Proveedor')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Agregar Proveedor</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Registre un nuevo aliado comercial en su base de datos.</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-[#5483B3] transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
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
    
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Basic Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                    <i class="fas fa-building text-indigo-500 mr-2"></i>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Identificación Legal</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="name">
                            Razón Social / Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" 
                            placeholder="Ej: Suministros IT S.A.S" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="nit">
                            NIT / Identificación Fiscal
                        </label>
                        <input type="text" name="nit" id="nit" value="{{ old('nit') }}" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" 
                            placeholder="Ej: 900.123.456-7">
                    </div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                    <i class="fas fa-address-book text-emerald-500 mr-2"></i>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Información de Contacto</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="contact_name">
                            Representante / Contacto
                        </label>
                        <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all" 
                            placeholder="Nombre de la persona encargada">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="email">
                                Correo
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all" 
                                placeholder="ventas@proveedor.com">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="phone">
                                Teléfono
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all" 
                                placeholder="+57 300...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width Address -->
            <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" for="address">
                        <i class="fas fa-map-marker-alt text-red-400 mr-2"></i> Dirección de Sede
                    </label>
                    <textarea name="address" id="address" rows="3" 
                        class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-medium" 
                        placeholder="Dirección completa, ciudad y departamento.">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
            <a href="{{ route('suppliers.index') }}" 
                class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all active:scale-95">
                Cancelar
            </a>
            <button type="submit" 
                class="w-full md:w-auto px-16 py-4 bg-[#5483B3] hover:bg-[#052659] text-white font-black rounded-2xl transition-all shadow-xl shadow-[#5483B3]/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm">
                <i class="fas fa-save mr-2 text-lg"></i> Registrar Proveedor
            </button>
        </div>
    </form>
</div>
@endsection
