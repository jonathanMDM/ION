@extends('layouts.app')

@section('page-title', 'Soporte')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-headset mr-2 text-gray-600"></i>Centro de Soporte
            </h1>
            <p class="text-gray-600">
                ¿Necesitas ayuda? Completa el formulario y nuestro equipo te responderá lo antes posible.
            </p>
        </div>

        <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Asunto <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="subject" 
                    id="subject" 
                    value="{{ old('subject') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                    placeholder="Describe brevemente tu consulta"
                >
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select 
                    name="category" 
                    id="category" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('category') border-red-500 @enderror"
                >
                    <option value="">Selecciona una categoría</option>
                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>
                        <i class="fas fa-cog"></i> Soporte Técnico
                    </option>
                    <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>
                        <i class="fas fa-credit-card"></i> Facturación
                    </option>
                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>
                        <i class="fas fa-question-circle"></i> Consulta General
                    </option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Mensaje <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="message" 
                    id="message" 
                    rows="8"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('message') border-red-500 @enderror"
                    placeholder="Describe tu consulta o problema en detalle..."
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Incluye toda la información relevante para ayudarnos a resolver tu consulta más rápido.
                </p>
            </div>

            <!-- Attachment -->
            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                    Adjuntar Archivo (Opcional)
                </label>
                <input 
                    type="file" 
                    name="attachment" 
                    id="attachment"
                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('attachment') border-red-500 @enderror"
                >
                @error('attachment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Formatos permitidos: JPG, PNG, PDF, DOC, DOCX. Tamaño máximo: 5MB
                </p>
            </div>

            <!-- User Info Display -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Información de Contacto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Nombre:</span> {{ Auth::user()->name }}
                    </div>
                    <div>
                        <span class="font-medium">Email:</span> {{ Auth::user()->email }}
                    </div>
                    <div>
                        <span class="font-medium">Empresa:</span> {{ Auth::user()->company ? Auth::user()->company->name : 'Sin empresa' }}
                    </div>
                    <div>
                        <span class="font-medium">Rol:</span> 
                        @if(Auth::user()->isAdmin())
                            Administrador
                        @elseif(Auth::user()->isEditor())
                            Editor
                        @else
                            Visor
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <a 
                    href="{{ route('dashboard') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                >
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition"
                >
                    <i class="fas fa-paper-plane mr-2"></i>Enviar Solicitud
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-info-circle mr-2 text-gray-600"></i>Información Útil
        </h2>
        <div class="space-y-3 text-sm text-gray-600">
            <div class="flex items-start">
                <i class="fas fa-clock text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="font-medium text-gray-700">Tiempo de Respuesta:</span> 
                    Normalmente respondemos en 24-48 horas hábiles.
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-envelope text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="font-medium text-gray-700">Email Directo:</span> 
                    <a href="mailto:support@ioninventory.com" class="text-gray-800 hover:text-gray-900 underline">
                        support@ioninventory.com
                    </a>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-book text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="font-medium text-gray-700">Documentación:</span> 
                    Consulta nuestra <a href="#" class="text-gray-800 hover:text-gray-900 underline">guía de usuario</a> para respuestas rápidas.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
