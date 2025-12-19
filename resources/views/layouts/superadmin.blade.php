<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin - ION Inventory</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
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

        /* Dark mode for SweetAlert2 */
        .dark .swal2-popup {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
            border: 1px solid #374151;
        }
        .dark .swal2-title, .dark .swal2-html-container, .dark .swal2-content {
            color: #f3f4f6 !important;
        }
        .dark .swal2-footer {
            border-top: 1px solid #374151 !important;
            color: #9ca3af !important;
        }
        .dark .swal2-close:hover {
            color: #ffffff !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar-expanded fixed left-0 top-0 h-full bg-gray-900 text-white transition-all duration-300 z-50 sidebar-mobile-hidden md:sidebar-mobile-visible">
        <div class="p-4 border-b border-gray-800">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold sidebar-text text-white font-logo tracking-wider">ion <span class="text-sm font-sans text-gray-400">ADMIN</span></h1>
                <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white hidden md:block">
                    <i class="fas fa-bars"></i>
                </button>
                <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-white md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <nav class="mt-4 overflow-y-auto h-[calc(100vh-80px)]">
            <a href="{{ route('superadmin.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.index') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                <i class="fas fa-tachometer-alt w-6"></i>
                <span class="ml-3 sidebar-text">Dashboard Global</span>
            </a>
            
            <div class="mt-2">
                <div class="px-4 py-2 text-xs text-gray-400 uppercase sidebar-text">Gestión</div>
                <a href="{{ route('superadmin.companies.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.companies.*') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-building w-6"></i>
                    <span class="ml-3 sidebar-text">Empresas</span>
                </a>
                <a href="{{ route('superadmin.system-status') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.system-status') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-server w-6"></i>
                    <span class="ml-3 sidebar-text">Estado del Sistema</span>
                </a>
                <a href="{{ route('superadmin.activity-logs') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.activity-logs') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span class="ml-3 sidebar-text">Logs de Actividad</span>
                </a>
                <a href="{{ route('superadmin.backups.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.backups.*') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-database w-6"></i>
                    <span class="ml-3 sidebar-text">Backups</span>
                </a>
                <a href="{{ route('superadmin.webhooks.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.webhooks.*') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-satellite-dish w-6"></i>
                    <span class="ml-3 sidebar-text">Webhooks</span>
                </a>
                <a href="{{ route('superadmin.api.token.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.api.token.*') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-code w-6"></i>
                    <span class="ml-3 sidebar-text">API Token</span>
                </a>
                <a href="{{ route('superadmin.support.validation') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.support.validation') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-user-check w-6"></i>
                    <span class="ml-3 sidebar-text">Validar Cliente</span>
                </a>
                <a href="{{ route('superadmin.announcements.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.announcements.*') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-bullhorn w-6"></i>
                    <span class="ml-3 sidebar-text">Anuncios</span>
                </a>
                <a href="{{ route('superadmin.tickets.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.tickets.*') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-ticket-alt w-6"></i>
                    <span class="ml-3 sidebar-text">Tickets de Soporte</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="ml-0 md:ml-64 transition-all duration-300 flex flex-col min-h-screen">
        <div class="flex-1 flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 px-4 md:px-6 py-4 transition-colors">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button onclick="toggleMobileSidebar()" class="mr-4 text-gray-600 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Panel Superadmin')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors focus:outline-none" title="Cambiar tema">
                        <i id="darkModeIcon" class="fas fa-moon text-xl"></i>
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button id="notification-btn" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white relative focus:outline-none p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            @php
                                $unreadCount = Auth::user()->notifications()->unread()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div id="notification-dropdown" 
                             class="hidden absolute right-0 mt-2 w-[calc(100vw-1rem)] min-w-[320px] bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 sm:w-[32rem] origin-top-right transform transition-all duration-200">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <h3 class="font-semibold text-gray-800 dark:text-white">Notificaciones</h3>
                                @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">
                                        Marcar todas como leídas
                                    </button>
                                </form>
                                @endif
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                @php
                                    $recentNotifications = Auth::user()->notifications()->take(5)->get();
                                @endphp
                                @forelse($recentNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification) }}" 
                                       onclick="event.preventDefault(); document.getElementById('read-form-{{ $notification->id }}').submit();"
                                       class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                @php
                                                    $iconClass = match($notification->type) {
                                                        'support_resolved' => 'check-circle text-green-500',
                                                        'support_admin_note' => 'comment text-blue-500',
                                                        'support_status_changed' => 'sync text-blue-500',
                                                        'support_request_created' => 'bell text-orange-500',
                                                        'support_user_response' => 'reply text-purple-500',
                                                        default => 'info-circle text-gray-500',
                                                    };
                                                @endphp
                                                <i class="fas fa-{{ $iconClass }} text-lg mt-1"></i>
                                            </div>
                                            <div class="ml-3 flex-1 overflow-hidden" style="max-width: 250px;">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $notification->title }}</p>
                                                <p class="text-xs text-gray-600 mt-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($notification->message, 80) }}</p>
                                                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <form id="read-form-{{ $notification->id }}" action="{{ route('notifications.read', $notification) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                @empty
                                    <div class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-3xl mb-2"></i>
                                        <p class="text-sm">No tienes notificaciones</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            @if($recentNotifications->count() > 0)
                            <div class="p-3 border-t border-gray-200 text-center">
                                <a href="{{ route('notifications.index') }}" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>


                    <!-- User Menu Dropdown -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center text-sm focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
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
        <div class="p-4 md:p-6">
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
                            Desarrollado por <a href="#" class="text-gray-700 hover:text-gray-900 font-semibold transition duration-200">OurDeveloper</a>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Versión 1.0.0
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
        // Dark Mode Logic
        (function() {
            const toggle = document.getElementById('darkModeToggle');
            const icon = document.getElementById('darkModeIcon');
            const html = document.documentElement;
            if (toggle && icon) {
                const theme = localStorage.getItem('theme') || 'light';
                if (theme === 'dark') {
                    html.classList.add('dark');
                    icon.className = 'fas fa-sun text-xl';
                }
                toggle.onclick = () => {
                    html.classList.toggle('dark');
                    const isDark = html.classList.contains('dark');
                    icon.className = isDark ? 'fas fa-sun text-xl' : 'fas fa-moon text-xl';
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                };
            }
        })();
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
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores de Validación',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#ef4444',
            });
        @endif
    </script>
</body>
</html>
