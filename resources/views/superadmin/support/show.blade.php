@extends('layouts.superadmin')

@section('page-title', 'Detalle de Solicitud #' . $supportRequest->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('superadmin.support.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Solicitudes
        </a>
    </div>

    <!-- Request Details -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Solicitud #{{ $supportRequest->id }}
                </h1>
                <p class="text-gray-600">{{ $supportRequest->subject }}</p>
            </div>
            <span class="px-3 py-1 text-sm rounded-full 
                {{ $supportRequest->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $supportRequest->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $supportRequest->status == 'resolved' ? 'bg-blue-lightest text-blue-dark' : '' }}
                {{ $supportRequest->status == 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                {{ $supportRequest->status_label }}
            </span>
        </div>

        <!-- Request Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Información del Usuario</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Nombre:</span>
                        <span class="font-medium text-gray-900 ml-2">{{ $supportRequest->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <a href="mailto:{{ $supportRequest->user->email }}" class="font-medium text-gray-900 ml-2 hover:text-gray-700 underline">
                            {{ $supportRequest->user->email }}
                        </a>
                    </div>
                    <div>
                        <span class="text-gray-600">Empresa:</span>
                        <span class="font-medium text-gray-900 ml-2">
                            {{ $supportRequest->company ? $supportRequest->company->name : 'Sin empresa' }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Detalles de la Solicitud</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Categoría:</span>
                        <span class="px-2 py-1 text-xs rounded-full ml-2
                            {{ $supportRequest->category == 'technical' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $supportRequest->category == 'billing' ? 'bg-blue-lightest text-blue-dark' : '' }}
                            {{ $supportRequest->category == 'general' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($supportRequest->category) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Fecha de Creación:</span>
                        <span class="font-medium text-gray-900 ml-2">{{ $supportRequest->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($supportRequest->resolved_at)
                    <div>
                        <span class="text-gray-600">Fecha de Resolución:</span>
                        <span class="font-medium text-gray-900 ml-2">{{ $supportRequest->resolved_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Message -->
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Mensaje</h3>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $supportRequest->message }}</p>
            </div>
        </div>

        <!-- Attachment -->
        @if($supportRequest->attachment_path)
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Archivo Adjunto</h3>
            <div class="flex items-center space-x-3 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <i class="fas fa-paperclip text-gray-400 text-xl"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $supportRequest->attachment_name }}</p>
                </div>
                <a href="{{ asset('storage/' . $supportRequest->attachment_path) }}" target="_blank" class="px-3 py-1 bg-gray-800 text-white text-sm rounded hover:bg-gray-900 transition">
                    <i class="fas fa-download mr-1"></i>Descargar
                </a>
            </div>
        </div>
        @endif

        <!-- Admin Notes -->
        @if($supportRequest->admin_notes)
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Notas del Administrador</h3>
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $supportRequest->admin_notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Update Status Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-edit mr-2"></i>Actualizar Estado
        </h2>

        <form action="{{ route('superadmin.support.update-status', $supportRequest) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select 
                    name="status" 
                    id="status" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    <option value="pending" {{ $supportRequest->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="in_progress" {{ $supportRequest->status == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="resolved" {{ $supportRequest->status == 'resolved' ? 'selected' : '' }}>Resuelto</option>
                    <option value="closed" {{ $supportRequest->status == 'closed' ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>

            <!-- Admin Notes -->
            <div>
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Notas del Administrador</label>
                <textarea 
                    name="admin_notes" 
                    id="admin_notes" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
                    placeholder="Agrega notas internas sobre esta solicitud..."
                >{{ $errors->any() ? old('admin_notes') : '' }}</textarea>
                <p class="mt-1 text-sm text-gray-500">
                    Estas notas son solo para uso interno y no se envían al usuario.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('superadmin.support.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
