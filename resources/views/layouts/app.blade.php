<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ION Inventory</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sidebar-collapsed { width: 4rem; }
        .sidebar-expanded { width: 16rem; }
        .font-logo { font-family: 'Orbitron', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar-expanded fixed left-0 top-0 h-full bg-gray-800 text-white transition-all duration-300 z-50 md:z-10 -translate-x-full md:translate-x-0">
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold sidebar-text font-logo tracking-wider">ion</h1>
                </div>
                <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white hidden md:block">
                    <i class="fas fa-bars"></i>
                </button>
                <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-white md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <nav class="mt-4 overflow-y-auto h-[calc(100vh-80px)] scrollbar-hide">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Panel">
                <i class="fas fa-chart-line w-6"></i>
                <span class="ml-3 sidebar-text">Panel</span>
            </a>
            <!-- Scanner - Solo visible en móvil -->
            <a href="{{ route('scanner.index') }}" class="md:hidden flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('scanner.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Escáner">
                <i class="fas fa-qrcode w-6"></i>
                <span class="ml-3 sidebar-text">Escáner</span>
            </a>
            
            <div class="mt-2">
                <div class="px-4 py-2 text-xs text-gray-400 uppercase sidebar-text">Activos</div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_assets'))
                <a href="{{ route('assets.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('assets.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Todos los Activos">
                    <i class="fas fa-box w-6"></i>
                    <span class="ml-3 sidebar-text">Todos los Activos</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('create_assets'))
                <a href="{{ route('assets.create') }}" class="flex items-center px-4 py-3 hover:bg-gray-700" title="Agregar Nuevo Activo">
                    <i class="fas fa-plus w-6"></i>
                    <span class="ml-3 sidebar-text">Agregar Nuevo Activo</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('import_assets'))
                <a href="{{ route('imports.create') }}" class="flex items-center px-4 py-3 hover:bg-gray-700" title="Importar desde Excel">
                    <i class="fas fa-file-excel w-6"></i>
                    <span class="ml-3 sidebar-text">Importar desde Excel</span>
                </a>
                @endif
            </div>

            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_locations') || Auth::user()->hasPermission('manage_categories'))
            <div class="mt-2">
                <div class="px-4 py-2 text-xs text-gray-400 uppercase sidebar-text">Organización</div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_locations'))
                <a href="{{ route('locations.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('locations.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Ubicaciones">
                    <i class="fas fa-map-marker-alt w-6"></i>
                    <span class="ml-3 sidebar-text">Ubicaciones</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_categories'))
                <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('categories.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Categorías">
                    <i class="fas fa-folder w-6"></i>
                    <span class="ml-3 sidebar-text">Categorías</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_subcategories'))
                <a href="{{ route('subcategories.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('subcategories.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Subcategorías">
                    <i class="fas fa-folder-open w-6"></i>
                    <span class="ml-3 sidebar-text">Subcategorías</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_suppliers'))
                <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('suppliers.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Proveedores">
                    <i class="fas fa-truck w-6"></i>
                    <span class="ml-3 sidebar-text">Proveedores</span>
                </a>
                @endif
            </div>
            @endif




            <div class="mt-2">
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_maintenance'))
                <a href="{{ route('maintenances.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('maintenances.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Mantenimiento">
                    <i class="fas fa-wrench w-6"></i>
                    <span class="ml-3 sidebar-text">Mantenimiento</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_reports'))
                <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('reports.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Reportes">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="ml-3 sidebar-text">Reportes</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_movements'))
                <a href="{{ route('asset-movements.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('asset-movements.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Movimientos">
                    <i class="fas fa-exchange-alt w-6"></i>
                    <span class="ml-3 sidebar-text">Movimientos</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_employees'))
                <a href="{{ route('employees.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('employees.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Empleados">
                    <i class="fas fa-id-card w-6"></i>
                    <span class="ml-3 sidebar-text">Empleados</span>
                </a>
                @endif
            </div>

            @if(Auth::user()->isAdmin())
            <div class="mt-2">
                <div class="px-4 py-2 text-xs text-gray-400 uppercase sidebar-text">Administración</div>
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Usuarios">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3 sidebar-text">Usuarios</span>
                </a>
                <a href="{{ route('backups.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('backups.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Respaldos">
                    <i class="fas fa-database w-6"></i>
                    <span class="ml-3 sidebar-text">Respaldos</span>
                </a>
            </div>
            @endif

            <div class="mt-2 border-t border-gray-700 pt-2">
                <a href="{{ route('two-factor.show') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('two-factor.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Seguridad (2FA)">
                    <i class="fas fa-shield-alt w-6"></i>
                    <span class="ml-3 sidebar-text">Seguridad (2FA)</span>
                </a>
                <a href="{{ route('password.change') }}" class="flex items-center px-4 py-3 hover:bg-gray-700" title="Cambiar Contraseña">
                    <i class="fas fa-key w-6"></i>
                    <span class="ml-3 sidebar-text">Cambiar Contraseña</span>
                </a>
                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('superadmin.api.token.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700" title="API Token">
                    <i class="fas fa-code w-6"></i>
                    <span class="ml-3 sidebar-text">API Token</span>
                </a>
                @endif
                <a href="{{ route('support.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('support.*') ? 'bg-gray-700 border-l-4 border-white' : '' }}" title="Soporte">
                    <i class="fas fa-headset w-6"></i>
                    <span class="ml-3 sidebar-text">Soporte</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 hover:bg-gray-700 w-full text-left" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="ml-3 sidebar-text">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="ml-0 md:ml-64 transition-all duration-300 relative z-20 md:z-0 flex flex-col min-h-screen">
        <div class="flex-1 flex flex-col">
        @if(session()->has('impersonator_id'))
        <div class="bg-red-600 text-white px-4 py-2 flex justify-between items-center shadow-md relative z-50">
            <span class="font-bold"><i class="fas fa-user-secret mr-2"></i>Estás suplantando a {{ Auth::user()->name }}</span>
            <form action="{{ route('impersonate.stop') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white text-red-600 px-3 py-1 rounded-full text-sm font-bold hover:bg-gray-100 transition">
                    <i class="fas fa-times mr-1"></i>Volver a Superadmin
                </button>
            </form>
        </div>
        @endif
        <!-- Top Bar -->
        <div class="bg-white shadow-sm border-b border-gray-200 px-4 md:px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button onclick="toggleMobileSidebar()" class="mr-4 text-gray-600 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <!-- Notifications Dropdown -->

                    
                    <!-- Global Search -->
                    <div class="hidden md:block mr-4 flex-1 max-w-md">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input 
                                type="text" 
                                name="q" 
                                id="globalSearch"
                                placeholder="Buscar... (Ctrl+K)" 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                            >
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </form>
                    </div>
                    
                    <h2 class="text-xl md:text-2xl font-semibold text-gray-800">@yield('page-title', 'Panel')</h2>
                </div>
                <div class="flex items-center space-x-2 md:space-x-4">
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
                    <!-- PWA Install Button -->
                    <button id="installBtn" class="hidden text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-download text-lg"></i>
                    </button>
                    
                    <span class="text-xs md:text-sm text-gray-600 flex flex-col items-end">
                        <span class="font-bold text-gray-800">{{ Auth::user()->company ? Auth::user()->company->name : 'Sin Empresa' }}</span>
                        <span>
                            <i class="fas fa-user-circle mr-1"></i>{{ Auth::user()->name }}
                            @if(Auth::user()->isAdmin())
                                <span class="ml-1 bg-gray-200 text-gray-900 text-xs px-2 py-0.5 rounded">Admin</span>
                            @elseif(Auth::user()->isEditor())
                                <span class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">Editor</span>
                            @else
                                <span class="ml-1 bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded">Visor</span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </div>



        <!-- Content Area -->
        <div class="max-w-7xl mx-auto p-4 md:p-6 w-full">
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

            <!-- Global Announcements -->
            @auth
                @php
                    $globalAnnouncements = \App\Models\Announcement::active()->forUser(Auth::user())->get();
                @endphp
                
                @foreach($globalAnnouncements as $announcement)
                    <div class="mb-4 p-4 rounded-md shadow-sm border-l-4 
                        {{ $announcement->type == 'info' ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}
                        {{ $announcement->type == 'warning' ? 'bg-yellow-50 border-yellow-500 text-yellow-700' : '' }}
                        {{ $announcement->type == 'error' ? 'bg-red-50 border-red-500 text-red-700' : '' }}
                        {{ $announcement->type == 'success' ? 'bg-green-50 border-green-500 text-green-700' : '' }}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($announcement->type == 'info') <i class="fas fa-info-circle"></i> @endif
                                @if($announcement->type == 'warning') <i class="fas fa-exclamation-triangle"></i> @endif
                                @if($announcement->type == 'error') <i class="fas fa-exclamation-circle"></i> @endif
                                @if($announcement->type == 'success') <i class="fas fa-check-circle"></i> @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">{{ $announcement->title }}</h3>
                                <div class="mt-2 text-sm">
                                    <p>{{ $announcement->message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endauth

            @yield('content')
        </div>

        </div>
        <!-- Footer -->
        <footer class="mt-auto py-6 px-4 md:px-6 border-t border-gray-200 bg-gray-50">
            <div class="max-w-7xl mx-auto">
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
        // Initialize sidebar as collapsed on page load (desktop only)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            
            // Only on desktop (md breakpoint and above)
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('md:ml-64');
                mainContent.classList.add('md:ml-16');
                sidebarTexts.forEach(text => text.classList.add('hidden'));
            }
        });

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
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
            }
        }

        // Global Search Keyboard Shortcut (Ctrl+K or Cmd+K)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('globalSearch');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });

        // PWA Install Prompt
        let deferredPrompt;
        const installBtn = document.getElementById('installBtn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn.classList.remove('hidden');
        });

        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) {
                return;
            }
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response to the install prompt: ${outcome}`);
            deferredPrompt = null;
            installBtn.classList.add('hidden');
        });

        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            installBtn.classList.add('hidden');
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
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
