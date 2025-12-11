{{-- Bulk Delete Header Component --}}
@props(['route', 'createRoute' => null, 'createText' => 'Nuevo', 'importRoute' => null, 'title'])

<form id="bulkDeleteForm" action="{{ $route }}" method="POST">
    @csrf
    @method('DELETE')
    
    <div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 md:flex-1">{{ $title }}</h2>
        <div class="flex flex-col md:flex-row gap-3 shrink-0">
            <!-- Bulk delete button (hidden by default) -->
            <button type="button" id="bulkDeleteBtn" class="hidden bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap" onclick="confirmBulkDelete('{{ $slot ?? 'elemento' }}')">
                <i class="fas fa-trash mr-2"></i>Eliminar Seleccionados (<span id="selectedCount">0</span>)
            </button>
            
            @if($importRoute)
            <a href="{{ $importRoute }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap">
                <i class="fas fa-file-upload mr-2"></i>Importar
            </a>
            @endif
            
            @if($createRoute)
            <a href="{{ $createRoute }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>{{ $createText }}
            </a>
            @endif
        </div>
    </div>
