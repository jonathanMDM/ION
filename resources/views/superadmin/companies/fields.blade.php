@extends('layouts.superadmin')

@section('page-title', 'Campos Personalizados - ' . $company->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('superadmin.companies.index') }}" class="text-[#5483B3] hover:text-indigo-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Empresas
        </a>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestión de Campos - {{ $company->name }}</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-[#5483B3]/400 text-[#052659] px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Create Custom Field -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Crear Nuevo Campo</h3>
            <form action="{{ route('superadmin.companies.fields.store', $company) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Campo (Etiqueta)</label>
                    <input type="text" name="label" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Dato</label>
                    <select name="type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="document.getElementById('options-container').classList.toggle('hidden', this.value !== 'select')">
                        <option value="text">Texto</option>
                        <option value="number">Número</option>
                        <option value="date">Fecha</option>
                        <option value="textarea">Área de Texto</option>
                        <option value="select">Selección (Lista)</option>
                    </select>
                </div>

                <div id="options-container" class="mb-4 hidden">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Opciones (separadas por coma)</label>
                    <input type="text" name="options" placeholder="Opción 1, Opción 2, Opción 3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_required" value="1" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">¿Es obligatorio?</span>
                    </label>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Crear Campo
                </button>
            </form>

            <!-- List Custom Fields -->
            <div class="mt-8">
                <h4 class="font-semibold text-gray-600 mb-2">Campos Personalizados Existentes</h4>
                <ul class="divide-y divide-gray-200">
                    @forelse($fields as $field)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <span class="font-medium text-gray-800">{{ $field->label }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $field->type }})</span>
                            @if($field->is_required)
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded ml-2">Obligatorio</span>
                            @endif
                        </div>
                        <form action="{{ route('superadmin.companies.fields.destroy', [$company, $field]) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Se perderán los datos asociados a este campo.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                        </form>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm italic py-3">No hay campos personalizados.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Visibility Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Configurar Visibilidad</h3>
            <p class="text-sm text-gray-600 mb-4">
                Controla quién puede ver cada campo. Puedes configurar por Rol o por Usuario específico.
            </p>

            @php
                $systemFields = [
                    'municipality_plate' => 'Placa Municipio',
                    'model' => 'Modelo',
                    'serial_number' => 'Número de Serie',
                    'purchase_price' => 'Precio de Compra',
                ];
                $users = \App\Models\User::where('company_id', $company->id)->get();
            @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="p-2">Campo</th>
                            <th class="p-2">Rol / Usuario</th>
                            <th class="p-2 text-center">Visible</th>
                            <th class="p-2">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Form Row -->
                        <tr class="bg-blue-50">
                            <form action="{{ route('superadmin.companies.fields.visibility', $company) }}" method="POST">
                                @csrf
                                <td class="p-2">
                                    <select name="field_key" class="w-full border rounded p-1 text-sm">
                                        <optgroup label="Campos del Sistema">
                                            @foreach($systemFields as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Campos Personalizados">
                                            @foreach($fields as $field)
                                            <option value="{{ $field->name }}">{{ $field->label }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </td>
                                <td class="p-2">
                                    <select name="target" class="w-full border rounded p-1 text-sm" onchange="
                                        if(this.value.startsWith('role:')) {
                                            document.getElementById('role_input').value = this.value.split(':')[1];
                                            document.getElementById('user_input').value = '';
                                        } else {
                                            document.getElementById('user_input').value = this.value.split(':')[1];
                                            document.getElementById('role_input').value = '';
                                        }
                                    ">
                                        <optgroup label="Roles">
                                            <option value="role:admin">Admin</option>
                                            <option value="role:editor">Editor</option>
                                            <option value="role:viewer">Visor</option>
                                        </optgroup>
                                        <optgroup label="Usuarios Específicos">
                                            @foreach($users as $user)
                                            <option value="user:{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <input type="hidden" name="role" id="role_input" value="admin">
                                    <input type="hidden" name="user_id" id="user_input" value="">
                                </td>
                                <td class="p-2 text-center">
                                    <select name="is_visible" class="border rounded p-1 text-sm">
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold">Guardar</button>
                                </td>
                            </form>
                        </tr>
                        
                        <!-- Existing Rules List -->
                        @php
                            $visibilityRules = \App\Models\FieldVisibility::where('company_id', $company->id)->get();
                        @endphp
                        
                        @if($visibilityRules->count() > 0)
                        <tr>
                            <td colspan="4" class="p-2">
                                <div class="mt-4">
                                    <h4 class="font-semibold text-gray-700 mb-2">Reglas de Visibilidad Activas:</h4>
                                    <div class="space-y-2">
                                        @foreach($visibilityRules as $rule)
                                        <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-800">{{ $rule->field_key }}</span>
                                                <span class="text-xs text-gray-500 mx-2">→</span>
                                                @if($rule->user_id)
                                                    @php
                                                        $ruleUser = \App\Models\User::find($rule->user_id);
                                                    @endphp
                                                    <span class="text-sm text-blue-600">Usuario: {{ $ruleUser ? $ruleUser->name : 'Desconocido' }}</span>
                                                @elseif($rule->role)
                                                    <span class="text-sm text-purple-600">Rol: {{ ucfirst($rule->role) }}</span>
                                                @else
                                                    <span class="text-sm text-gray-600">Global</span>
                                                @endif
                                                <span class="text-xs mx-2">→</span>
                                                @if($rule->is_visible)
                                                    <span class="text-xs bg-[#C1E8FF] text-[#052659] px-2 py-1 rounded">Visible</span>
                                                @else
                                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Oculto</span>
                                                @endif
                                            </div>
                                            <div class="flex gap-2">
                                                <!-- Toggle Visibility -->
                                                <form action="{{ route('superadmin.companies.fields.visibility', $company) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="field_key" value="{{ $rule->field_key }}">
                                                    <input type="hidden" name="user_id" value="{{ $rule->user_id }}">
                                                    <input type="hidden" name="role" value="{{ $rule->role }}">
                                                    <input type="hidden" name="is_visible" value="{{ $rule->is_visible ? '0' : '1' }}">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs">
                                                        {{ $rule->is_visible ? 'Ocultar' : 'Mostrar' }}
                                                    </button>
                                                </form>
                                                <!-- Delete Rule -->
                                                <form action="{{ route('superadmin.companies.fields.visibility.delete', [$company, $rule]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta regla?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="4" class="p-2 text-xs text-gray-500 text-center italic">
                                No hay reglas de visibilidad configuradas. Todos los campos son visibles por defecto.
                            </td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td colspan="4" class="p-2 text-xs text-gray-500 text-center italic border-t mt-2 pt-2">
                                Las reglas se aplican en orden: Usuario > Rol > Global.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
