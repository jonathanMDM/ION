@extends('layouts.app')

@section('page-title', 'Ticket ' . $ticket->ticket_number)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-ticket-alt mr-2 text-blue-600"></i>{{ $ticket->ticket_number }}
                </h2>
                <p class="text-lg text-gray-700">{{ $ticket->subject }}</p>
                <div class="flex gap-3 mt-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($ticket->priority == 'urgent') bg-red-100 text-red-800
                        @elseif($ticket->priority == 'high') bg-orange-100 text-orange-800
                        @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($ticket->status == 'open') bg-blue-100 text-blue-800
                        @elseif($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @if($ticket->status == 'open') Abierto
                        @elseif($ticket->status == 'in_progress') En Proceso
                        @elseif($ticket->status == 'resolved') Resuelto
                        @else Cerrado
                        @endif
                    </span>
                </div>
            </div>
            <a href="{{ route('support.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Descripción</h3>
        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
        <div class="mt-4 text-sm text-gray-500">
            <i class="fas fa-clock mr-1"></i>Creado el {{ $ticket->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    @if($ticket->solution)
    <!-- Solution -->
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-green-800 mb-4">
            <i class="fas fa-check-circle mr-2"></i>Solución
        </h3>
        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->solution }}</p>
        @if($ticket->resolved_at)
        <p class="text-sm text-green-600 mt-3">
            <i class="fas fa-clock mr-1"></i>Resuelto el {{ $ticket->resolved_at->format('d/m/Y H:i') }}
        </p>
        @endif
    </div>
    @endif

    <!-- Comments -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Comentarios</h3>

        <!-- Add Comment Form -->
        <form action="{{ route('support.add-note', $ticket) }}" method="POST" class="mb-6 bg-gray-50 p-4 rounded-lg">
            @csrf
            <label class="block text-sm font-medium text-gray-700 mb-2">Agregar Comentario</label>
            <textarea name="note" required rows="3" placeholder="Escribe un comentario..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 mb-2"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-comment mr-2"></i>Agregar Comentario
                </button>
            </div>
        </form>

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($ticket->notes()->where('is_internal', false)->latest()->get() as $note)
            <div class="border-l-4 border-blue-400 bg-blue-50 p-4 rounded">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-semibold text-gray-800">{{ $note->user->name }}</span>
                    <span class="text-xs text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $note->note }}</p>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">
                <i class="fas fa-comment-slash text-3xl mb-2 text-gray-300"></i><br>
                No hay comentarios aún
            </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
