<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Cambio de Contraseña Requerido - ION Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-logo { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-8">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="text-5xl mb-3">🔐</div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Cambio de Contraseña Requerido</h1>
            <p class="text-gray-600 text-sm">Por seguridad, debes cambiar tu contraseña temporal</p>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Crea una contraseña segura que contenga al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y un carácter especial.
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('force-password-change.update') }}">
            @csrf

            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-lock mr-2"></i>Nueva Contraseña
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    minlength="8"
                    class="shadow appearance-none border @error('password') border-red-500 @enderror rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-gray-500"
                    placeholder="Ingresa tu nueva contraseña"
                >
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1 font-semibold">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-lock mr-2"></i>Confirmar Contraseña
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    required 
                    minlength="8"
                    class="shadow appearance-none border @error('password') border-red-500 @enderror rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-gray-500"
                    placeholder="Confirma tu nueva contraseña"
                >
                <p id="match-error" class="hidden text-red-500 text-xs italic mt-1 font-semibold">
                    <i class="fas fa-exclamation-circle mr-1"></i>Las contraseñas no coinciden.
                </p>
                @if($errors->has('password') && str_contains($errors->first('password'), 'coinciden'))
                     <p class="text-red-500 text-xs italic mt-1 font-semibold">
                        <i class="fas fa-exclamation-circle mr-1"></i>Las contraseñas no coinciden.
                    </p>
                @endif
            </div>

            <!-- Password Requirements -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 transition-all duration-300">
                <p class="text-xs font-semibold text-gray-700 mb-2">Requisitos de la contraseña:</p>
                <ul class="text-xs text-gray-500 space-y-2">
                    <li class="flex items-center transition-colors duration-300" id="li-length">
                        <i id="icon-length" class="fas fa-circle text-[10px] text-gray-300 mr-2"></i>
                        <span>Mínimo 8 caracteres</span>
                    </li>
                    <li class="flex items-center transition-colors duration-300" id="li-upper">
                        <i id="icon-upper" class="fas fa-circle text-[10px] text-gray-300 mr-2"></i>
                        <span>Al menos una letra mayúscula</span>
                    </li>
                    <li class="flex items-center transition-colors duration-300" id="li-lower">
                        <i id="icon-lower" class="fas fa-circle text-[10px] text-gray-300 mr-2"></i>
                        <span>Al menos una letra minúscula</span>
                    </li>
                    <li class="flex items-center transition-colors duration-300" id="li-number">
                        <i id="icon-number" class="fas fa-circle text-[10px] text-gray-300 mr-2"></i>
                        <span>Al menos un número</span>
                    </li>
                    <li class="flex items-center transition-colors duration-300" id="li-special">
                        <i id="icon-special" class="fas fa-circle text-[10px] text-gray-300 mr-2"></i>
                        <span>Carácter especial (@$!%*?&#.)</span>
                    </li>
                </ul>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                id="submit-btn"
                class="w-full bg-gray-800 hover:bg-black text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200"
            >
                <i class="fas fa-key mr-2"></i>Cambiar Contraseña y Continuar
            </button>
        </form>

        <!-- Logout Option -->
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    <i class="fas fa-sign-out-alt mr-1"></i>Cerrar Sesión
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-gray-500">
            <p>© {{ date('Y') }} ION Inventory. Todos los derechos reservados.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const matchError = document.getElementById('match-error');
            
            const requirements = {
                length: { re: /.{8,}/, icon: 'icon-length', li: 'li-length' },
                upper: { re: /[A-Z]/, icon: 'icon-upper', li: 'li-upper' },
                lower: { re: /[a-z]/, icon: 'icon-lower', li: 'li-lower' },
                number: { re: /[0-9]/, icon: 'icon-number', li: 'li-number' },
                special: { re: /[@$!%*?&#.]/, icon: 'icon-special', li: 'li-special' }
            };

            function validate() {
                const val = passwordInput.value;
                const confirmVal = confirmInput.value;

                // Validate each requirement
                Object.keys(requirements).forEach(key => {
                    const req = requirements[key];
                    const iconEl = document.getElementById(req.icon);
                    const liEl = document.getElementById(req.li);
                    
                    if (req.re.test(val)) {
                        iconEl.className = 'fas fa-check-circle text-green-500 mr-2';
                        liEl.className = 'flex items-center text-green-700 font-medium';
                    } else {
                        iconEl.className = 'fas fa-circle text-[10px] text-gray-300 mr-2';
                        liEl.className = 'flex items-center text-gray-500';
                    }
                });

                // Match validation
                if (confirmVal.length > 0) {
                    if (val !== confirmVal) {
                        matchError.classList.remove('hidden');
                        confirmInput.classList.add('border-red-500');
                    } else {
                        matchError.classList.add('hidden');
                        confirmInput.classList.remove('border-red-500');
                        confirmInput.classList.add('border-green-500');
                    }
                } else {
                    matchError.classList.add('hidden');
                    confirmInput.classList.remove('border-red-500', 'border-green-500');
                }
            }

            passwordInput.addEventListener('input', validate);
            confirmInput.addEventListener('input', validate);
        });
    </script>
</body>
</html>
