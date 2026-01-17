@extends('layouts.app')

@section('page-title', 'Asignar Activo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('assets.show', $asset->id) }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver al Activo
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Asignar Activo</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Asignando: <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $asset->name }}</span> ({{ $asset->custom_id }})
        </p>

        <form action="{{ route('assets.assign.store', $asset->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Empleado *</label>
                <select name="employee_id" required class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                    <option value="">Seleccionar Empleado</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }} ({{ $employee->department ?? 'Sin depto.' }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de Asignación *</label>
                    <input type="date" name="assigned_date" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
                    @error('assigned_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">¿Es un préstamo temporal?</label>
                    <div class="flex items-center mt-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_loan" class="sr-only peer" onchange="toggleReturnDate(this)">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Activar fecha de retorno</span>
                        </label>
                    </div>
                </div>
            </div>

            <div id="return_date_container" class="mb-6 hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Esperada de Retorno *</label>
                <input type="date" name="expected_return_date" id="expected_return_date" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notas</label>
                <textarea name="notes" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-colors" placeholder="Condiciones de entrega, accesorios incluidos, etc.">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('assets.show', $asset->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded">
                    Confirmar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleReturnDate(checkbox) {
        const container = document.getElementById('return_date_container');
        const input = document.getElementById('expected_return_date');
        if (checkbox.checked) {
            container.classList.remove('hidden');
            input.setAttribute('required', 'required');
        } else {
            container.classList.add('hidden');
            input.removeAttribute('required');
            input.value = '';
        }
    }
</script>
@endpush
@endsection
