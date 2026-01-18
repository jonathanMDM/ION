@extends('layouts.superadmin')

@section('page-title', 'Backups de Empresas')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Backups de Empresas</h2>
    <p class="text-gray-600">Gestiona los respaldos de datos de cada empresa</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-blue-medium/400 text-blue-dark px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Upload Backup Section -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Subir Backup</h3>
        <p class="text-sm text-gray-600 mt-1">Sube un archivo de backup (.sql) para restaurar datos</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('superadmin.backups.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <input 
                        type="file" 
                        name="backup_file" 
                        accept=".sql,.zip"
                        required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                    >
                    <p class="mt-1 text-xs text-gray-500">Formatos aceptados: .sql, .zip (máx. 50MB)</p>
                </div>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition"
                >
                    <i class="fas fa-upload mr-2"></i>Subir Backup
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Backups List -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Backups Disponibles</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Archivo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($backups as $backup)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <i class="fas fa-database text-gray-600 mr-2"></i>
                        {{ $backup['name'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $backup['size'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $backup['date'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('superadmin.backups.restore', $backup['name']) }}" method="POST" class="inline-block mr-3" onsubmit="return confirm('⚠️ ADVERTENCIA: Esto restaurará los datos del backup. ¿Estás seguro?')">
                            @csrf
                            <button type="submit" class="text-blue-medium hover:text-green-900">
                                <i class="fas fa-undo"></i> Restaurar
                            </button>
                        </form>
                        <a href="{{ route('superadmin.backups.download', $backup['name']) }}" class="text-gray-800 hover:text-black mr-3">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                        <form action="{{ route('superadmin.backups.delete', $backup['name']) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este backup?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No hay backups disponibles
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
