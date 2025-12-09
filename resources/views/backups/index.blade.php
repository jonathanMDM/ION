@extends('layouts.app')

@section('page-title', 'Respaldos de la Empresa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Respaldos de la Empresa</h2>
    <p class="text-gray-600">Gestiona los respaldos de datos de tu empresa</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
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
        <form action="{{ route('backups.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="w-full md:flex-1">
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
                    class="w-full md:w-auto px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition flex justify-center items-center"
                >
                    <i class="fas fa-upload mr-2"></i>Subir Backup
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Backups List -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="text-lg font-medium text-gray-900">Backups Disponibles</h3>
        <form action="{{ route('backups.create') }}" method="POST" class="w-full md:w-auto">
            @csrf
            <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded flex justify-center items-center">
                <i class="fas fa-plus mr-2"></i>Crear Backup
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Archivo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Tamaño</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Acciones</th>
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
                        {{ number_format($backup['size'] / 1024, 2) }} KB
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ date('d/m/Y H:i:s', $backup['date']) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('backups.restore', $backup['name']) }}" method="POST" class="inline-block mr-3" onsubmit="return confirm('⚠️ ADVERTENCIA: Esto restaurará los datos del backup. ¿Estás seguro?')">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-undo"></i> Restaurar
                            </button>
                        </form>
                        <a href="{{ route('backups.download', $backup['name']) }}" class="text-gray-800 hover:text-black mr-3">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                        <form action="{{ route('backups.delete', $backup['name']) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este backup?')">
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

<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h3 class="font-bold text-gray-900 mb-2">
        <i class="fas fa-info-circle mr-2"></i>Información
    </h3>
    <ul class="text-sm text-blue-700 space-y-1">
        <li>• Los backups incluyen todos los datos de tu empresa (activos, usuarios, ubicaciones, etc.)</li>
        <li>• Puedes restaurar un backup en cualquier momento para recuperar datos</li>
        <li>• Se recomienda descargar backups importantes a un lugar seguro</li>
        <li>• Solo puedes ver y restaurar backups de tu propia empresa</li>
    </ul>
</div>
@endsection
