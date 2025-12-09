@extends('layouts.app')

@section('page-title', 'Sin Conexión')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
    <div class="bg-gray-100 p-8 rounded-full mb-6">
        <i class="fas fa-wifi text-6xl text-gray-400"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-800 mb-2">¡Ups! No tienes conexión</h2>
    <p class="text-gray-600 mb-6 max-w-md">
        Parece que has perdido la conexión a internet. Algunas funciones pueden no estar disponibles hasta que recuperes la señal.
    </p>
    <button onclick="window.location.reload()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded-lg transition duration-300">
        Intentar de nuevo
    </button>
</div>
@endsection
