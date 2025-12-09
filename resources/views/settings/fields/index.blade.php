@extends('layouts.app')

@section('page-title', 'Gestión de Campos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestión de Campos y Visibilidad</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Create Custom Field -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Crear Nuevo Campo</h3>
            <form action="{{ route('settings.fields.store') }}" method="POST">
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
                    @forelse($customFields as $field)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <span class="font-medium text-gray-800">{{ $field->label }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $field->type }})</span>
                        </div>
                        <form action="{{ route('settings.fields.destroy', $field) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Se perderán los datos asociados a este campo.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                        </form>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm italic">No hay campos personalizados.</li>
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
                            <form action="{{ route('settings.fields.visibility') }}" method="POST">
                                @csrf
                                <td class="p-2">
                                    <select name="field_key" class="w-full border rounded p-1 text-sm">
                                        <optgroup label="Campos del Sistema">
                                            @foreach($systemFields as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Campos Personalizados">
                                            @foreach($customFields as $field)
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
                        
                        <!-- Existing Rules List (Simplified for now, ideally would list all rules) -->
                        <tr>
                            <td colspan="4" class="p-2 text-xs text-gray-500 text-center italic">
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
