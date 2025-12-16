@extends('layouts.superadmin')

@section('page-title', 'Crear Ticket de Soporte')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-ticket-alt mr-2 text-blue-600"></i>Nuevo Ticket de Soporte
            </h2>
            @if($company)
            <p class="text-sm text-gray-600 mt-2">
                Cliente: <strong>{{ $company->name }}</strong>
            </p>
            @endif
        </div>

        <form action="{{ route('superadmin.tickets.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Empresa *</label>
                    @if($company)
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <input type="text" value="{{ $company->name }}" disabled class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                    @else
                        <select name="company_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Seleccione una empresa</option>
                            @foreach(\App\Models\Company::where('status', 'active')->orderBy('name')->get() as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('company_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- User Selection (Optional) -->
                @if($company && $company->users->count() > 0)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario (Opcional)</label>
                    <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Sin especificar</option>
                        @foreach($company->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Contact Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Contacto *</label>
                    <select name="contact_type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="call">Llamada Telefónica</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="email">Email</option>
                        <option value="other">Otro</option>
                    </select>
                    @error('contact_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                    <select name="priority" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="low">Baja</option>
                        <option value="medium" selected>Media</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente</option>
                    </select>
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="category" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="technical">Técnico</option>
                        <option value="configuration">Configuración</option>
                        <option value="query">Consulta</option>
                        <option value="error">Error</option>
                        <option value="other">Otro</option>
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Estimated Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo Estimado (minutos)</label>
                    <input type="number" name="estimated_time" min="0" placeholder="Ej: 30" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('estimated_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Subject -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asunto *</label>
                    <input type="text" name="subject" required maxlength="255" placeholder="Resumen breve del problema" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del Problema *</label>
                    <textarea name="description" required rows="5" placeholder="Describe detalladamente el problema reportado por el cliente..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Solution (Optional) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Solución Aplicada (Opcional)</label>
                    <textarea name="solution" rows="4" placeholder="Si ya se resolvió el problema, describe la solución aplicada..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Si completas este campo, el ticket se marcará como "Resuelto"</p>
                    @error('solution') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.tickets.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-save mr-2"></i>Crear Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
