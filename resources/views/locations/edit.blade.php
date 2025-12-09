@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-4 md:p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Editar Ubicación</h2>
    
    <form action="{{ route('locations.update', $location->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre de la Ubicación</label>
            <input type="text" name="name" id="name" value="{{ $location->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Dirección</label>
            <input type="text" name="address" id="address" value="{{ $location->address }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Actualizar Ubicación
            </button>
            <a href="{{ route('locations.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
