<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadministrador - ION Inventory</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar-collapsed { width: 4rem; }
        .sidebar-expanded { width: 16rem; }
        @media (max-width: 768px) {
            .sidebar-mobile-hidden { transform: translateX(-100%); }
            .sidebar-mobile-visible { transform: translateX(0); }
        }
        .font-logo { font-family: 'Orbitron', sans-serif; }
        
        /* Modern Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(99, 102, 241, 0.4); }

        /* Glassmorphism for Top Bar */
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Sidebar Item States */
        .sidebar-item-active {
            background: linear-gradient(to right, rgba(99, 102, 241, 0.15), transparent);
            border-left: 4px solid #6366f1;
            color: #818cf8 !important;
        }
        .dark .sidebar-item-active {
            background: linear-gradient(to right, rgba(99, 102, 241, 0.2), transparent);
            border-left: 4px solid #818cf8;
            color: #a5b4fc !important;
        }

        /* Modern SweetAlert2 Premium Theme */
        .swal2-popup {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 1.5rem !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .swal2-title {
            color: #ffffff !important;
            font-weight: 800 !important;
            font-size: 1.5rem !important;
            letter-spacing: -0.025em !important;
        }
        .swal2-html-container {
            color: rgba(203, 213, 225, 0.9) !important;
            font-size: 1rem !important;
            line-height: 1.6 !important;
        }
        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%) !important;
            border-radius: 0.8rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3) !important;
            transition: all 0.3s !important;
        }
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 20px -3px rgba(99, 102, 241, 0.4) !important;
        }
        .swal2-cancel {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
            border-radius: 0.8rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 600 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .swal2-icon {
            border-width: 3px !important;
            transform: scale(1.1) !important;
            margin-bottom: 1.5rem !important;
        }
        .swal2-success-circular-line, .swal2-success-fix, .swal2-success-aside-line {
            display: none !important;
        }
        .swal2-timer-progress-bar {
            background: linear-gradient(to right, #6366f1, #a855f7) !important;
            height: 4px !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Plus Jakarta Sans', 'sans-serif'],
                        'logo': ['Orbitron', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar-expanded fixed left-0 top-0 h-full transition-all duration-300 z-50 sidebar-mobile-hidden md:sidebar-mobile-visible text-slate-300" style="background: var(--color-black-pearl); border-right: 1px solid rgba(14, 104, 115, 0.2); color: #B0C4C9;">
        <div class="p-6 border-b border-white/5">
            <div class="flex items-center justify-between">
                <div class="flex flex-col sidebar-text w-full">
                    <a href="{{ route('superadmin.index') }}" class="flex flex-col items-center justify-center w-full group">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('img/logo-horizontal.png') }}" alt="ION Inventory" class="h-10 w-auto transition-all group-hover:opacity-90">
                            <span class="text-[10px] font-sans font-black uppercase tracking-wider transition-all" style="color: var(--green-caribbean); background: rgba(0, 223, 129, 0.1); padding: 0.375rem 0.5rem; border-radius: 0.375rem; border: 1px solid var(--green-bangladesh); box-shadow: 0 0 10px rgba(0, 223, 129, 0.1);">Admin</span>
                        </div>
                    </a>
                </div>
                <button onclick="toggleSidebar()" class="text-slate-500 hover:text-white transition-colors hidden md:block">
                    <i class="fas fa-bars"></i>
                </button>
                <button onclick="toggleMobileSidebar()" class="text-slate-500 hover:text-white md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <nav class="mt-4 overflow-y-auto h-[calc(100vh-80px)] px-2">
            <a href="{{ route('superadmin.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.index') ? 'sidebar-item-active' : '' }}">
                <i class="fas fa-tachometer-alt w-6"></i>
                <span class="ml-3 sidebar-text font-medium text-sm truncate">Panel Global</span>
            </a>
            
            <div class="mt-4">
                <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Gestión</div>
                <a href="{{ route('superadmin.companies.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.companies.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-building w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Empresas</span>
                </a>
                <a href="{{ route('superadmin.system-status') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.system-status') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-server w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Estado del Sistema</span>
                </a>
                <a href="{{ route('superadmin.activity-logs') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.activity-logs') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Logs de Actividad</span>
                </a>
                <a href="{{ route('superadmin.backups.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.backups.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-database w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Backups</span>
                </a>
                <a href="{{ route('superadmin.webhooks.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.webhooks.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-satellite-dish w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Webhooks</span>
                </a>
                <a href="{{ route('superadmin.api.token.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.api.token.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-code w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">API Token</span>
                </a>
                <a href="{{ route('superadmin.support.validation') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.support.validation') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-user-check w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Validar Cliente</span>
                </a>
                <a href="{{ route('superadmin.announcements.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.announcements.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-bullhorn w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Anuncios</span>
                </a>
                <a href="{{ route('superadmin.tickets.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('superadmin.tickets.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-ticket-alt w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Tickets de Soporte</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="ml-0 md:ml-64 transition-all duration-300 flex flex-col min-h-screen bg-slate-50 dark:bg-navy-900">
        <div class="flex flex-col">
        <!-- Top Bar -->
        <header class="glass-header sticky top-0 z-40 px-4 md:px-6 py-3 transition-colors">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button onclick="toggleMobileSidebar()" class="mr-4 text-gray-600 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Panel Superadmin')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notifications removed as requested -->


                    <!-- User Menu Dropdown -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center text-sm focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Superadministrador</div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 py-1 z-50 origin-top-right transition-all duration-200">
                            <!-- User Info -->
                             <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <!-- Logout -->
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span class="ml-3 font-medium">Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 pt-4 pb-6">
            @yield('content')
        </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto py-6 px-4 md:px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 transition-colors">
            <div class="max-w-7xl mx-auto w-full">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            © {{ date('Y') }} <strong>ION</strong>. Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-sm text-gray-600">
                            Desarrollado por <a href="#" class="text-gray-700 hover:text-gray-900 font-semibold transition duration-200">OutDeveloper</a>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Versión 1.1.0
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            
            if (sidebar.classList.contains('sidebar-expanded')) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('md:ml-64');
                mainContent.classList.add('md:ml-16');
                sidebarTexts.forEach(text => text.classList.add('hidden'));
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('md:ml-16');
                mainContent.classList.add('md:ml-64');
                sidebarTexts.forEach(text => text.classList.remove('hidden'));
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            if (sidebar.classList.contains('sidebar-mobile-hidden')) {
                sidebar.classList.remove('sidebar-mobile-hidden');
                sidebar.classList.add('sidebar-mobile-visible');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.classList.remove('sidebar-mobile-visible');
                overlay.classList.add('hidden');
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('notification-btn');
            const dropdown = document.getElementById('notification-dropdown');
            
            if (btn && dropdown) {
                // Toggle dropdown
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
                
                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
                
                // Prevent closing when clicking inside dropdown
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
        // Force Light Mode
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        localStorage.setItem('theme', 'light');
    </script>
    <script>
        // User Menu Dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-menu-dropdown');
            
            if (userBtn && userDropdown) {
                // Toggle dropdown
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
                
                // Prevent closing when clicking inside dropdown
                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
    <script>
        // SweetAlert2 Notifications
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: '{{ session('success') }}',
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: 'rgba(15, 23, 42, 0.95)',
                color: '#fff'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'swal-neon-popup',
                    confirmButton: 'swal-neon-confirm'
                }
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores de Validación',
                html: '<ul style="text-align: left; font-size: 0.9rem;">@foreach($errors->all() as $error)<li><i class="fas fa-chevron-right mr-2" style="color: #00f5ff;"></i>{{ $error }}</li>@endforeach</ul>',
                showConfirmButton: true,
                confirmButtonText: 'Revisar',
                customClass: {
                    confirmButton: 'bg-gradient-to-r from-sky-600 to-cyan-600'
                }
            });
        @endif
    </script>
</body>
</html>
