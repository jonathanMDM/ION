@extends('layouts.app')

@section('page-title', 'Activos')

@section('content')
<form id="bulkDeleteForm" action="{{ route('assets.bulk-delete') }}" method="POST">
    @csrf
    @method('DELETE')
    
    <div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 md:flex-1">Activos</h2>
        <div class="flex flex-col md:flex-row gap-3 shrink-0">
            <!-- Bulk delete button (hidden by default) -->
            <button type="button" id="bulkDeleteBtn" class="hidden bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap" onclick="confirmBulkDelete()">
                <i class="fas fa-trash mr-2"></i>Eliminar Seleccionados (<span id="selectedCount">0</span>)
            </button>
            
            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('create_assets'))
            <a href="{{ route('imports.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap">
                <i class="fas fa-file-upload mr-2"></i>Importar Activos
            </a>
            <a href="{{ route('assets.create') }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>Nuevo Activo
            </a>
            @endif
        </div>
    </div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-center">
                    <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-gray-600" onchange="toggleAll(this)">
                </th>
                <th class="py-3 px-6 text-left whitespace-nowrap">ID Único</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Nombre</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Modelo</th>
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <th class="py-3 px-6 text-left whitespace-nowrap">Placa Municipio</th>
                @endif
                
                @php
                    $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
                @endphp
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <th class="py-3 px-6 text-left whitespace-nowrap">{{ $field->label }}</th>
                    @endif
                @endforeach
                <th class="py-3 px-6 text-left whitespace-nowrap">Ubicación</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Categoría/Subcategoría</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Especificaciones</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Estado</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Cantidad</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Valor</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($assets as $asset)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-center">
                    <input type="checkbox" name="selected_assets[]" value="{{ $asset->id }}" class="form-checkbox h-5 w-5 text-gray-600 row-checkbox" onchange="updateSelectedCount()">
                </td>
                <td class="py-3 px-6 text-left whitespace-nowrap">
                    <span class="font-medium">{{ $asset->custom_id }}</span>
                </td>
                <td class="py-3 px-6 text-left whitespace-nowrap">
                    <div class="flex items-center">
                        @if($asset->image)
                            @php
                                $imageUrl = \Illuminate\Support\Str::startsWith($asset->image, 'http') 
                                    ? $asset->image 
                                    : asset('storage/' . $asset->image);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $asset->name }}" class="w-10 h-10 rounded object-cover mr-3" onerror="this.style.display='none'">
                        @endif
                        <span class="font-medium">{{ $asset->name }}</span>
                    </div>
                </td>
                <td class="py-3 px-6 text-left">
                    <span>{{ $asset->model ?? '-' }}</span>
                </td>

                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <td class="py-3 px-6 text-left">
                    <span>{{ $asset->municipality_plate ?? '-' }}</span>
                </td>
                @endif

                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <td class="py-3 px-6 text-left">
                        <span>{{ $asset->custom_attributes[$field->name] ?? '-' }}</span>
                    </td>
                    @endif
                @endforeach
                <td class="py-3 px-6 text-left">
                    <span>{{ $asset->location->name }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span>{{ $asset->subcategory->category->name }} / {{ $asset->subcategory->name }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="text-sm">{{ Str::limit($asset->specifications, 50) }}</span>
                </td>
                <td class="py-3 px-6 text-center w-32">
                    <span class="inline-block px-3 py-1 text-xs rounded-full whitespace-nowrap
                        {{ $asset->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $asset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $asset->status == 'decommissioned' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $asset->status == 'active' ? 'Activo' : ($asset->status == 'maintenance' ? 'Mantenimiento' : 'Dado de Baja') }}
                    </span>
                </td>
                <td class="py-3 px-4 text-center w-20">
                    <span class="font-semibold text-sm">{{ $asset->quantity }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                    <span class="font-semibold">${{ number_format($asset->value, 2) }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center">
                        <a href="{{ route('assets.show', $asset->id) }}" class="w-4 mr-2 transform hover:text-green-500 hover:scale-110" title="Ver detalles">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('edit_assets'))
                        <a href="{{ route('assets.edit', $asset->id) }}" class="w-4 mr-2 transform hover:text-gray-600 hover:scale-110" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        @endif
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('delete_assets'))
                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endif
                        @if(!empty($asset->custom_id))
                        <a href="{{ route('assets.qr', $asset->id) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" title="Código QR">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </a>
                        @else
                        <span class="w-4 mr-2 text-gray-300 cursor-not-allowed" title="QR no disponible: Sin ID único">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $assets->links() }}
</div>

</form>

<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const count = checkboxes.length;
    document.getElementById('selectedCount').textContent = count;
    
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    if (count > 0) {
        bulkDeleteBtn.classList.remove('hidden');
    } else {
        bulkDeleteBtn.classList.add('hidden');
    }
    
    // Update select all checkbox
    const selectAll = document.getElementById('selectAll');
    const allCheckboxes = document.querySelectorAll('.row-checkbox');
    selectAll.checked = allCheckboxes.length > 0 && count === allCheckboxes.length;
}

function confirmBulkDelete() {
    const count = document.querySelectorAll('.row-checkbox:checked').length;
    if (confirm(`¿Estás seguro de que deseas eliminar ${count} activo(s)? Esta acción no se puede deshacer.`)) {
        document.getElementById('bulkDeleteForm').submit();
    }
}
</script>

@endsection
