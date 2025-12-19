@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-4 md:p-6 rounded shadow transition-colors">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Agregar Proveedor</h2>
    
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-4 transition-colors">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">Nombre del Proveedor *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="nit">NIT</label>
            <input type="text" name="nit" id="nit" value="{{ old('nit') }}" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" placeholder="Ej: 900123456-7">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="contact_name">Nombre de Contacto</label>
            <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="phone">Teléfono</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="address">Dirección</label>
            <textarea name="address" id="address" rows="3" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors">{{ old('address') }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <i class="fas fa-save mr-2"></i>Guardar Proveedor
            </button>
            <a href="{{ route('suppliers.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
