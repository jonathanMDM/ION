@extends('layouts.app')

@section('page-title', 'Verificación 2FA')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <i class="fas fa-shield-alt text-gray-600 text-5xl mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Verificación de Dos Factores</h2>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Ingresa el código de tu aplicación de autenticación</p>
        </div>
        
        <form action="{{ route('two-factor.verify') }}" method="POST">
            @csrf
            <div class="mb-6">
                <input 
                    type="text" 
                    name="code" 
                    required 
                    placeholder="000000" 
                    maxlength="10"
                    autofocus
                    class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline text-center text-3xl font-mono"
                >
            </div>
            
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-3 px-4 rounded w-full mb-4">
                Verificar
            </button>
            
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    ¿Perdiste tu dispositivo? Usa un código de recuperación
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
