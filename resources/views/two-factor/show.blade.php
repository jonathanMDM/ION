@extends('layouts.app')

@section('page-title', 'Autenticación de Dos Factores')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Autenticación de Dos Factores (2FA)</h2>
        
        @if(session('recovery_codes'))
            <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                <h3 class="font-bold text-yellow-800 dark:text-yellow-100 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Códigos de Recuperación
                </h3>
                <p class="text-yellow-700 dark:text-yellow-200 text-sm mb-3">
                    Guarda estos códigos en un lugar seguro. Puedes usarlos para acceder si pierdes tu dispositivo.
                </p>
                <div class="bg-white dark:bg-gray-700 p-3 rounded font-mono text-sm grid grid-cols-2 gap-2">
                    @foreach(session('recovery_codes') as $code)
                        <div class="text-gray-800 dark:text-gray-200">{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif
        
        @if($user->two_factor_enabled)
            <div class="bg-green-50 dark:bg-green-900 border border-blue-medium/200 dark:border-blue-medium/700 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-green-800 dark:text-green-100">2FA Activado</p>
                        <p class="text-sm text-blue-dark dark:text-green-200">Tu cuenta está protegida con autenticación de dos factores.</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('two-factor.disable') }}" method="POST" onsubmit="return confirm('¿Estás seguro de desactivar 2FA?')">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Confirma tu contraseña para desactivar
                    </label>
                    <input type="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i>Desactivar 2FA
                </button>
            </form>
        @else
            <div class="mb-6">
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    La autenticación de dos factores añade una capa extra de seguridad a tu cuenta. 
                    Necesitarás tu contraseña y un código de tu aplicación de autenticación para iniciar sesión.
                </p>
                
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-2">Paso 1: Escanea el código QR</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                        Usa Google Authenticator, Authy u otra app compatible para escanear este código:
                    </p>
                    <div class="flex justify-center mb-3">
                        {!! $qrCode !!}
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                        O ingresa manualmente: <code class="bg-white dark:bg-gray-600 px-2 py-1 rounded">{{ $secret }}</code>
                    </p>
                </div>
                
                <form action="{{ route('two-factor.enable') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Paso 2: Ingresa el código de verificación
                        </label>
                        <input type="text" name="code" required placeholder="000000" maxlength="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline text-center text-2xl font-mono">
                    </div>
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded w-full">
                        <i class="fas fa-shield-alt mr-2"></i>Activar 2FA
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
