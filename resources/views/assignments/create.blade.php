@extends('layouts.app')

@section('page-title', 'Asignar Activo')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Vincular Recurso</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Formalice la entrega de activos a un colaborador específico.</p>
        </div>
        <a href="{{ route('assets.show', $asset->id) }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al activo
        </a>
    </div>

    <!-- Asset Summary Card -->
    <div class="bg-blue-medium rounded-[2rem] p-8 mb-8 text-white shadow-xl shadow-blue-medium/20 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-2xl">
                <i class="fas fa-laptop-house"></i>
            </div>
            <div>
                <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mb-1">Activo a entregar</p>
                <h3 class="text-2xl font-black leading-tight">{{ $asset->name }}</h3>
                <p class="text-indigo-200 text-xs font-mono">{{ $asset->custom_id }} • {{ $asset->location->name }}</p>
            </div>
        </div>
        <div class="px-6 py-3 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20">
            <p class="text-[9px] font-black uppercase tracking-widest text-indigo-100">Valor de Reposición</p>
            <p class="text-lg font-black">${{ number_format($asset->value, 2) }}</p>
        </div>
    </div>

    <form action="{{ route('assets.assign.store', $asset->id) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Side: Employee Selection -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-user-check text-indigo-500 mr-3"></i>
                        <h3 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-widest">Responsable del Recurso</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3" for="employee_id">Seleccionar Colaborador <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </div>
                                <select name="employee_id" id="employee_id" required 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-4 pl-11 pr-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-medium transition-all font-bold appearance-none">
                                    <option value="" disabled selected>Busque o seleccione un empleado...</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} — {{ $employee->department ?? 'General' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('employee_id') <p class="text-red-500 text-[10px] mt-2 italic font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Notas de Entrega</label>
                            <textarea name="notes" rows="4" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl py-4 px-5 text-sm font-medium focus:ring-2 focus:ring-blue-medium transition-all" 
                                placeholder="Describa el estado físico del activo, accesorios incluidos o condiciones especiales de uso...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Logistics -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center">
                        <i class="fas fa-calendar-alt text-emerald-500 mr-2"></i>
                        <h3 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Cronología</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Fecha de Entrega</label>
                            <input type="date" name="assigned_date" value="{{ date('Y-m-d') }}" required 
                                class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-xl py-2 px-3 text-xs font-bold focus:ring-2 focus:ring-blue-medium transition-all">
                        </div>

                        @if(auth()->user()->company->hasModule('loans'))
                        <div class="pt-4 border-t border-gray-50 dark:border-gray-700">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider group-hover:text-blue-medium transition-colors">¿Préstamo Temporal?</span>
                                <input type="checkbox" name="is_loan" value="1" id="is_loan" class="sr-only peer" onchange="toggleReturnDate(this)" {{ old('is_loan') ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-medium relative"></div>
                            </label>

                            <div id="return_date_container" class="mt-4 {{ old('is_loan') ? '' : 'hidden' }}">
                                <label class="block text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-2">Fecha de Devolución</label>
                                <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date') }}" 
                                    class="w-full bg-indigo-50 dark:bg-indigo-900/20 border-blue-medium/100 dark:border-blue-medium/900/30 rounded-xl py-2 px-3 text-xs font-black text-blue-medium focus:ring-2 focus:ring-blue-medium transition-all">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="p-6 bg-blue-50/50 dark:bg-blue-900/10 rounded-3xl border border-blue-100 dark:border-blue-900/30">
                    <p class="text-[10px] text-blue-700 dark:text-blue-400 font-medium leading-relaxed italic">
                        <i class="fas fa-shield-alt mr-1"></i> Al confirmar, se generará una entrada en el historial de trazabilidad del activo y del colaborador automáticamente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
            <a href="{{ route('assets.show', $asset->id) }}" 
                class="w-full md:w-auto text-center px-10 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all active:scale-95">
                Cancelar
            </a>
            <button type="submit" 
                class="w-full md:w-auto px-16 py-4 bg-blue-medium hover:bg-blue-dark text-white font-black rounded-2xl transition-all shadow-xl shadow-blue-medium/25 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center tracking-wide uppercase text-sm">
                <i class="fas fa-check-circle mr-2 text-lg"></i> Confirmar Asignación
            </button>
        </div>
    </form>
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
