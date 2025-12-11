@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Agregar Nueva Ubicación</h2>
    
    <form action="{{ route('locations.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre de la Ubicación</label>
            <input type="text" name="name" id="name" placeholder="Ej: Oficina Principal, Almacén General, Sala de Servidores..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <p class="text-gray-500 text-xs mt-1">💡 Ejemplos: Oficina Principal, Almacén General, Sala de Servidores, Recepción, Departamento de IT</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Dirección</label>
            <input type="text" name="address" id="address" placeholder="Ej: Piso 2, Edificio A, Calle 123 #45-67..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-500 text-xs mt-1">💡 Opcional: Agrega detalles como piso, edificio, o dirección completa</p>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Guardar Ubicación
            </button>
            <a href="{{ route('locations.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
