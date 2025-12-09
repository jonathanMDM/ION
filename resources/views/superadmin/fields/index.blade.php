@extends('layouts.superadmin')

@section('page-title', 'Configuración de Campos')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Visibilidad de Campos por Rol</h2>
        <p class="text-gray-600 text-sm mt-1">
            Controla qué campos son visibles para cada rol de usuario. Los Superadmins siempre ven todos los campos.
        </p>
    </div>

    <form action="{{ route('superadmin.fields.update') }}" method="POST">
        @csrf
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Campo
                        </th>
                        @foreach($roles as $role)
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ ucfirst($role) }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($availableFields as $fieldName => $fieldLabel)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $fieldLabel }}</div>
                            <div class="text-xs text-gray-500">{{ $fieldName }}</div>
                            <input type="hidden" name="labels[{{ $fieldName }}]" value="{{ $fieldLabel }}">
                        </td>
                        @foreach($roles as $index => $role)
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $config = $configs->get($role)?->where('field_name', $fieldName)->first();
                                $isVisible = $config ? $config->is_visible : true;
                                $inputName = "configs[{$fieldName}_{$role}]";
                            @endphp
                            
                            <input type="hidden" name="{{ $inputName }}[role]" value="{{ $role }}">
                            <input type="hidden" name="{{ $inputName }}[field_name]" value="{{ $fieldName }}">
                            <input type="hidden" name="{{ $inputName }}[is_visible]" value="0">
                            
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="{{ $inputName }}[is_visible]" 
                                       value="1" 
                                       class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition duration-150 ease-in-out"
                                       {{ $isVisible ? 'checked' : '' }}>
                            </label>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">
                <i class="fas fa-save mr-2"></i>Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
