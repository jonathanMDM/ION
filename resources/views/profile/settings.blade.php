@extends('layouts.app')

@section('page-title', 'Configuración de Cuenta')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Settings Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
            <p class="text-gray-600">Gestiona la seguridad y preferencias de tu cuenta.</p>
        </div>

        <!-- Security Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-indigo-600"></i> Seguridad
                </h3>
            </div>
            
            <div class="p-6">
                <!-- Two Factor Auth -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Autenticación de Dos Factores (2FA)</h4>
                    <p class="text-gray-600 text-sm mb-6">
                        Añade una capa extra de seguridad a tu cuenta requiriendo un código de tu celular al iniciar sesión.
                    </p>

                    @if(session('recovery_codes'))
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <h3 class="font-bold text-yellow-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Códigos de Recuperación
                            </h3>
                            <p class="text-yellow-700 text-sm mb-3">
                                Guarda estos códigos en un lugar seguro. Puedes usarlos para acceder si pierdes tu dispositivo.
                            </p>
                            <div class="bg-white p-3 rounded font-mono text-sm grid grid-cols-2 gap-2 border border-yellow-100">
                                @foreach(session('recovery_codes') as $code)
                                    <div class="text-gray-800">{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($user->two_factor_enabled)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-start">
                            <i class="fas fa-check-circle text-green-500 text-2xl mr-3 mt-1"></i>
                            <div>
                                <p class="font-bold text-green-800">2FA Activado</p>
                                <p class="text-sm text-green-700">Tu cuenta está protegida.</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('two-factor.disable') }}" method="POST" onsubmit="return confirm('¿Estás seguro de desactivar 2FA?')">
                            @csrf
                            @method('DELETE')
                            <!-- Hidden input to signal redirect back to settings -->
                            <input type="hidden" name="redirect_to" value="profile.settings">
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-3 font-medium">Zona de Peligro</p>
                                <div class="flex flex-col sm:flex-row gap-3 items-center">
                                    <input type="password" name="password" required placeholder="Confirma tu contraseña" class="flex-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="submit" class="bg-white border border-red-300 text-red-600 hover:bg-red-50 font-semibold py-2 px-4 rounded-lg transition-colors w-full sm:w-auto">
                                        Desactivar 2FA
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <h5 class="font-bold text-gray-800 mb-4">1. Escanea el código QR</h5>
                                <div class="flex justify-center bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-4">
                                    {!! $qrCode !!}
                                </div>
                                <p class="text-xs text-center text-gray-500">
                                    Código secreto: <span class="font-mono bg-gray-200 px-1 rounded">{{ $secret }}</span>
                                </p>
                            </div>
                            
                            <div>
                                <h5 class="font-bold text-gray-800 mb-4">2. Verifica el código</h5>
                                <p class="text-sm text-gray-600 mb-4">
                                    Ingresa el código de 6 dígitos que aparece en tu aplicación de autenticación (Google Authenticator, Authy, etc).
                                </p>
                                
                                <form action="{{ route('two-factor.enable') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="redirect_to" value="profile.settings">
                                    
                                    <div class="mb-4">
                                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Código de Verificación</label>
                                        <input type="text" name="code" id="code" required placeholder="000 000" maxlength="6" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-xl tracking-widest font-mono">
                                    </div>
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">
                                        Activar Autenticación
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Other Settings (Placeholder) -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class="fas fa-bell mr-2 text-indigo-600"></i> Preferencias
                </h3>
            </div>
            <div class="p-6 text-center text-gray-500 py-12">
                <p>Próximamente más configuraciones...</p>
            </div>
        </div>
    </div>
</div>
@endsection
