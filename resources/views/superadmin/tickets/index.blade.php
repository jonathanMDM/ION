@extends('layouts.superadmin')

@section('page-title', 'Tickets de Soporte')

@section('content')
<div class="mb-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-ticket-alt text-3xl text-gray-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Abiertos</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['open'] }}</p>
                </div>
                <i class="fas fa-folder-open text-3xl text-blue-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">En Proceso</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</p>
                </div>
                <i class="fas fa-spinner text-3xl text-yellow-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Resueltos</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-400"></i>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('superadmin.tickets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket #, asunto, empresa..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Todos</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Abierto</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resuelto</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select name="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Todas</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route('superadmin.tickets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-redo mr-2"></i>Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asunto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-bold text-gray-900">{{ $ticket->ticket_number }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $ticket->company->name }}</div>
                        @if($ticket->user)
                        <div class="text-xs text-gray-500">{{ $ticket->user->name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($ticket->subject, 50) }}</div>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-{{ $ticket->contact_type == 'call' ? 'phone' : ($ticket->contact_type == 'whatsapp' ? 'whatsapp' : 'envelope') }} mr-1"></i>
                            {{ ucfirst($ticket->contact_type) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($ticket->priority == 'urgent') bg-red-100 text-red-800
                            @elseif($ticket->priority == 'high') bg-orange-100 text-orange-800
                            @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($ticket->status == 'open') bg-blue-100 text-blue-800
                            @elseif($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $ticket->status == 'in_progress' ? 'En Proceso' : ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ticket->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('superadmin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                        <p>No hay tickets registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($tickets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
