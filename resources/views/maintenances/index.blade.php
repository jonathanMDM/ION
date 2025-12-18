@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row md:items-center mb-6 gap-4">
    <h1 class="text-3xl font-bold text-gray-800 md:flex-1">Historial de Mantenimiento</h1>
    <a href="{{ route('maintenances.create') }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap shrink-0">
        <i class="fas fa-plus mr-2"></i>Agregar Registro de Mantenimiento
    </a>
</div>

<!-- Search Bar -->
<div class="mb-4 bg-white p-4 rounded-lg shadow-md">
    <div class="flex items-center gap-3">
        <div class="flex-1 relative">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Buscar por Activo, Fecha, Descripción o Costo..." 
                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent"
            >
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <button 
            type="button" 
            onclick="clearSearch()" 
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors"
        >
            <i class="fas fa-times mr-2"></i>Limpiar
        </button>
    </div>
    <p class="text-sm text-gray-500 mt-2">
        <span id="resultCount">{{ $maintenances->count() }}</span> mantenimiento(s) encontrado(s)
    </p>
</div>

<div class="bg-white dark:bg-gray-800 shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left whitespace-nowrap">Activo</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Fecha</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Descripción</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Costo</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($maintenances as $maintenance)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left whitespace-nowrap">
                    <span class="font-medium">{{ $maintenance->asset->name }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span>{{ $maintenance->date->format('Y-m-d') }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span>{{ Str::limit($maintenance->description, 50) }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                    ${{ number_format($maintenance->cost, 2) }}
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center">
                        <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="w-4 mr-2 transform hover:text-gray-600 hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');
    const resultCount = document.getElementById('resultCount');
    const totalRows = tableRows.length;

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        resultCount.textContent = visibleCount;
    });
});

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');
    const resultCount = document.getElementById('resultCount');
    
    searchInput.value = '';
    tableRows.forEach(row => {
        row.style.display = '';
    });
    resultCount.textContent = tableRows.length;
}
</script>
@endsection
