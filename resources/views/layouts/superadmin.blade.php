<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin - ION Inventory</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-collapsed { width: 4rem; }
        .sidebar-expanded { width: 16rem; }
        @media (max-width: 768px) {
            .sidebar-mobile-hidden { transform: translateX(-100%); }
            .sidebar-mobile-visible { transform: translateX(0); }
        }
        .font-logo { font-family: 'Orbitron', sans-serif; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100">
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
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
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
                <a href="{{ route('superadmin.support.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('superadmin.support.index') ? 'bg-gray-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-headset w-6"></i>
                    <span class="ml-3 sidebar-text">Soporte</span>
                </a>
            </div>

            <div class="mt-2 border-t border-gray-800 pt-2">
                <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 hover:bg-gray-800 w-full text-left">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="ml-3 sidebar-text">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="ml-0 md:ml-64 transition-all duration-300 flex flex-col min-h-screen">
        <div class="flex-1 flex flex-col">
        <!-- Top Bar -->
        <div class="bg-white shadow-sm border-b border-gray-200 px-4 md:px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button onclick="toggleMobileSidebar()" class="mr-4 text-gray-600 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl md:text-2xl font-semibold text-gray-800">@yield('page-title', 'Panel Superadmin')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button id="notification-btn" class="text-gray-500 hover:text-gray-700 relative focus:outline-none">
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
                             class="hidden absolute right-0 mt-2 w-[calc(100vw-1rem)] min-w-[320px] bg-white rounded-lg shadow-lg border border-gray-200 z-50 sm:w-[32rem] origin-top-right transform transition-all duration-200">
                            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="font-semibold text-gray-800">Notificaciones</h3>
                                @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-gray-600 hover:text-gray-800">
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
                                       class="block px-5 py-4 hover:bg-gray-50 border-b border-gray-100 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50' }}">
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

                    <span class="text-sm text-gray-600">
                        <i class="fas fa-user-shield mr-1 text-gray-600"></i>{{ Auth::user()->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-4 md:p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto py-6 px-4 md:px-6 border-t border-gray-200 bg-gray-50">
            <div class="max-w-7xl mx-auto w-full">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-sm text-gray-600">
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
    </script>
</body>
</html>
