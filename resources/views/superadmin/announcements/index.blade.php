@extends('layouts.superadmin')

@section('page-title', 'Gestión de Anuncios')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 md:flex-1">Anuncios Globales</h1>
        <a href="{{ route('superadmin.announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap shrink-0">
            <i class="fas fa-plus mr-2"></i>Nuevo Anuncio
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-blue-medium/500 text-blue-dark p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Audiencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Fechas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($announcements as $announcement)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $announcement->title }}</div>
                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($announcement->message, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $announcement->type == 'info' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $announcement->type == 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $announcement->type == 'error' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $announcement->type == 'success' ? 'bg-blue-lightest text-blue-dark' : '' }}">
                            {{ ucfirst($announcement->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($announcement->target_audience == 'all')
                            Todos
                        @elseif($announcement->target_audience == 'admins_only')
                            Solo Admins
                        @else
                            {{ $announcement->company->name ?? 'Empresa Específica' }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($announcement->start_date)
                            <div>Inicio: {{ $announcement->start_date->format('d/m/Y H:i') }}</div>
                        @endif
                        @if($announcement->end_date)
                            <div>Fin: {{ $announcement->end_date->format('d/m/Y H:i') }}</div>
                        @endif
                        @if(!$announcement->start_date && !$announcement->end_date)
                            Siempre visible
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('superadmin.announcements.toggle', $announcement) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-sm font-medium {{ $announcement->is_active ? 'text-blue-medium hover:text-green-900' : 'text-red-600 hover:text-red-900' }}">
                                {{ $announcement->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('superadmin.announcements.edit', $announcement) }}" class="text-blue-medium hover:text-indigo-900 mr-3">Editar</a>
                        <form action="{{ route('superadmin.announcements.destroy', $announcement) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este anuncio?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay anuncios creados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
