@extends('layouts.superadmin')

@section('page-title', 'Gestión de Token API')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Token API</h2>
        <p class="text-gray-600 mt-2">Genera y administra tokens API para acceso programático a tus datos.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-[#5483B3]/500 text-[#052659] p-4 mb-6" role="alert">
        <p class="font-bold">¡Éxito!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('token'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p class="font-bold mb-2">Tu Token API</p>
        <div class="bg-white p-3 rounded font-mono text-sm break-all mb-2">
            {{ session('token') }}
        </div>
        <p class="text-sm">⚠️ <strong>Importante:</strong> Guarda este token de forma segura. No podrás verlo nuevamente.</p>
        <button onclick="copyToken('{{ session('token') }}')" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
            <i class="fas fa-copy mr-1"></i>Copiar Token
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">¡Error!</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Current Token Status -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Estado del Token Actual</h3>
        
        @if($hasToken)
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Tienes un token API activo
                    </p>
                    @if($expiresAt)
                        <p class="text-sm text-gray-600 mt-1">
                            Expira: {{ $expiresAt->format('d/m/Y g:i A') }}
                            @if($isExpired)
                                <span class="text-red-600 font-bold">(Expirado)</span>
                            @else
                                <span class="text-gray-500">({{ $expiresAt->diffForHumans() }})</span>
                            @endif
                        </p>
                    @else
                        <p class="text-sm text-gray-600 mt-1">Nunca expira</p>
                    @endif
                </div>
                <form action="{{ route('superadmin.api.token.revoke') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas revocar tu token API? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-trash mr-2"></i>Revocar Token
                    </button>
                </form>
            </div>
        @else
            <p class="text-gray-700">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                No tienes un token API activo
            </p>
        @endif
    </div>

    <!-- Generate New Token -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Generar Nuevo Token</h3>
        
        @if($hasToken && !$isExpired)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 mb-4">
                <p class="text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Generar un nuevo token revocará tu token actual. Solo puedes tener 1 token activo a la vez.
                </p>
            </div>
        @endif

        <form action="{{ route('superadmin.api.token.generate') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="token_name" class="block text-gray-700 text-sm font-bold mb-2">
                    Nombre del Token <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="token_name" 
                       id="token_name" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('token_name') border-red-500 @enderror" 
                       placeholder="ej., API de Producción, App Móvil, Monitor de Estado"
                       value="{{ old('token_name') }}"
                       required>
                @error('token_name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-600 text-xs mt-1">Un nombre descriptivo para ayudarte a identificar este token</p>
            </div>

            <div class="mb-6">
                <label for="expires_in_days" class="block text-gray-700 text-sm font-bold mb-2">
                    Expiración (Opcional)
                </label>
                <select name="expires_in_days" 
                        id="expires_in_days" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Nunca expira</option>
                    <option value="30">30 días</option>
                    <option value="60">60 días</option>
                    <option value="90" selected>90 días (Recomendado)</option>
                    <option value="180">180 días</option>
                    <option value="365">1 año</option>
                </select>
                <p class="text-gray-600 text-xs mt-1">Por seguridad, recomendamos establecer una fecha de expiración</p>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                <i class="fas fa-key mr-2"></i>Generar Token API
            </button>
        </form>
    </div>

    <!-- API Documentation -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Documentación de la API</h3>
        
        <div class="space-y-4">
            <div>
                <h4 class="font-bold text-gray-700 mb-2">URL Base</h4>
                <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ url('/api') }}</code>
            </div>

            <div>
                <h4 class="font-bold text-gray-700 mb-2">Autenticación</h4>
                <p class="text-gray-600 text-sm mb-2">Incluye tu token en el encabezado Authorization:</p>
                <code class="bg-gray-100 px-2 py-1 rounded text-sm block">Authorization: Bearer tu-token-api-aqui</code>
            </div>

            <div>
                <h4 class="font-bold text-gray-700 mb-2">Ejemplo de Solicitud</h4>
                <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto"><code>curl -H "Authorization: Bearer tu-token" \
  {{ url('/api/v1/assets') }}</code></pre>
            </div>

            <div>
                <h4 class="font-bold text-gray-700 mb-2">Límite de Solicitudes</h4>
                <p class="text-gray-600 text-sm">60 solicitudes por minuto por token</p>
            </div>

            <div>
                <a href="{{ asset('API_DOCUMENTATION.md') }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold" target="_blank">
                    <i class="fas fa-book mr-2"></i>Ver Documentación Completa de la API
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToken(token) {
    navigator.clipboard.writeText(token).then(function() {
        alert('¡Token copiado al portapapeles!');
    }, function() {
        alert('Error al copiar el token. Por favor, cópialo manualmente.');
    });
}
</script>
@endsection
