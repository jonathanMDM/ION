@extends('layouts.app')

@section('page-title', 'Editar Centro de Costo')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Centro de Costo</h2>
            <p class="text-gray-600 mt-1">Actualiza la información del centro de costo</p>
        </div>
        <a href="{{ route('cost-centers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('cost-centers.update', $costCenter) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Código -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Código <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="code" 
                       id="code" 
                       value="{{ old('code', $costCenter->code) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('code') border-red-500 @enderror"
                       required>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $costCenter->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Presupuesto -->
            <div>
                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                    Presupuesto
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                    <input type="number" 
                           name="budget" 
                           id="budget" 
                           value="{{ old('budget', $costCenter->budget) }}"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('budget') border-red-500 @enderror">
                </div>
                @error('budget')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Responsable -->
            <div>
                <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Responsable
                </label>
                <select name="manager_id" 
                        id="manager_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('manager_id') border-red-500 @enderror">
                    <option value="">Seleccionar responsable</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id', $costCenter->manager_id) == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} ({{ ucfirst($manager->role) }})
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $costCenter->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Centro de costo activo</span>
                </label>
            </div>
        </div>

        <!-- Descripción -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Descripción
            </label>
            <textarea name="description" 
                      id="description" 
                      rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $costCenter->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('cost-centers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                <i class="fas fa-save mr-2"></i>Actualizar Centro de Costo
            </button>
        </div>
    </form>
</div>
@endsection
