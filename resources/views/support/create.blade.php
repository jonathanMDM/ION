@extends('layouts.app')

@section('page-title', 'Crear Ticket de Soporte')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-ticket-alt mr-2 text-blue-600"></i>Nuevo Ticket de Soporte
            </h2>
            <p class="text-sm text-gray-600 mt-2">Describe tu problema y nuestro equipo te ayudará</p>
        </div>

        <form action="{{ route('support.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asunto *</label>
                    <input type="text" name="subject" required maxlength="255" value="{{ old('subject') }}" placeholder="Resumen breve del problema" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="category" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="technical">Problema Técnico</option>
                        <option value="configuration">Configuración</option>
                        <option value="query">Consulta</option>
                        <option value="error">Error en el Sistema</option>
                        <option value="other">Otro</option>
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                    <select name="priority" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="low">Baja - Puede esperar</option>
                        <option value="medium" selected>Media - Normal</option>
                        <option value="high">Alta - Importante</option>
                        <option value="urgent">Urgente - Crítico</option>
                    </select>
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del Problema *</label>
                    <textarea name="description" required rows="6" placeholder="Describe detalladamente el problema que estás experimentando..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Incluye todos los detalles posibles para ayudarnos a resolver tu problema más rápido</p>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('support.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-paper-plane mr-2"></i>Enviar Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
