@extends('layouts.superadmin')

@section('page-title', 'Ticket ' . $ticket->ticket_number)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-ticket-alt mr-2 text-blue-600"></i>{{ $ticket->ticket_number }}
                </h2>
                <p class="text-xl text-gray-700">{{ $ticket->subject }}</p>
                <div class="flex gap-4 mt-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($ticket->priority == 'urgent') bg-red-100 text-red-800
                        @elseif($ticket->priority == 'high') bg-orange-100 text-orange-800
                        @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-blue-lightest text-blue-dark
                        @endif">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ ucfirst($ticket->priority) }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($ticket->status == 'open') bg-blue-100 text-blue-800
                        @elseif($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status == 'resolved') bg-blue-lightest text-blue-dark
                        @else bg-gray-100 text-gray-800
                        @endif">
                        <i class="fas fa-circle mr-1"></i>{{ $ticket->status == 'in_progress' ? 'En Proceso' : ucfirst($ticket->status) }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                        <i class="fas fa-tag mr-1"></i>{{ ucfirst($ticket->category) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('superadmin.tickets.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>Descripción del Problema
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>
            </div>

            @if($ticket->solution)
            <!-- Solution -->
            <div class="bg-green-50 border-l-4 border-blue-medium/500 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-green-800 mb-4">
                    <i class="fas fa-check-circle mr-2"></i>Solución Aplicada
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->solution }}</p>
                </div>
                @if($ticket->resolved_at)
                <p class="text-sm text-blue-medium mt-3">
                    <i class="fas fa-clock mr-1"></i>Resuelto el {{ $ticket->resolved_at->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
            @endif

            <!-- Notes Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-comments mr-2 text-blue-600"></i>Notas y Seguimiento
                </h3>

                <!-- Add Note Form -->
                <form action="{{ route('superadmin.tickets.add-note', $ticket) }}" method="POST" class="mb-6 bg-gray-50 p-4 rounded-lg">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agregar Nota</label>
                    <textarea name="note" required rows="3" placeholder="Escribe una nota sobre este ticket..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 mb-2"></textarea>
                    <div class="flex justify-between items-center">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 mr-2">
                            Nota interna (solo visible para superadmins)
                        </label>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Agregar Nota
                        </button>
                    </div>
                </form>

                <!-- Notes List -->
                <div class="space-y-4">
                    @forelse($ticket->notes()->latest()->get() as $note)
                    <div class="border-l-4 {{ $note->is_internal ? 'border-yellow-400 bg-yellow-50' : 'border-blue-400 bg-blue-50' }} p-4 rounded">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-800">{{ $note->user->name }}</span>
                                @if($note->is_internal)
                                <span class="px-2 py-1 text-xs bg-yellow-200 text-yellow-800 rounded">Interna</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $note->note }}</p>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">
                        <i class="fas fa-comment-slash text-3xl mb-2 text-gray-300"></i><br>
                        No hay notas registradas
                    </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Update Status Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>Actualizar Ticket
                </h3>
                <form action="{{ route('superadmin.tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="status" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Abierto</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resuelto</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                            <select name="priority" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Baja</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Media</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Alta</option>
                                <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Solución</label>
                            <textarea name="solution" rows="4" placeholder="Describe la solución..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ $ticket->solution }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo Real (min)</label>
                            <input type="number" name="actual_time" min="0" value="{{ $ticket->actual_time }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-save mr-2"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ticket Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-info mr-2 text-blue-600"></i>Información
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Empresa:</span>
                        <p class="font-semibold text-gray-800">{{ $ticket->company->name }}</p>
                    </div>
                    @if($ticket->user)
                    <div>
                        <span class="text-gray-600">Usuario:</span>
                        <p class="font-semibold text-gray-800">{{ $ticket->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-gray-600">Contacto:</span>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-{{ $ticket->contact_type == 'call' ? 'phone' : ($ticket->contact_type == 'whatsapp' ? 'whatsapp' : 'envelope') }} mr-1"></i>
                            {{ ucfirst($ticket->contact_type) }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600">Atendido por:</span>
                        <p class="font-semibold text-gray-800">{{ $ticket->superadmin->name ?? 'Sin asignar' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Creado:</span>
                        <p class="font-semibold text-gray-800">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($ticket->estimated_time)
                    <div>
                        <span class="text-gray-600">Tiempo Estimado:</span>
                        <p class="font-semibold text-gray-800">{{ $ticket->estimated_time }} minutos</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
