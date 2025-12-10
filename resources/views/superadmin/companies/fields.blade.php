@extends('layouts.superadmin')

@section('page-title', 'Campos Personalizados - ' . $company->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('superadmin.companies.index') }}" class="text-indigo-600 hover:text-indigo-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Empresas
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Campos Personalizados</h2>
        <p class="text-gray-600 mb-6">Gestiona los campos personalizados para {{ $company->name }}</p>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-700">
                <i class="fas fa-info-circle mr-2"></i>
                Esta funcionalidad permite crear campos personalizados para los activos de esta empresa.
            </p>
        </div>

        <!-- Formulario para crear campo -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Crear Nuevo Campo</h3>
            <form action="{{ route('superadmin.companies.fields.store', $company) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Campo</label>
                        <input 
                            type="text" 
                            name="name" 
                            required
                            placeholder="ej: numero_serie"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Etiqueta</label>
                        <input 
                            type="text" 
                            name="label" 
                            required
                            placeholder="ej: Número de Serie"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select 
                            name="type" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="text">Texto</option>
                            <option value="number">Número</option>
                            <option value="date">Fecha</option>
                            <option value="textarea">Área de Texto</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                        >
                            <i class="fas fa-plus mr-2"></i>Crear Campo
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($fields->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requerido</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($fields as $field)
                    <tr>
                        <td class="px-6 py-4">{{ $field->name }}</td>
                        <td class="px-6 py-4">{{ ucfirst($field->type) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $field->required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $field->required ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('superadmin.companies.fields.destroy', [$company, $field]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3"></i>
            <p>No hay campos personalizados configurados</p>
        </div>
        @endif
    </div>
</div>
@endsection
