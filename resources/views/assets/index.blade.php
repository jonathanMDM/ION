@extends('layouts.app')

@section('page-title', 'Activos')

@section('content')
<form id="bulkDeleteForm" action="{{ route('assets.bulk-delete') }}" method="POST">
    @csrf
    @method('DELETE')
    
    <div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white md:flex-1">Activos</h2>
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

    <!-- Search Bar -->
    <div class="mb-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md transition-colors">
        <div class="flex items-center gap-3">
            <div class="flex-1 relative">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Buscar por ID, Nombre, Ubicación, Categoría..." 
                    class="w-full px-4 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 dark:focus:ring-gray-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors"
                >
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button 
                type="button" 
                onclick="clearSearch()" 
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
            >
                <i class="fas fa-times mr-2"></i>Limpiar
            </button>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
            <span id="resultCount">{{ $assets->total() }}</span> activo(s) encontrado(s)
        </p>
    </div>

<div class="bg-white dark:bg-gray-800 shadow-md rounded my-6 overflow-x-auto transition-colors">
    <table class="w-full table-auto" style="min-width: 1400px;">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal">
                <th class="py-3 px-4 text-center" style="width: 50px;">
                    <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-gray-600" onchange="toggleAll(this)">
                </th>
                <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 120px;">ID Único</th>
                <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 200px;">Nombre</th>
                <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 100px;">Modelo</th>
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 120px;">Placa Municipio</th>
                @endif
                
                @php
                    $customFields = \App\Models\CustomField::where('company_id', Auth::user()->company_id)->get();
                @endphp
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 150px;">{{ $field->label }}</th>
                    @endif
                @endforeach
                <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 150px;">Ubicación</th>
                <th class="py-3 px-4 text-left whitespace-nowrap" style="min-width: 180px;">Categoría/Subcategoría</th>
                <th class="py-3 px-4 text-left" style="min-width: 200px;">Especificaciones</th>
                <th class="py-3 px-4 text-center whitespace-nowrap" style="min-width: 100px;">Estado</th>
                <th class="py-3 px-4 text-center whitespace-nowrap" style="min-width: 80px;">Cantidad</th>
                <th class="py-3 px-4 text-center whitespace-nowrap" style="min-width: 120px;">Valor</th>
                <th class="py-3 px-4 text-center whitespace-nowrap" style="min-width: 120px;">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
            @foreach($assets as $asset)
            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                <td class="py-3 px-4 text-center">
                    <input type="checkbox" name="selected_assets[]" value="{{ $asset->id }}" class="form-checkbox h-5 w-5 text-gray-600 row-checkbox" onchange="updateSelectedCount()">
                </td>
                <td class="py-3 px-4 text-left whitespace-nowrap">
                    <span class="font-medium">{{ $asset->custom_id }}</span>
                </td>
                <td class="py-3 px-4 text-left whitespace-nowrap">
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
                <td class="py-3 px-4 text-left">
                    <span>{{ $asset->model ?? '-' }}</span>
                </td>

                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <td class="py-3 px-4 text-left">
                    <span>{{ $asset->municipality_plate ?? '-' }}</span>
                </td>
                @endif

                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <td class="py-3 px-4 text-left">
                        <span>{{ $asset->custom_attributes[$field->name] ?? '-' }}</span>
                    </td>
                    @endif
                @endforeach
                <td class="py-3 px-4 text-left">
                    <span>{{ $asset->location->name }}</span>
                </td>
                <td class="py-3 px-4 text-left">
                    <span>{{ $asset->subcategory?->category?->name ?? 'Sin categoría' }} / {{ $asset->subcategory?->name ?? 'Sin subcategoría' }}</span>
                </td>
                <td class="py-3 px-4 text-left">
                    <span class="text-sm">{{ Str::limit($asset->specifications, 50) }}</span>
                </td>
                <td class="py-3 px-4 text-center w-32">
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
                <td class="py-3 px-4 text-center">
                    <span class="font-semibold">${{ number_format($asset->value, 2) }}</span>
                </td>
                <td class="py-3 px-4 text-center">
                    <div class="flex item-center justify-center">
                        <a href="{{ route('assets.show', $asset->id) }}" class="w-4 mr-2 transform hover:text-green-500 hover:scale-110" title="Ver detalles">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <!-- Withdrawal Button -->
                        <button type="button" onclick="openWithdrawModal({{ $asset->id }}, '{{ $asset->name }}', {{ $asset->quantity }})" class="w-4 mr-2 transform hover:text-orange-500 hover:scale-110" title="Retirar Stock">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('edit_assets'))
                        <a href="{{ route('assets.edit', $asset->id) }}" class="w-4 mr-2 transform hover:text-gray-600 hover:scale-110" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        @endif
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('delete_assets'))
                        <button type="button" onclick="deleteAsset({{ $asset->id }})" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" title="Eliminar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
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

<!-- Single Delete Form -->
<form id="singleDeleteForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteAsset(id) {
    if (typeof Swal === 'undefined') {
        if (confirm('¿Estás seguro de que deseas eliminar este activo?')) {
            const form = document.getElementById('singleDeleteForm');
            const url = "{{ route('assets.destroy', ':id') }}";
            form.action = url.replace(':id', id);
            form.submit();
        }
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('singleDeleteForm');
            const url = "{{ route('assets.destroy', ':id') }}";
            form.action = url.replace(':id', id);
            form.submit();
        }
    });
}

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
    
    if (typeof Swal === 'undefined') {
        if (confirm(`¿Estás seguro de que deseas eliminar ${count} activo(s)? Esta acción no se puede deshacer.`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminarán ${count} activo(s). ¡Esta acción no se puede deshacer!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Sí, eliminar todo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('bulkDeleteForm').submit();
        }
    });
}

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

<!-- Withdraw Modal -->
<div id="withdrawModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeWithdrawModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="withdrawForm" action="" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Retirar Stock: <span id="withdrawAssetName"></span>
                    </h3>
                    <div class="mt-4">
                        <label for="withdraw_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad a retirar (Max: <span id="maxQuantity"></span>)</label>
                        <input type="number" name="quantity" id="withdraw_quantity" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm" required>
                    </div>
                    <div class="mt-4">
                        <label for="withdraw_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo del retiro</label>
                        <textarea name="reason" id="withdraw_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm" placeholder="Ej: Venda a cliente, Consumo interno..." required></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar Retiro
                    </button>
                    <button type="button" onclick="closeWithdrawModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openWithdrawModal(id, name, max) {
    const form = document.getElementById('withdrawForm');
    const url = "{{ route('assets.withdraw', ':id') }}";
    form.action = url.replace(':id', id);
    
    document.getElementById('withdrawAssetName').textContent = name;
    document.getElementById('maxQuantity').textContent = max;
    document.getElementById('withdraw_quantity').max = max;
    document.getElementById('withdrawModal').classList.remove('hidden');
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
}
</script>

<script>
function deleteAsset(id) {
    if (typeof Swal === 'undefined') {
        if (confirm('¿Estás seguro de que deseas eliminar este activo?')) {
            const form = document.getElementById('singleDeleteForm');
            const url = "{{ route('assets.destroy', ':id') }}";
            form.action = url.replace(':id', id);
            form.submit();
        }
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('singleDeleteForm');
            const url = "{{ route('assets.destroy', ':id') }}";
            form.action = url.replace(':id', id);
            form.submit();
        }
    });
}

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
    
    if (typeof Swal === 'undefined') {
        if (confirm(`¿Estás seguro de que deseas eliminar ${count} activo(s)? Esta acción no se puede deshacer.`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminarán ${count} activo(s). ¡Esta acción no se puede deshacer!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Sí, eliminar todo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('bulkDeleteForm').submit();
        }
    });
}

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
