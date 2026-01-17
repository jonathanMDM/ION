<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Recuperar Contraseña - ION Inventory</title>
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
            <div class="mb-4">
                <img src="{{ asset('img/logo-horizontal.png') }}" alt="ION Inventory" class="h-14 w-auto mx-auto">
            </div>
            <p class="text-gray-600 mt-2">Recuperar Contraseña</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-2"></i>
                Ingresa tu correo electrónico y te enviaremos una contraseña temporal.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    <i class="fas fa-envelope mr-2"></i>Correo Electrónico
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required 
                    autofocus
                    placeholder="tu@email.com"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200 mb-4"
            >
                <i class="fas fa-paper-plane mr-2"></i>Enviar Contraseña Temporal
            </button>

            <a href="{{ route('login') }}" class="block text-center text-sm text-gray-600 hover:text-gray-800 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i>Volver al Login
            </a>
        </form>
    </div>
</body>
</html>
