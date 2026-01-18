<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ION INVENTORY</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <!-- Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <style>
        .sidebar-collapsed { width: 4rem; }
        .sidebar-expanded { width: 16rem; }
        .font-logo { font-family: 'Orbitron', sans-serif; }
            /* Forest Tech Palette (Altitude) */
            --bg-body: #F8FAFB; /* Ultra light gray for clean, fresh look */
            --bg-card: #FFFFFF;
            --bg-sidebar: #032221; /* Dark Deep Teal from Image 1 / Gradient 4 Base */
            
            /* Text Colors */
            --text-main: #06231D;      /* Black Green */
            --text-secondary: #587974; /* Muted Teal */
            --text-light: #FFFFFF;
            
            /* Accents */
            --color-primary: #076653; /* Deep Green */
            --color-secondary: #0C342C; /* Dark Forest */
            --color-accent-lime: #E3EF26; /* Acid Lime */
            
            /* Gradients (Altitude Palette) */
            --gradient-lime: linear-gradient(180deg, #FFFDEE 0%, #E3EF26 100%);     /* Gradient 1 */
            --gradient-forest: linear-gradient(180deg, #E2FBCE 0%, #076653 100%);   /* Gradient 2 */
            --gradient-dark: linear-gradient(180deg, #E3EF26 0%, #0C342C 100%);     /* Gradient 3 */
            --gradient-deep: linear-gradient(180deg, #076653 0%, #06231D 100%);     /* Gradient 4 */
            
            /* Functional Mappings */
            --card-shadow: 0 8px 30px rgba(7, 102, 83, 0.06); /* Green-tinted shadow */
            --border-light: #CFE5E1;

            /* Sidebar Active State (Image 1 Style) */
            --color-active-text: #FFFFFF;
            --bg-active-item: rgba(255, 255, 255, 0.1); /* Glassy effect on dark bg */
            --border-active: #E3EF26; /* Lime border accent */
        }
        
        /* Modern Scrollbar - Light Theme */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #F4F7FE; }
        ::-webkit-scrollbar-thumb { background: #E0E5F2; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #A3AED0; }

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
        /* Sidebar Item States - Soft Pill Style */
        /* Sidebar Item States - Soft Pill Style (Updated to Red/Pink Theme) */
        /* Sidebar Item States - Forest Tech Style */
        .sidebar-item-active {
            background-color: var(--bg-active-item) !important;
            color: var(--color-active-text) !important;
            font-weight: 600 !important;
            border-left: 4px solid var(--border-active) !important;
            border-right: none !important;
            border-radius: 0 12px 12px 0 !important; /* Rounded on right only */
            margin-right: 1rem;
        }

        .sidebar-item-active i {
            color: var(--border-active) !important; /* Lime icon */
        }
        
        .sidebar-text {
            color: #8A9E9A; /* Muted text for inactive */
            font-weight: 500;
        }
        
        .sidebar-item-active .sidebar-text {
            color: var(--text-main) !important;
        }
        
        /* SweetAlert Forest Tech Styling */
        /* SweetAlert Soft UI Styling */
        .swal-neon-popup, .swal2-popup {
            background: #FFFFFF !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            border: none !important;
        }
        .swal-neon-popup .swal2-title, .swal2-title {
            color: var(--text-main) !important;
        }
        .swal-neon-popup .swal2-html-container, .swal2-html-container {
            color: var(--text-secondary) !important;
        }
        .swal-neon-confirm, .swal2-confirm {
            background: var(--gradient-blue) !important;
            color: #FFFFFF !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(67, 24, 255, 0.4) !important;
        }
        .swal2-cancel {
            background: #F4F7FE !important;
            color: var(--text-secondary) !important;
            border-radius: 12px !important;
        }

        /* SweetAlert Magma Custom Icons */
        .swal2-icon.swal2-success { border-color: var(--color-blue-lagoon) !important; color: var(--color-blue-lagoon) !important; }
        .swal2-icon.swal2-success .swal2-success-ring { border: 4px solid rgba(14, 104, 115, 0.2) !important; }
        .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: var(--color-blue-lagoon) !important; }
        
        .swal2-icon.swal2-error { border-color: var(--color-lust) !important; color: var(--color-lust) !important; }
        .swal2-icon.swal2-error .swal2-x-mark-line-left, 
        .swal2-icon.swal2-error .swal2-x-mark-line-right { background-color: var(--color-lust) !important; }
        
        .swal2-icon.swal2-warning { border-color: var(--color-burnt-orange) !important; color: var(--color-burnt-orange) !important; }
        
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

        /* Mobile Responsive SweetAlert */
        @media (max-width: 640px) {
            .swal2-popup {
                width: auto !important;
                max-width: 340px !important;
                padding: 1rem !important;
                border-radius: 1rem !important;
            }
            .swal2-title {
                font-size: 1.1rem !important;
                margin-bottom: 0.5rem !important;
            }
            .swal2-html-container {
                font-size: 0.85rem !important;
                margin: 0.5rem 0 !important;
            }
            .swal2-icon {
                margin-bottom: 0.75rem !important;
                transform: scale(0.7) !important;
                margin-top: 0 !important;
            }
            .swal2-confirm, .swal2-cancel {
                padding: 0.5rem 1rem !important;
                font-size: 0.75rem !important;
                margin: 0.25rem 0.25rem !important;
                width: auto !important;
                display: inline-block !important;
            }
            .swal2-actions {
                flex-direction: row !important;
                gap: 0.25rem !important;
                width: 100% !important;
                justify-content: center !important;
                margin-top: 1rem !important;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body class="transition-colors duration-200" style="background-color: var(--bg-body); color: var(--text-main);">
    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar-expanded fixed left-0 top-0 h-full transition-all duration-300 z-50 md:z-10 -translate-x-full md:translate-x-0" style="background: var(--bg-sidebar); border-right: none; box-shadow: 4px 0 24px rgba(0,0,0,0.02);">
        <div class="p-6 border-b border-white/5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                <div class="flex flex-col sidebar-text">
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center group">
                        <img src="{{ asset('img/logo-horizontal.png') }}" alt="ION Inventory" class="h-12 w-auto transition-all group-hover:opacity-90">
                    </a>
                </div>
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
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : '' }}" title="Panel">
                <i class="fas fa-chart-line w-6"></i>
                <span class="ml-3 sidebar-text font-medium text-sm truncate">Panel</span>
            </a>
            <!-- Scanner - Solo visible en móvil -->
            <a href="{{ route('scanner.index') }}" class="md:hidden flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('scanner.*') ? 'sidebar-item-active' : '' }}" title="Escáner">
                <i class="fas fa-qrcode w-6"></i>
                <span class="ml-3 sidebar-text font-medium text-sm truncate">Escáner</span>
            </a>
            
            <div class="mt-4">
                <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Activos</div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_assets'))
                <a href="{{ route('assets.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('assets.*') ? 'sidebar-item-active' : '' }}" title="Todos los Activos">
                    <i class="fas fa-box w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Todos los Activos</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('create_assets'))
                <a href="{{ route('assets.create') }}" id="tour-add-asset" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('assets.create') ? 'sidebar-item-active' : '' }}" title="Agregar Nuevo Activo">
                    <i class="fas fa-plus w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Agregar Nuevo Activo</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('import_assets'))
                <a href="{{ route('imports.create') }}" id="tour-import" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('imports.*') ? 'sidebar-item-active' : '' }}" title="Importar desde Excel">
                    <i class="fas fa-file-excel w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Importar desde Excel</span>
                </a>
                @endif
            </div>

            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_locations') || Auth::user()->hasPermission('manage_categories'))
            <div class="mt-4">
                <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Organización</div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_locations'))
                <a href="{{ route('locations.index') }}" id="tour-locations" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('locations.*') ? 'sidebar-item-active' : '' }}" title="Ubicaciones">
                    <i class="fas fa-map-marker-alt w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Ubicaciones</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_categories'))
                <a href="{{ route('categories.index') }}" id="tour-categories" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('categories.*') ? 'sidebar-item-active' : '' }}" title="Categorías">
                    <i class="fas fa-folder w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Categorías</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_subcategories'))
                <a href="{{ route('subcategories.index') }}" id="tour-subcategories" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('subcategories.*') ? 'sidebar-item-active' : '' }}" title="Subcategorías">
                    <i class="fas fa-folder-open w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Subcategorías</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_suppliers'))
                <a href="{{ route('suppliers.index') }}" id="tour-suppliers" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('suppliers.*') ? 'sidebar-item-active' : '' }}" title="Proveedores">
                    <i class="fas fa-truck w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Proveedores</span>
                </a>
                @endif
            </div>

@if(auth()->user()->company && auth()->user()->company->hasModule("cost_centers"))
<div class="mt-4">
    <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Finanzas</div>
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission("view_cost_centers"))
    <a href="{{ route("cost-centers.index") }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs("cost-centers.*") ? "sidebar-item-active" : "" }}" title="Centros de Costo">
        <i class="fas fa-building w-6"></i>
        <span class="ml-3 sidebar-text font-medium text-sm truncate">Centros de Costo</span>
    </a>
    @endif
</div>
@endif
            @endif

            <div class="mt-4">
                <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Operaciones</div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_maintenance'))
                <a href="{{ route('maintenances.index') }}" id="tour-maintenance" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('maintenances.*') ? 'sidebar-item-active' : '' }}" title="Mantenimiento">
                    <i class="fas fa-wrench w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Mantenimiento</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_reports'))
                <a href="{{ route('reports.index') }}" id="tour-reports" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('reports.*') ? 'sidebar-item-active' : '' }}" title="Reportes">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Reportes</span>
                </a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_employees'))
                <a href="{{ route('employees.index') }}" id="tour-employees" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('employees.*') ? 'sidebar-item-active' : '' }}" title="Empleados">
                    <i class="fas fa-id-card w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Empleados</span>
                </a>
                @endif
            </div>

            <div class="mt-4 pb-20">
                <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Configuración</div>

                @if(Auth::user()->isAdmin())
                <a href="{{ route('users.index') }}" id="tour-users" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('users.*') ? 'sidebar-item-active' : '' }}" title="Usuarios">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Usuarios</span>
                </a>
                <a href="{{ route('backups.index') }}" id="tour-backups" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('backups.*') ? 'sidebar-item-active' : '' }}" title="Respaldos">
                    <i class="fas fa-database w-6"></i>
                    <span class="ml-3 sidebar-text font-medium text-sm truncate">Respaldos</span>
                </a>
                @endif

                @if(Auth::user()->isSuperAdmin())
                <div class="mt-4 border-t border-white/5 pt-4">
                    <a href="{{ route('superadmin.api.token.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1" title="API Token">
                        <i class="fas fa-code w-6"></i>
                        <span class="ml-3 sidebar-text font-medium text-indigo-400">API Token</span>
                    </a>
                </div>
                @endif
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="ml-0 md:ml-64 transition-all duration-300 relative z-20 md:z-0 flex flex-col min-h-screen bg-slate-100 dark:bg-navy-900">
        <div class="flex flex-col">
        @if(session()->has('impersonator_id'))
        <div class="backdrop-blur-xl text-white px-3 py-2 md:px-6 flex flex-row justify-between items-center shadow-2xl relative z-[60]" style="background: rgba(0, 15, 8, 0.95); border-bottom: 1px solid var(--green-bangladesh);">
            <div class="flex items-center gap-2 md:gap-4 overflow-hidden">
                <div class="flex h-7 w-7 md:h-9 md:w-9 items-center justify-center rounded-xl shadow-inner flex-shrink-0" style="background: rgba(3, 98, 76, 0.3); border: 1px solid var(--green-bangladesh);">
                    <i class="fas fa-user-secret animate-pulse text-xs md:text-lg" style="color: var(--green-caribbean);"></i>
                </div>
                <div class="min-w-0">
                    <div class="hidden md:flex items-center gap-2">
                        <span class="text-[10px] uppercase tracking-[0.2em] font-black" style="color: var(--green-mountain);">Modo Admin</span>
                        <span class="h-1 w-1 rounded-full" style="background: var(--green-caribbean);"></span>
                        <span class="text-[10px] uppercase tracking-[0.2em] font-black" style="color: var(--green-caribbean);">Sesión Activa</span>
                    </div>
                    <p class="text-xs md:text-sm font-medium text-slate-200 truncate flex items-center">
                        <span class="md:hidden font-bold mr-1" style="color: var(--green-caribbean);">Admin:</span>
                        <span class="font-bold text-white tracking-tight truncate">{{ Str::limit(Auth::user()->name, 15) }}</span>
                    </p>
                </div>
            </div>
            <form action="{{ route('impersonate.stop') }}" method="POST" class="flex-shrink-0 ml-2">
                @csrf
                <button type="submit" class="group relative flex items-center justify-center gap-2 px-3 py-1.5 md:px-5 md:py-2 rounded-lg md:rounded-xl text-[10px] md:text-xs font-bold transition-all duration-300 shadow-lg active:scale-95" style="background: rgba(220, 38, 38, 0.1); color: #ef4444; border: 1px solid rgba(220, 38, 38, 0.3);">
                    <i class="fas fa-power-off opacity-70 group-hover:rotate-90 transition-transform duration-500"></i>
                    <span class="hidden md:inline">SALIR DEL MODO</span>
                    <span class="md:hidden">SALIR</span>
                </button>
            </form>
        </div>
        @endif
        <!-- Top Bar -->
        <header class="glass-header sticky top-0 z-40 px-4 md:px-6 py-3 transition-colors">
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
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 focus:border-transparent"
                            >
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </form>
                    </div>
                    
                    <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Panel')</h2>
                </div>
                <div class="flex items-center space-x-2 md:space-x-4">
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative ml-3">
                        <button id="user-menu-btn" class="flex items-center text-sm focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                            <div class="flex flex-col items-end mr-3 hidden md:flex">
                                <span class="font-bold text-gray-800 dark:text-white text-xs transition-colors">
                                    {{ Auth::user()->company ? Str::limit(Auth::user()->company->name, 20) : 'Sin Empresa' }}
                                </span>
                                <div class="flex items-center">
                                    <span class="text-gray-600 dark:text-gray-400 transition-colors">
                                        @php
                                            $names = explode(' ', Auth::user()->name);
                                            $firstName = $names[0] ?? '';
                                            $lastName = count($names) > 2 ? $names[2] : ($names[1] ?? ''); // Try to get 3rd word as surname, fallback to 2nd
                                        @endphp
                                        {{ ucfirst($firstName) }} {{ ucfirst($lastName) }}
                                    </span>
                                    @if(Auth::user()->isAdmin())
                                        <span class="ml-2 bg-gray-200 text-gray-800 text-[10px] px-1.5 py-0.5 rounded border border-gray-300 font-medium">Admin</span>
                                    @elseif(Auth::user()->isEditor())
                                        <span class="ml-2 bg-green-100 text-green-800 text-[10px] px-1.5 py-0.5 rounded border border-green-200 font-medium">Editor</span>
                                    @else
                                        <span class="ml-2 bg-gray-100 text-gray-800 text-[10px] px-1.5 py-0.5 rounded border border-gray-200 font-medium">Visor</span>
                                    @endif
                                </div>
                            </div>
                            <div class="h-9 w-9 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 ring-2 ring-white shadow-sm">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <i class="fas fa-chevron-down ml-2 text-gray-400 text-xs hidden md:block"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 py-1 z-50 origin-top-right transition-all duration-200">
                            <div class="px-4 py-3 border-b border-gray-50 dark:border-gray-700 md:hidden">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class="fas fa-user-circle mr-2 text-gray-400 w-4"></i> Mi Perfil
                            </a>
                            <a href="{{ route('profile.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class="fas fa-cog mr-2 text-gray-400 w-4"></i> Configuración
                            </a>

                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                            <!-- PWA Install Button -->
                            <button id="installBtn" class="hidden w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class="fas fa-download mr-2 text-gray-400 w-4"></i> Instalar App
                            </button>
                            
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-700 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2 text-red-400 w-4"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>



        <!-- Content Area -->
        <div class="max-w-full mx-auto p-2 md:p-6 w-full">


            <!-- Global Announcements -->
            @auth
            @endauth

            @yield('content')
        </div>

        </div>
        <!-- Footer -->
        <footer class="mt-auto py-6 px-4 md:px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 transition-colors">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            © {{ date('Y') }} <strong class="dark:text-white">ION</strong>. Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Desarrollado por <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-semibold transition duration-200">OutDeveloper</a>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Versión 1.1.0
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Initialize sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            
            // Only on desktop (md breakpoint and above)
            if (window.innerWidth >= 768) {
                // Get saved state from localStorage, default to collapsed
                const isCollapsed = localStorage.getItem('sidebarCollapsed') !== 'false';
                
                if (isCollapsed) {
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
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('md:ml-16');
                mainContent.classList.add('md:ml-64');
                sidebarTexts.forEach(text => text.classList.remove('hidden'));
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', 'false');
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

            // User Menu Dropdown
            const userBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-menu-dropdown');
            
            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                    // Close notifications if open
                    if (dropdown && !dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                    }
                });
                
                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });

                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: {!! json_encode(session('success')) !!},
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
                    text: {!! json_encode(session('error')) !!},
                    showConfirmButton: true,
                    confirmButtonText: 'Entendido',
                    customClass: {
                        confirmButton: 'bg-gradient-to-r from-indigo-600 to-purple-600'
                    }
                });
            @endif
        });
    </script>

    <!-- Notifications Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationsBtn = document.getElementById('notifications-btn');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            const notificationsList = document.getElementById('notifications-list');
            const notificationBadge = document.getElementById('notification-badge');
            const markAllReadBtn = document.getElementById('mark-all-read');

            // Toggle notifications dropdown
            if (notificationsBtn) {
                notificationsBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notificationsDropdown.classList.toggle('hidden');
                    if (!notificationsDropdown.classList.contains('hidden')) {
                        loadNotifications();
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!notificationsDropdown.contains(e.target) && !notificationsBtn.contains(e.target)) {
                        notificationsDropdown.classList.add('hidden');
                    }
                });

                notificationsDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Load notifications count on page load
            updateNotificationCount();

            // Update count every 30 seconds
            setInterval(updateNotificationCount, 30000);

            // Update notification count
            function updateNotificationCount() {
                const badge = document.getElementById('notification-badge');
                if (!badge) return;

                fetch('{{ route("notifications.unread-count") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading notification count:', error));
            }

            // Load recent notifications
            function loadNotifications() {
                notificationsList.innerHTML = '<div class="flex items-center justify-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i></div>';
                
                fetch('{{ route("notifications.recent") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            notificationsList.innerHTML = `
                                <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                                    <i class="fas fa-bell-slash text-4xl mb-2"></i>
                                    <p class="text-sm">No tienes notificaciones</p>
                                </div>
                            `;
                        } else {
                            notificationsList.innerHTML = data.map(notification => `
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition-colors notification-item" data-id="${notification.id}" data-url="${notification.data.action_url || '#'}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas ${getNotificationIcon(notification.type)} text-${getNotificationColor(notification.type)}-600 text-lg"></i>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                            <p class="text-xs text-gray-600 mt-1">${notification.message}</p>
                                            <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                                        </div>
                                    </div>
                                </div>
                            `).join('');

                            // Add click handlers to notification items
                            document.querySelectorAll('.notification-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    const notificationId = this.dataset.id;
                                    const url = this.dataset.url;
                                    markAsRead(notificationId, url);
                                });
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        notificationsList.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-8 text-red-500">
                                <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                                <p class="text-sm">Error al cargar notificaciones</p>
                            </div>
                        `;
                    });
            }

            // Mark notification as read
            function markAsRead(notificationId, redirectUrl) {
                fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationCount();
                        if (redirectUrl && redirectUrl !== '#') {
                            window.location.href = redirectUrl;
                        }
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
            }

            // Mark all as read
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    fetch('{{ route("notifications.mark-all-as-read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateNotificationCount();
                            loadNotifications();
                        }
                    })
                    .catch(error => console.error('Error marking all as read:', error));
                });
            }

            // Helper functions
            function getNotificationIcon(type) {
                const icons = {
                    'low_stock_alert': 'fa-exclamation-triangle',
                    'asset_assigned': 'fa-box-open',
                    'maintenance_reminder': 'fa-tools',
                    'default': 'fa-bell'
                };
                return icons[type] || icons.default;
            }

            function getNotificationColor(type) {
                const colors = {
                    'low_stock_alert': 'red',
                    'asset_assigned': 'blue',
                    'maintenance_reminder': 'yellow',
                    'default': 'gray'
                };
                return colors[type] || colors.default;
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diff = Math.floor((now - date) / 1000); // seconds

                if (diff < 60) return 'Hace un momento';
                if (diff < 3600) return `Hace ${Math.floor(diff / 60)} minutos`;
                if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} horas`;
                if (diff < 604800) return `Hace ${Math.floor(diff / 86400)} días`;
                
                return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
            }
        });
    </script>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-24 right-6 flex flex-col gap-3 z-50">
        <!-- Support Button -->
        <a href="{{ route('support.index') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white rounded-full w-14 h-14 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group relative"
           title="Soporte Técnico">
            <i class="fas fa-headset text-xl"></i>
            <span class="absolute right-full mr-3 bg-gray-800 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                Soporte Técnico
            </span>
        </a>
        
        <!-- WhatsApp Button -->
        <a href="https://wa.me/573145781261?text=Hola%20equipo%20de%20OutDeveloper,%20necesito%20soporte%20técnico%20con%20ION%20Inventory.%20Mi%20consulta%20es:%20" 
           target="_blank" 
           class="bg-green-500 hover:bg-green-600 text-white rounded-full w-14 h-14 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group relative animate-pulse hover:animate-none"
           title="Soporte por WhatsApp">
            <i class="fab fa-whatsapp text-2xl"></i>
            <span class="absolute right-full mr-3 bg-green-600 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none shadow-lg">
                💬 Soporte ION Inventory
            </span>
        </a>
    </div>

    @include('components.onboarding-tour')
@stack("scripts")
</body>
</html>
