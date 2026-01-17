<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <title>Acceso | ION Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Plus Jakarta Sans', 'sans-serif'],
                        'logo': ['Orbitron', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: radial-gradient(circle at top right, #1e1b4b, #0f172a, #020617);
            overflow: hidden;
        }

        .glass-container {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px border;
            border-image-source: linear-gradient(to bottom right, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .animated-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            opacity: 0.4;
        }

        .blob {
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.3) 0%, rgba(79, 70, 229, 0) 70%);
            filter: blur(40px);
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(10%, 10%); }
        }

        .input-glow:focus {
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.2);
            border-color: rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 font-sans text-slate-200">
    <div class="blob" style="top: -100px; left: -100px;"></div>
    <div class="blob" style="bottom: -100px; right: -100px; background: radial-gradient(circle, rgba(147, 51, 234, 0.2) 0%, rgba(147, 51, 234, 0) 70%); animation-delay: -5s;"></div>

    <div class="relative w-full max-w-md">
        <!-- Decoration -->
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -bottom-10 -right-10 w-20 h-20 bg-purple-500 rounded-full blur-3xl opacity-20"></div>

        <div class="glass-container relative overflow-hidden rounded-[2.5rem] border border-white/10 p-8 sm:p-10">
            <!-- Header -->
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="mb-4">
                    <img src="{{ asset('img/logo-horizontal.png') }}" alt="ION Inventory" class="h-16 w-auto mx-auto">
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-2xl mb-6 text-sm">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="font-bold">Error de acceso</span>
                    </div>
                    <ul class="opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2 px-1" for="email">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <i class="fas fa-at"></i>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            placeholder="nombre@empresa.com"
                            class="w-full bg-slate-800/50 border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white placeholder-slate-500 outline-none transition-all input-glow focus:bg-slate-800/80" 
                            required 
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2 px-1" for="password">
                        Contraseña
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <i class="fas fa-key"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="••••••••"
                            class="w-full bg-slate-800/50 border border-white/5 rounded-2xl py-4 pl-12 pr-12 text-white placeholder-slate-500 outline-none transition-all input-glow focus:bg-slate-800/80" 
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition-colors"
                        >
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center group cursor-pointer">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" class="peer sr-only">
                            <div class="w-5 h-5 bg-slate-800 border border-white/10 rounded-md peer-checked:bg-indigo-600 peer-checked:border-indigo-500 transition-all"></div>
                            <i class="fas fa-check absolute scale-0 peer-checked:scale-100 text-[10px] text-white w-5 text-center transition-transform"></i>
                        </div>
                        <span class="ml-2 text-xs text-slate-400 group-hover:text-slate-200 transition-colors">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="group relative w-full py-4 bg-white text-slate-950 font-black rounded-2xl hover:bg-indigo-50 active:scale-[0.98] transition-all shadow-[0_20px_40px_rgba(255,255,255,0.1)] overflow-hidden"
                >
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2 text-sm"></i>
                        INICIAR SESIÓN
                    </span>
                </button>

            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
