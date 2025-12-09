@extends('layouts.superadmin')

@section('page-title', 'Editar Anuncio')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('superadmin.announcements.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Anuncios
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Anuncio</h2>

        <form action="{{ route('superadmin.announcements.update', $announcement) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Título *</label>
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" value="{{ old('title', $announcement->title) }}" required>
                @error('title')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Mensaje *</label>
                <textarea name="message" id="message" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('message') border-red-500 @enderror" required>{{ old('message', $announcement->message) }}</textarea>
                @error('message')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipo de Anuncio *</label>
                    <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="info" {{ old('type', $announcement->type) == 'info' ? 'selected' : '' }}>Información (Azul)</option>
                        <option value="warning" {{ old('type', $announcement->type) == 'warning' ? 'selected' : '' }}>Advertencia (Amarillo)</option>
                        <option value="error" {{ old('type', $announcement->type) == 'error' ? 'selected' : '' }}>Error/Crítico (Rojo)</option>
                        <option value="success" {{ old('type', $announcement->type) == 'success' ? 'selected' : '' }}>Éxito (Verde)</option>
                    </select>
                </div>

                <div>
                    <label for="target_audience" class="block text-gray-700 text-sm font-bold mb-2">Audiencia *</label>
                    <select name="target_audience" id="target_audience" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="toggleCompanySelect()">
                        <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>Todos los Usuarios</option>
                        <option value="admins_only" {{ old('target_audience', $announcement->target_audience) == 'admins_only' ? 'selected' : '' }}>Solo Administradores</option>
                        <option value="specific_company" {{ old('target_audience', $announcement->target_audience) == 'specific_company' ? 'selected' : '' }}>Empresa Específica</option>
                    </select>
                </div>
            </div>

            <div id="company_select_container" class="mb-4 {{ old('target_audience', $announcement->target_audience) == 'specific_company' ? '' : 'hidden' }}">
                <label for="company_id" class="block text-gray-700 text-sm font-bold mb-2">Seleccionar Empresa *</label>
                <select name="company_id" id="company_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione una empresa...</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $announcement->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha Inicio (Opcional)</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d\TH:i') : '') }}">
                    <p class="text-gray-500 text-xs mt-1">Dejar vacío para mostrar inmediatamente.</p>
                </div>

                <div>
                    <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha Fin (Opcional)</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d\TH:i') : '') }}">
                    <p class="text-gray-500 text-xs mt-1">Dejar vacío para mostrar indefinidamente.</p>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Actualizar Anuncio
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCompanySelect() {
        const audience = document.getElementById('target_audience').value;
        const container = document.getElementById('company_select_container');
        
        if (audience === 'specific_company') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection
