@extends('layouts.app')

@section('page-title', 'Detalles del Activo')

@php
    $imageUrl = $asset->image 
        ? (\Illuminate\Support\Str::startsWith($asset->image, 'http') ? $asset->image : asset('storage/' . $asset->image))
        : null;
@endphp

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 group">
        <div class="flex items-center gap-5">
            <a href="{{ route('assets.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 text-gray-400 hover:text-[#5483B3] hover:shadow-md transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-none">{{ $asset->name }}</h2>
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 rounded-lg text-xs font-mono font-bold">#{{ $asset->custom_id }}</span>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                        <i class="fas fa-map-marker-alt mr-1.5 text-red-400"></i> {{ $asset->location->name }}
                    </p>
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                        <i class="fas fa-tags mr-1.5 text-indigo-400"></i> {{ $asset->subcategory?->category?->name }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('assets.qr', $asset->id) }}" target="_blank" class="px-5 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-qrcode text-indigo-500"></i> Generar QR
            </a>
            
            @if(Auth::user()->hasPermission('edit_assets'))
                <div class="flex items-center gap-3 bg-gray-100 dark:bg-gray-900/50 p-1.5 rounded-2xl border border-gray-200 dark:border-gray-800">
                    @if(auth()->user()->company->hasModule('transfers'))
                        <button onclick="document.getElementById('transferModal').classList.remove('hidden')" class="px-5 py-2.5 bg-[#5483B3] hover:bg-[#052659] text-white font-black rounded-xl transition-all shadow-lg shadow-[#5483B3]/20 flex items-center gap-2 text-sm uppercase tracking-wide">
                            <i class="fas fa-exchange-alt"></i> Trasladar
                        </button>
                    @endif
                    <a href="{{ route('assets.edit', $asset->id) }}" class="p-2.5 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-[#5483B3] rounded-xl transition-all hover:shadow-sm shadow-[#5483B3]/10">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Sidebar: Visuals & Status -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Asset Card (Image + Status) -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden group/card shadow-xl shadow-gray-200/20">
                <div class="relative aspect-square bg-gray-50 dark:bg-gray-900/40 flex items-center justify-center overflow-hidden">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $asset->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/card:scale-110">
                        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    @else
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-7xl text-gray-200 dark:text-gray-800 mb-4 transition-transform group-hover/card:rotate-12 group-hover/card:scale-110 duration-500"></i>
                            <p class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-[0.2em]">Sin Imagen Cargada</p>
                        </div>
                    @endif

                    <div class="absolute top-6 right-6">
                        @php
                            $statusColors = [
                                'active' => 'bg-emerald-500 shadow-emerald-500/40',
                                'maintenance' => 'bg-amber-500 shadow-amber-500/40',
                                'archived' => 'bg-gray-500 shadow-gray-500/40',
                                'lost' => 'bg-red-500 shadow-red-500/40',
                            ];
                        @endphp
                        <span class="flex items-center gap-2 {{ $statusColors[$asset->status] ?? 'bg-indigo-500' }} text-white px-4 py-2 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            {{ ucfirst($asset->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-700">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Precio de Compra</p>
                                <p class="text-xl font-black text-gray-900 dark:text-white">${{ number_format($asset->value, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-[#5483B3]">
                                <i class="fas fa-tag"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Cantidad</p>
                                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $asset->quantity ?? 1 }} {{ $asset->unit ?? 'Und' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">F. Compra</p>
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Info: Model & Specs -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <i class="fas fa-microchip text-indigo-400"></i> Especificaciones Téc.
                </h4>
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Modelo / Referencia</p>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $asset->model ?? 'Genérico / No especificado' }}</p>
                    </div>
                    @if($asset->serial_number)
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Número de Serial</p>
                        <p class="text-xs font-mono font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded inline-block">{{ $asset->serial_number }}</p>
                    </div>
                    @endif
                    @if($asset->specifications)
                    <div class="p-4 bg-indigo-50/30 dark:bg-indigo-900/10 rounded-2xl border border-[#5483B3]/100/50 dark:border-[#5483B3]/900/30">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Comentarios Técnicos</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed italic">"{{ $asset->specifications }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Content: Details, Assignment, History -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Details Grid -->
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-700/50">
                    <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest flex items-center">
                        <i class="fas fa-list-ul text-indigo-500 mr-3"></i> Información Detallada
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-red-500 text-sm">
                            <i class="fas fa-id-card-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Placa Municipio</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $asset->municipality_plate ?? 'Sin asignar' }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500 text-sm">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Subcategoría</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $asset->subcategory?->name }}</p>
                        </div>
                    </div>

                    <!-- Dynamic Custom Fields -->
                    @php
                        $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
                        $customAttributes = $asset->custom_attributes ?? [];
                    @endphp
                    @foreach($customFields as $field)
                        @if(\App\Helpers\FieldHelper::isVisible($field->name) && !empty($customAttributes[$field->name]))
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-sm">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">{{ $field->label }}</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $customAttributes[$field->name] }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Financial Section Integration --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                @include('assets.partials.financial-section')
            </div>

            <!-- Assignment Management Section -->
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest flex items-center">
                        <i class="fas fa-user-check text-emerald-500 mr-3"></i> Estado de Asignación
                    </h3>
                    
                    @if(Auth::user()->hasPermission('create_movements'))
                        @if($asset->isAssigned())
                            <button onclick="document.getElementById('returnModal').classList.remove('hidden')" class="px-4 py-2 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-100 transition-all flex items-center gap-2">
                                <i class="fas fa-undo"></i> Devolver
                            </button>
                        @elseif($asset->status == 'active')
                            <a href="{{ route('assets.assign', $asset->id) }}" class="px-5 py-2.5 bg-[#5483B3] hover:bg-[#052659] text-white font-black rounded-xl transition-all shadow-md shadow-[#5483B3]/20 flex items-center gap-2 text-[10px] uppercase tracking-widest">
                                <i class="fas fa-plus"></i> Asignar Recurso
                            </a>
                        @endif
                    @endif
                </div>

                <div class="p-8">
                    @if($asset->isAssigned())
                        @php $assignment = $asset->currentAssignment; @endphp
                        <div class="flex flex-col md:flex-row items-center gap-8 bg-emerald-50/50 dark:bg-emerald-900/10 p-6 rounded-3xl border border-emerald-100/50 dark:border-emerald-900/30">
                            <div class="relative">
                                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center text-2xl font-black text-emerald-600 border border-emerald-100 dark:border-gray-700">
                                    {{ substr($assignment->employee->first_name, 0, 1) }}{{ substr($assignment->employee->last_name, 0, 1) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] border-2 border-white dark:border-gray-800">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <p class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest mb-1">Colaborador Actual</p>
                                <a href="{{ route('employees.show', $assignment->employee->id) }}" class="text-xl font-black text-gray-900 dark:text-white hover:text-[#5483B3] transition-colors">{{ $assignment->employee->full_name }}</a>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $assignment->employee->position ?? 'Sin cargo' }} • {{ $assignment->employee->department ?? 'General' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="bg-white/80 dark:bg-gray-800 px-4 py-3 rounded-2xl shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Asignado el</p>
                                    <p class="text-xs font-black text-gray-800 dark:text-white">{{ $assignment->assigned_date->format('d/m/y') }}</p>
                                </div>
                                <div class="bg-white/80 dark:bg-gray-800 px-4 py-3 rounded-2xl shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Devolución</p>
                                    <p class="text-xs font-black {{ $assignment->expected_return_date && $assignment->expected_return_date->isPast() ? 'text-red-500' : 'text-gray-800 dark:text-white' }}">
                                        {{ $assignment->expected_return_date ? $assignment->expected_return_date->format('d/m/y') : 'Indefinida' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50/50 dark:bg-gray-900/20 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-300 dark:text-gray-700 mx-auto mb-4 border border-gray-100 dark:border-gray-800">
                                <i class="fas fa-person-circle-plus text-2xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Activo en Stock</h4>
                            <p class="text-xs text-gray-500 mt-1">Este recurso está disponible para nuevas asignaciones.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Unified Traceability Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700">
                    <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest flex items-center">
                        <i class="fas fa-history text-indigo-500 mr-3"></i> Trazabilidad del Activo
                    </h3>
                </div>
                <div class="p-8">
                    @if($timeline->count() > 0)
                        <div class="relative pl-8 space-y-8 before:absolute before:inset-y-0 before:left-0 before:w-0.5 before:bg-gray-100 dark:before:bg-gray-700">
                            @foreach($timeline as $event)
                                <div class="relative group/item">
                                    <div class="absolute -left-[41px] top-1 w-5 h-5 rounded-full bg-white dark:bg-gray-800 border-4 border-{{ $event->color }}-500 shadow-sm z-10 transition-transform group-hover/item:scale-125"></div>
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 bg-gray-50/30 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-800 hover:border-{{ $event->color }}-500/30 transition-all">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="text-[9px] font-black text-{{ $event->color }}-600 uppercase tracking-widest bg-{{ $event->color }}-50 dark:bg-{{ $event->color }}-900/20 px-2 py-0.5 rounded-lg border border-{{ $event->color }}-100/50">
                                                    {{ $event->title }}
                                                </span>
                                                <span class="text-[10px] font-bold text-gray-400">{{ $event->date->format('d M, Y • H:i') }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed">{{ $event->description }}</p>
                                            
                                            @if($event->reason || $event->notes)
                                                <div class="mt-3 p-4 bg-white dark:bg-gray-800 rounded-2xl text-xs text-gray-600 dark:text-gray-400 italic border-l-4 border-{{ $event->color }}-500 shadow-sm">
                                                    <i class="fas fa-quote-left text-[10px] text-{{ $event->color }}-300 mb-1 block"></i>
                                                    "{{ $event->reason ?? $event->notes }}"
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right flex flex-col items-end gap-2 shrink-0">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-400">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span class="text-[9px] font-black text-gray-500 uppercase tracking-wider">{{ $event->user }}</span>
                                            </div>
                                            @if(isset($event->amount) && $event->amount > 0)
                                                <span class="text-sm font-black text-{{ $event->color }}-600">${{ number_format($event->amount, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-400 text-sm font-bold italic italic">No hay registros previos en el historial de este activo.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@if($asset->isAssigned())
    <!-- Return Modal -->
    <div id="returnModal" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden transform transition-all">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-700/50">
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest flex items-center">
                    <i class="fas fa-undo text-amber-500 mr-3"></i> Devolución
                </h3>
                <button onclick="document.getElementById('returnModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-900 text-gray-400 hover:text-red-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('assignments.return', $assignment->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-2xl border border-amber-100 dark:border-amber-900/30 flex items-start gap-3">
                    <i class="fas fa-info-circle text-amber-500 mt-1"></i>
                    <p class="text-xs text-amber-700 dark:text-amber-400 font-medium leading-relaxed italic">
                        Confirmando la devolución de <strong>{{ $asset->name }}</strong> actualmente asignado a <strong>{{ $assignment->employee->full_name }}</strong>.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Fecha de Devolución</label>
                    <input type="date" name="return_date" value="{{ date('Y-m-d') }}" required 
                        class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 font-black text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Estado del Activo / Notas</label>
                    <textarea name="notes" rows="4" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-4 px-5 text-sm font-medium focus:ring-2 focus:ring-amber-500 transition-all" placeholder="Escriba el estado en el que se recibe el activo..."></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="flex-1 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl uppercase tracking-[0.2em] text-[10px] hover:bg-gray-200 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-amber-500 text-white font-black rounded-2xl uppercase tracking-[0.2em] text-[10px] shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all">Confirmar Devolución</button>
                </div>
            </form>
        </div>
    </div>
@endif

@if(auth()->user()->company->hasModule('transfers'))
    <!-- Transfer Modal -->
    <div id="transferModal" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-700/50">
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest flex items-center">
                    <i class="fas fa-exchange-alt text-indigo-500 mr-3"></i> Trasladar Activo
                </h3>
                <button onclick="document.getElementById('transferModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-900 text-gray-400 hover:text-red-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('asset-movements.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                
                <div class="flex items-center gap-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-[#5483B3]/100 dark:border-[#5483B3]/900/30">
                    <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-indigo-500 shadow-sm">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-[#5483B3] dark:text-indigo-400 uppercase tracking-widest">Ubicación Actual</p>
                        <p class="text-sm font-black text-gray-900 dark:text-white leading-none mt-0.5">{{ $asset->location->name }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Destino del Traslado</label>
                    <select name="to_location_id" required class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-3 px-4 font-black text-gray-800 dark:text-white focus:ring-2 focus:ring-[#5483B3] transition-all">
                        <option value="" disabled selected>Seleccione la nueva ubicación...</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Justificación del Movimiento</label>
                    <textarea name="reason" rows="4" required class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-4 px-5 text-sm font-medium focus:ring-2 focus:ring-[#5483B3] transition-all" placeholder="Describa por qué se realiza este cambio de ubicación..."></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('transferModal').classList.add('hidden')" class="flex-1 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl uppercase tracking-[0.2em] text-[10px] hover:bg-gray-200 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-[#5483B3] text-white font-black rounded-2xl uppercase tracking-[0.2em] text-[10px] shadow-lg shadow-[#5483B3]/20 hover:bg-[#052659] transition-all">Confirmar Traslado</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
