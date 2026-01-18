@if(!Auth::user()->onboarding_completed)
@php
    $locCount = \App\Models\Location::where('company_id', Auth::user()->company_id)->count();
    $catCount = \App\Models\Category::where('company_id', Auth::user()->company_id)->count();
    $subCount = \App\Models\Subcategory::whereHas('category', function($q) {
        $q->where('company_id', Auth::user()->company_id);
    })->count();
    $supCount = \App\Models\Supplier::where('company_id', Auth::user()->company_id)->count();
    $astCount = \App\Models\Asset::where('company_id', Auth::user()->company_id)->count();
    $usrCount = \App\Models\User::where('company_id', Auth::user()->company_id)->count();
    $companyName = Auth::user()->company ? Auth::user()->company->name : 'tu empresa';
@endphp

<div id="onboarding-module" class="fixed inset-0 z-[10000] overflow-hidden pointer-events-none font-sans hidden">
    <!-- Premium Glass Overlay -->
    <svg id="tour-spotlight" class="absolute inset-0 w-full h-full pointer-events-auto transition-opacity duration-700 opacity-0">
        <defs>
            <mask id="spotlight-mask">
                <rect x="0" y="0" width="100%" height="100%" fill="white" />
                <rect id="spotlight-hole" x="0" y="0" width="0" height="0" rx="16" fill="black" />
            </mask>
            <filter id="glow">
                <feGaussianBlur stdDeviation="4" result="blur" />
                <feComposite in="SourceGraphic" in2="blur" operator="over" />
            </filter>
        </defs>
        <rect x="0" y="0" width="100%" height="100%" fill="rgba(2, 6, 23, 0.85)" mask="url(#spotlight-mask)" class="backdrop-blur-[3px]" />
        <rect id="spotlight-border" x="0" y="0" width="0" height="0" rx="16" fill="none" stroke="rgba(99, 102, 241, 0.5)" stroke-width="2" mask="url(#spotlight-mask)" filter="url(#glow)" />
    </svg>

    <!-- Welcome Hero Modal (Step 1) -->
    <div id="tour-welcome-modal" class="absolute inset-0 flex items-center justify-center p-4 pointer-events-auto z-[10002] hidden opacity-0 transition-all duration-700 scale-95">
        <div class="relative w-full max-w-lg">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 rounded-[2.5rem] blur-2xl opacity-30 animate-pulse"></div>
            <div class="relative bg-slate-900 border border-white/10 rounded-[2.2rem] shadow-2xl overflow-hidden p-8 sm:p-10 text-center">
                <div class="mb-8 relative inline-block">
                    <div class="w-20 h-20 bg-[#5483B3] rounded-2xl flex items-center justify-center shadow-2xl mx-auto transform -rotate-12 border-4 border-white/5">
                        <i class="fas fa-rocket text-white text-3xl animate-bounce"></i>
                    </div>
                </div>
                
                <h4 class="text-indigo-400 font-black text-xs uppercase tracking-[0.3em] mb-4">¡BIENVENIDOS A ION!</h4>
                <h2 class="text-3xl sm:text-4xl font-black text-white mb-6 leading-tight">
                    Hola, <span class="bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">{{ $companyName }}</span>
                </h2>
                
                <p class="text-gray-400 text-sm sm:text-base leading-relaxed mb-10">
                    Nos complace darte la bienvenida. Hemos creado un recorrido inteligente para que configures ION en minutos y obtengas un control total y preciso de tus activos desde el primer momento.
                </p>

                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <button onclick="handleStepAction()" class="flex-1 py-4 bg-white text-slate-950 rounded-2xl font-black shadow-xl hover:bg-slate-100 transform active:scale-95 transition-all text-sm">
                        Comenzar Recorrido <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button onclick="skipTour()" class="flex-1 py-4 bg-slate-800 text-white rounded-2xl font-bold shadow-xl hover:bg-slate-700 transform active:scale-95 transition-all text-sm">
                        Ir al Panel <i class="fas fa-times ml-2 opacity-50"></i>
                    </button>
                </div>
                

            </div>
        </div>
    </div>

    <!-- Premium Tooltip Card (Mobile Optimized) -->
    <div id="tour-tooltip" class="absolute z-[10001] w-[calc(100%-1.5rem)] sm:w-[350px] pointer-events-auto transform transition-all duration-500 opacity-0 -translate-y-4">
        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-[1.2rem] sm:rounded-[2rem] blur-xl opacity-20"></div>
        <div class="relative bg-slate-900/95 backdrop-blur-2xl rounded-[1.2rem] sm:rounded-[1.8rem] shadow-[0_30px_100px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden">
            <div class="h-1.5 w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
                <div id="tour-progress" class="h-full bg-white/40 transition-all duration-700" style="width: 0%"></div>
            </div>
            
            <div class="p-3 sm:p-6">
                <!-- Header -->
                <div class="flex items-center space-x-2 sm:space-x-3 mb-1 sm:mb-4">
                    <div class="w-6 h-6 sm:w-10 sm:h-10 bg-[#5483B3] rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg transform -rotate-3 shrink-0">
                        <i class="fas fa-magic text-white text-[10px] sm:text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-[7px] font-black text-indigo-400 uppercase tracking-widest truncate">ION Guía</h4>
                        <h3 id="tour-title" class="text-xs sm:text-base font-bold text-white leading-tight truncate">Configurando...</h3>
                    </div>
                    <button onclick="skipTour()" class="ml-auto text-gray-500 hover:text-white transition-colors p-1 -mr-1">
                        <i class="fas fa-times text-sm sm:text-lg"></i>
                    </button>
                </div>

                <!-- Text Area -->
                <div id="tour-content" class="min-h-0 sm:min-h-[60px]">
                    <p id="tour-text" class="text-gray-300 text-[10px] sm:text-xs leading-relaxed">...</p>
                </div>

                <!-- Controls -->
                <div class="mt-2 sm:mt-6 flex flex-col space-y-1 sm:space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span id="tour-step-label" class="text-[8px] font-bold text-gray-500 uppercase">Paso 1/5</span>
                            <button onclick="skipTour()" class="sm:hidden text-[8px] text-gray-500 hover:text-gray-300 transition-colors uppercase font-bold text-left">
                                Saltar
                            </button>
                        </div>
                        <div class="flex space-x-1.5 sm:space-x-2">
                            <button id="tour-prev-btn" onclick="handlePrevStep()" class="hidden px-2 sm:px-4 py-1 sm:py-2 bg-slate-800 text-white rounded-lg sm:rounded-xl text-[9px] sm:text-[11px] font-bold hover:bg-slate-700 transition-all flex items-center shadow-lg border border-white/5">
                                <i class="fas fa-chevron-left mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Atrás</span>
                            </button>
                            <button id="tour-next-btn" onclick="handleStepAction()" class="px-3 sm:px-5 py-1 sm:py-2 bg-white text-slate-950 rounded-lg sm:rounded-xl text-[9px] sm:text-[11px] font-black hover:bg-slate-100 transition-all flex items-center shadow-lg">
                                <span id="btn-text">Entendido</span>
                                <i class="fas fa-chevron-right ml-1 sm:ml-2 text-[8px] sm:text-[10px]"></i>
                            </button>
                            <button id="btn-wait" class="hidden px-3 sm:px-5 py-1 sm:py-2 bg-[#5483B3] text-white rounded-lg sm:rounded-xl text-[9px] sm:text-[11px] font-black cursor-default opacity-80">
                                Esperando...
                            </button>
                        </div>
                    </div>
                    <button onclick="skipTour()" class="hidden sm:block text-[10px] text-gray-500 hover:text-gray-300 transition-colors uppercase tracking-[0.2em] font-bold text-center py-2">
                        Saltar Tour
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const counts = {
        locations: {{ $locCount }},
        categories: {{ $catCount }},
        subcategories: {{ $subCount }},
        suppliers: {{ $supCount }},
        assets: {{ $astCount }},
        users: {{ $usrCount }}
    };

    const steps = [
        {
            id: 'welcome',
            title: "¡Bienvenidos!",
            text: "Te guiaremos por ION.",
            target: null,
            action: 'next'
        },
        // LOCATIONS
        {
            id: 'loc_sidebar',
            title: "1. Ubicaciones",
            text: "Define las sedes, bodegas u oficinas donde estarán tus activos.",
            target: '#tour-locations',
            action: 'sidebar'
        },
        {
            id: 'loc_add',
            title: "Nueva Ubicación",
            text: "Crea tu primera sede para empezar a organizar.",
            target: 'a[href*="/locations/create"]',
            action: 'click'
        },
        {
            id: 'loc_fill',
            title: "Crea tu Ubicación",
            text: "Llena los datos y guarda. La guía te espera en la esquina.",
            target: '#tour-location-form',
            action: 'wait'
        },
        // CATEGORIES
        {
            id: 'cat_sidebar',
            title: "2. Categorías",
            text: "Agrupa tus equipos por tipos generales (ej: Maquinaria, TI, Mobiliario).",
            target: '#tour-categories',
            action: 'sidebar'
        },
        {
            id: 'cat_add',
            title: "Nueva Categoría",
            text: "Crea la categoría principal de tus activos.",
            target: 'a[href*="/categories/create"]',
            action: 'click'
        },
        {
            id: 'cat_fill',
            title: "Crea la Categoría",
            text: "Define el nombre y guarda.",
            target: '#tour-category-form',
            action: 'wait'
        },
        // SUBCATEGORIES
        {
            id: 'sub_sidebar',
            title: "3. Subcategorías",
            text: "Sé más específico dentro de cada categoría (ej: Laptops dentro de TI).",
            target: '#tour-subcategories',
            action: 'sidebar'
        },
        {
            id: 'sub_add',
            title: "Nueva Subcategoría",
            text: "Agrega el detalle a tu categoría principal.",
            target: 'a[href*="/subcategories/create"]',
            action: 'click'
        },
        {
            id: 'sub_fill',
            title: "Crea la Subcategoría",
            text: "Vincula y guarda.",
            target: '#tour-subcategory-form',
            action: 'wait'
        },
        // SUPPLIERS
        {
            id: 'sup_sidebar',
            title: "4. Proveedores",
            text: "Gestiona quiénes suministran tus equipos para control de garantías y compras.",
            target: '#tour-suppliers',
            action: 'sidebar'
        },
        {
            id: 'sup_add',
            title: "Nuevo Proveedor",
            text: "Registra tu primer proveedor oficial.",
            target: 'a[href*="/suppliers/create"]',
            action: 'click'
        },
        {
            id: 'sup_fill',
            title: "Crea el Proveedor",
            text: "Ingresa el nombre, NIT y datos de contacto.",
            target: '#tour-supplier-form',
            action: 'wait'
        },
        // ASSETS
        {
            id: 'ast_sidebar',
            title: "5. Activos",
            text: "El corazón del sistema. Aquí registras cada equipo con su foto, QR y ficha técnica.",
            target: '#tour-add-asset',
            action: 'sidebar'
        },
        {
            id: 'ast_add',
            title: "Nuevo Activo",
            text: "Registra un activo con su QR único e histórico.",
            target: 'a[href*="/assets/create"]',
            action: 'click'
        },
        {
            id: 'ast_fill',
            title: "Crea el Activo",
            text: "Completa el formulario principal. ¡Ya casi terminas!",
            target: '#tour-asset-form',
            action: 'wait'
        },
        // NEW MODULES
        {
            id: 'mov_sidebar',
            title: "Movimientos",
            text: "Historial de traslados: mira quién ha tenido un activo, desde qué fecha y cuándo regresó.",
            target: '#tour-movements',
            action: 'sidebar'
        },
        {
            id: 'emp_sidebar',
            title: "Empleados",
            text: "Gestiona la base de datos de las personas a quienes les asignarás la responsabilidad de los activos.",
            target: '#tour-employees',
            action: 'sidebar'
        },
        {
            id: 'mnt_sidebar',
            title: "Mantenimientos",
            text: "Lleva el control de reparaciones preventivas y correctivas. Agenda fechas para evitar fallos.",
            target: '#tour-maintenance',
            action: 'sidebar'
        },
        {
            id: 'usr_sidebar',
            title: "Usuarios",
            text: "Administra quién puede entrar al sistema (vendedores, técnicos, admin) y sus permisos técnicos.",
            target: '#tour-users',
            action: 'sidebar'
        },
        {
            id: 'bck_sidebar',
            title: "Respaldos",
            text: "Tu información segura: descarga una copia completa de toda tu base de datos en un clic.",
            target: '#tour-backups',
            action: 'sidebar'
        },
        // REPORTS
        {
            id: 'final_reports',
            title: "Reportes Inteligentes",
            text: "Genera PDF y Excel de toda tu operación para auditorías y control gerencial.",
            target: '#tour-reports',
            action: 'finish'
        }
    ];

    let activeStepIdx = 0;
    let syncRunning = false;

    function getAutoStep() {
        let savedStepId = localStorage.getItem('ion_tour_active_id');
        const path = window.location.pathname.replace(/\/$/, '');

        // Primary setup flow logic
        if (counts.assets > 0 && !path.includes('/assets') && !path.includes('/create')) return steps.findIndex(s => s.id === 'final_reports');

        if (path.includes('/locations/create')) return steps.findIndex(s => s.id === 'loc_fill');
        if (path.includes('/categories/create')) return steps.findIndex(s => s.id === 'cat_fill');
        if (path.includes('/subcategories/create')) return steps.findIndex(s => s.id === 'sub_fill');
        if (path.includes('/suppliers/create')) return steps.findIndex(s => s.id === 'sup_fill');
        if (path.includes('/assets/create')) return steps.findIndex(s => s.id === 'ast_fill');
        
        if (path.endsWith('/locations')) return (counts.locations > 0) ? steps.findIndex(s => s.id === 'cat_sidebar') : steps.findIndex(s => s.id === 'loc_add');
        if (path.endsWith('/categories')) return (counts.categories > 0) ? steps.findIndex(s => s.id === 'sub_sidebar') : steps.findIndex(s => s.id === 'cat_add');
        if (path.endsWith('/subcategories')) return (counts.subcategories > 0) ? steps.findIndex(s => s.id === 'sup_sidebar') : steps.findIndex(s => s.id === 'sub_add');
        if (path.endsWith('/suppliers')) return (counts.suppliers > 0) ? steps.findIndex(s => s.id === 'ast_sidebar') : steps.findIndex(s => s.id === 'sup_add');
        if (path.endsWith('/assets')) return (counts.assets > 0) ? steps.findIndex(s => s.id === 'final_reports') : steps.findIndex(s => s.id === 'ast_add');
        
        if (path.includes('/reports')) return steps.findIndex(s => s.id === 'final_reports');

        if (savedStepId) {
            let idx = steps.findIndex(s => s.id === savedStepId);
            return idx !== -1 ? idx : 0;
        }

        return 0;
    }

    function syncSpotlight() {
        if (!syncRunning) return;
        
        const step = steps[activeStepIdx];
        if (!step) return;

        const hole = document.getElementById('spotlight-hole');
        const border = document.getElementById('spotlight-border');
        const tooltip = document.getElementById('tour-tooltip');
        const targetEl = step.target ? document.querySelector(step.target) : null;
        const isMobile = window.innerWidth < 768;

        if (targetEl && (targetEl.offsetWidth > 0 || targetEl.offsetHeight > 0)) {
            const rect = targetEl.getBoundingClientRect();
            let padding = step.id.includes('_fill') ? 20 : 10;
            
            hole.setAttribute('x', rect.left - padding);
            hole.setAttribute('y', rect.top - padding);
            hole.setAttribute('width', rect.width + (padding*2));
            hole.setAttribute('height', rect.height + (padding*2));
            
            border.setAttribute('x', rect.left - padding);
            border.setAttribute('y', rect.top - padding);
            border.setAttribute('width', rect.width + (padding*2));
            border.setAttribute('height', rect.height + (padding*2));

            if (!step.id.includes('_fill') && !isMobile) {
                let left = rect.right + 40;
                let top = rect.top + (rect.height / 2) - (tooltip.offsetHeight / 2);
                if (left + tooltip.offsetWidth > window.innerWidth - 20) {
                    left = rect.left - tooltip.offsetWidth - 40;
                }
                tooltip.style.left = Math.max(10, left) + 'px';
                tooltip.style.top = Math.max(10, Math.min(top, window.innerHeight - tooltip.offsetHeight - 10)) + 'px';
            }
        } else {
            hole.setAttribute('width', 0);
            border.setAttribute('width', 0);
        }

        requestAnimationFrame(syncSpotlight);
    }

    function startTour() {
        activeStepIdx = getAutoStep();
        const module = document.getElementById('onboarding-module');
        if (!module) return;
        module.classList.remove('hidden');
        syncRunning = true;
        
        setTimeout(() => {
            document.getElementById('tour-spotlight').classList.remove('opacity-0');
            renderStep();
            syncSpotlight();
        }, 300);
    }

    function renderStep() {
        const step = steps[activeStepIdx];
        if (!step) return;

        localStorage.setItem('ion_tour_active_id', step.id);

        const welcomeModal = document.getElementById('tour-welcome-modal');
        const tooltip = document.getElementById('tour-tooltip');
        const nextBtn = document.getElementById('tour-next-btn');
        const prevBtn = document.getElementById('tour-prev-btn');
        const waitBtn = document.getElementById('btn-wait');
        const btnText = document.getElementById('btn-text');

        // Handle Welcome Modal Interaction
        if (step.id === 'welcome' && !localStorage.getItem('ion_welcome_dismissed')) {
            welcomeModal.classList.remove('hidden');
            setTimeout(() => {
                welcomeModal.classList.add('opacity-100', 'scale-100');
                welcomeModal.classList.remove('scale-95');
            }, 100);
            tooltip.classList.add('hidden');
            return;
        } else {
            welcomeModal.classList.remove('opacity-100', 'scale-100');
            welcomeModal.classList.add('hidden');
            tooltip.classList.remove('hidden');
        }

        document.getElementById('tour-title').innerText = step.title;
        document.getElementById('tour-text').innerText = step.text;
        document.getElementById('tour-progress').style.width = ((activeStepIdx + 1) / steps.length * 100) + '%';
        document.getElementById('tour-step-label').innerText = `Paso ${activeStepIdx + 1}/${steps.length}`;

        if (activeStepIdx > 0) prevBtn.classList.remove('hidden');
        else prevBtn.classList.add('hidden');

        if (step.action === 'wait') {
            nextBtn.classList.add('hidden');
            waitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            waitBtn.classList.add('hidden');
            
            if (step.action === 'finish') btnText.innerText = 'Terminar';
            else if (step.action === 'sidebar' || step.action === 'click') btnText.innerText = 'Ir ahora';
            else btnText.innerText = 'Siguiente';
        }

        const isMobile = window.innerWidth < 768;
        tooltip.style.transition = 'opacity 0.5s, transform 0.5s';

        const targetEl = step.target ? document.querySelector(step.target) : null;

        if (targetEl && (targetEl.offsetWidth > 0 || targetEl.offsetHeight > 0)) {
            document.getElementById('tour-spotlight').style.pointerEvents = 'none';

            if (step.id.includes('_fill')) {
                if (isMobile) {
                    tooltip.style.bottom = '1.5rem';
                    tooltip.style.left = '50%';
                    tooltip.style.top = 'auto';
                    tooltip.style.right = 'auto';
                    tooltip.style.transform = 'translateX(-50%)';
                } else {
                    tooltip.style.bottom = '2rem';
                    tooltip.style.right = '2rem';
                    tooltip.style.left = 'auto';
                    tooltip.style.top = 'auto';
                    tooltip.style.transform = 'none';
                }
            } else if (isMobile) {
                tooltip.style.bottom = '1.5rem';
                tooltip.style.left = '50%';
                tooltip.style.top = 'auto';
                tooltip.style.right = 'auto';
                tooltip.style.transform = 'translateX(-50%)';
            } else {
                tooltip.style.bottom = 'auto';
                tooltip.style.right = 'auto';
                tooltip.style.transform = 'none';
            }
            tooltip.classList.add('opacity-100');
        } else {
            document.getElementById('tour-spotlight').style.pointerEvents = 'auto';
            tooltip.style.left = '50%';
            tooltip.style.top = isMobile ? 'auto' : '50%';
            tooltip.style.bottom = isMobile ? '2rem' : 'auto';
            tooltip.style.transform = isMobile ? 'translateX(-50%)' : 'translate(-50%, -50%)';
            tooltip.classList.add('opacity-100');

            if (step.action === 'sidebar' && isMobile) {
                btnText.innerText = 'Abrir Menú';
            }
        }
    }

    function handleStepAction() {
        const step = steps[activeStepIdx];
        
        // Mark welcome modal as dismissed once "Comenzar" is clicked
        if (step.id === 'welcome') {
            localStorage.setItem('ion_welcome_dismissed', 'true');
        }

        const targetEl = step.target ? document.querySelector(step.target) : null;
        const isMobile = window.innerWidth < 768;

        if (step.action === 'finish') {
            finishTour();
            return;
        }

        if (step.action === 'sidebar' && isMobile) {
            const sidebar = document.getElementById('sidebar');
            const sidebarHidden = sidebar && (window.getComputedStyle(sidebar).transform.includes('-100%') || sidebar.classList.contains('-translate-x-full'));
            
            if (sidebarHidden && typeof toggleMobileSidebar === 'function') {
                toggleMobileSidebar();
                setTimeout(renderStep, 400);
                return;
            }
        }

        if ((step.action === 'click' || step.action === 'sidebar') && targetEl) {
            if (activeStepIdx + 1 < steps.length) {
                localStorage.setItem('ion_tour_active_id', steps[activeStepIdx + 1].id);
            }
            if (targetEl.tagName === 'A' && targetEl.href) {
                window.location.href = targetEl.href;
            } else {
                targetEl.click();
            }
            return;
        }

        activeStepIdx++;
        renderStep();
    }

    function handlePrevStep() {
        if (activeStepIdx > 0) {
            activeStepIdx--;
            renderStep();
        }
    }

    function finishTour() {
        syncRunning = false;
        localStorage.removeItem('ion_tour_active_id');
        localStorage.removeItem('ion_welcome_dismissed');
        document.getElementById('onboarding-module').remove();
        fetch('{{ route("onboarding.complete") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    }

    function skipTour() { finishTour(); }

    window.addEventListener('resize', renderStep);
    document.addEventListener('DOMContentLoaded', startTour);
</script>
@endif
