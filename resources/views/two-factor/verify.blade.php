<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Verificación 2FA - ION Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-logo { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold text-gray-800 font-logo tracking-widest">ion</h1>
            <p class="text-gray-600 mt-2">Verificación de Dos Factores</p>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-shield-alt text-blue-500 text-2xl mr-3"></i>
                <div>
                    <p class="text-sm text-blue-700 font-semibold">Seguridad Adicional</p>
                    <p class="text-xs text-blue-600 mt-1">Ingresa el código de 6 dígitos de tu aplicación de autenticación</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="code">
                    <i class="fas fa-key mr-2"></i>Código de Autenticación
                </label>
                <input 
                    type="text" 
                    name="code" 
                    id="code" 
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    class="shadow appearance-none border rounded w-full py-4 px-4 text-gray-700 text-center text-3xl font-mono tracking-widest leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required 
                    autofocus
                >
                <p class="text-xs text-gray-500 mt-2 text-center">
                    <i class="fas fa-mobile-alt mr-1"></i>Abre tu app de autenticación (Google Authenticator, Authy, etc.)
                </p>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200 mb-4"
            >
                <i class="fas fa-check-circle mr-2"></i>Verificar Código
            </button>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 text-center mb-3">
                    <i class="fas fa-life-ring mr-1"></i>¿Perdiste acceso a tu dispositivo?
                </p>
                <p class="text-xs text-gray-500 text-center">
                    Puedes usar un código de recuperación en lugar del código de 6 dígitos
                </p>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800 hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i>Volver al Login
                </a>
            </div>
        </form>
    </div>

    <script>
        // Auto-format code input
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Small delay to show the complete code
                setTimeout(() => {
                    this.form.submit();
                }, 300);
            }
        });
    </script>
</body>
</html>
