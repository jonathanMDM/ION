@extends('layouts.app')

@section('page-title', 'Nuevo Empleado')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('employees.index') }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Empleados
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Registrar Nuevo Empleado</h2>

        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Corporativo *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                    <input type="text" name="department" value="{{ old('department') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cargo / Posición</label>
                    <input type="text" name="position" value="{{ old('position') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('employees.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded">
                    Guardar Empleado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
