@extends('layouts.app')

@section('page-title', 'Detalle de Solicitud de Soporte')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('support.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Soporte
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $supportRequest->subject }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Solicitud #{{ $supportRequest->id }} • 
                        Creada {{ $supportRequest->created_at->diffForHumans() }}
                    </p>
                </div>
                <div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'in_progress' => 'En Progreso',
                            'resolved' => 'Resuelto',
                            'closed' => 'Cerrado',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$supportRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$supportRequest->status] ?? ucfirst($supportRequest->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Request Details -->
        <div class="p-6 space-y-6">
            <!-- Category -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Categoría</h3>
                <p class="text-gray-900">
                    @php
                        $categoryLabels = [
                            'technical' => 'Soporte Técnico',
                            'billing' => 'Facturación',
                            'general' => 'Consulta General',
                        ];
                    @endphp
                    <i class="fas fa-tag mr-2 text-gray-400"></i>
                    {{ $categoryLabels[$supportRequest->category] ?? ucfirst($supportRequest->category) }}
                </p>
            </div>



            <!-- Chat Interface -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Conversación</h3>
                
                <div class="space-y-4 mb-6">
                    <!-- Original Message -->
                    <div class="flex justify-end">
                        <div class="bg-blue-50 rounded-lg p-4 max-w-3xl border border-blue-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-blue-800">Tú</span>
                                <span class="text-xs text-blue-600">{{ $supportRequest->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $supportRequest->message }}</p>
                        </div>
                    </div>

                    <!-- Admin Notes (Legacy Support) -->
                    @if($supportRequest->admin_notes)
                    <div class="flex justify-start">
                        <div class="bg-gray-100 rounded-lg p-4 max-w-3xl border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-800">Soporte</span>
                                <span class="text-xs text-gray-600">{{ $supportRequest->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $supportRequest->admin_notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- User Response (Legacy Support) -->
                    @if($supportRequest->user_response)
                    <div class="flex justify-end">
                        <div class="bg-blue-50 rounded-lg p-4 max-w-3xl border border-blue-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-blue-800">Tú</span>
                                <span class="text-xs text-blue-600">{{ $supportRequest->user_responded_at ? $supportRequest->user_responded_at->format('d/m/Y H:i') : '' }}</span>
                            </div>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $supportRequest->user_response }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Chat Messages -->
                    @foreach($supportRequest->messages as $message)
                    <div class="flex {{ $message->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                        <div class="{{ $message->is_admin_reply ? 'bg-gray-100 border-gray-200' : 'bg-blue-50 border-blue-100' }} rounded-lg p-4 max-w-3xl border">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold {{ $message->is_admin_reply ? 'text-gray-800' : 'text-blue-800' }}">
                                    {{ $message->is_admin_reply ? 'Soporte' : 'Tú' }}
                                </span>
                                <span class="text-xs {{ $message->is_admin_reply ? 'text-gray-600' : 'text-blue-600' }}">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $message->message }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Response Form & Actions -->
                @if(!in_array($supportRequest->status, ['resolved', 'closed']))
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <form action="{{ route('support.respond', $supportRequest) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="user_response" class="sr-only">Tu respuesta</label>
                            <textarea 
                                name="user_response" 
                                id="user_response" 
                                rows="3"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Escribe tu respuesta aquí..."
                            ></textarea>
                        </div>
                        <div class="flex justify-between items-center">
                            <button 
                                type="button"
                                onclick="if(confirm('¿Estás seguro de que quieres marcar esta solicitud como resuelta?')) document.getElementById('resolve-form').submit()"
                                class="text-sm text-green-600 hover:text-green-800 font-medium"
                            >
                                <i class="fas fa-check-circle mr-1"></i>Marcar como Resuelto
                            </button>
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>Enviar
                            </button>
                        </div>
                    </form>
                    
                    <form id="resolve-form" action="{{ route('support.resolve', $supportRequest) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
                @else
                <div class="bg-green-50 rounded-lg p-4 border border-green-200 text-center">
                    <p class="text-green-800 font-medium">
                        <i class="fas fa-check-circle mr-2"></i>
                        Esta solicitud ha sido marcada como resuelta.
                    </p>
                    <p class="text-sm text-green-600 mt-1">
                        Si necesitas más ayuda, por favor crea una nueva solicitud.
                    </p>
                </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Historial</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Solicitud creada</p>
                            <p class="text-xs text-gray-500">{{ $supportRequest->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($supportRequest->status !== 'pending')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Estado actualizado a: {{ $statusLabels[$supportRequest->status] ?? ucfirst($supportRequest->status) }}</p>
                            <p class="text-xs text-gray-500">{{ $supportRequest->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($supportRequest->resolved_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-400 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Solicitud resuelta</p>
                            <p class="text-xs text-gray-500">{{ $supportRequest->resolved_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
