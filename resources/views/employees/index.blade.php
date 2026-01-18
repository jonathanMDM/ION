@extends('layouts.app')

@section('page-title', 'Empleados')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
    <h2 class="text-2xl font-bold text-gray-800 md:flex-1">Empleados</h2>
    <a href="{{ route('employees.create') }}" 
        class="text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center justify-center whitespace-nowrap shrink-0 border-none cursor-pointer"
        style="background: linear-gradient(135deg, #5483B3 0%, #052659 100%); box-shadow: 0 4px 12px rgba(84, 131, 179, 0.3);">
        <i class="fas fa-user-plus mr-2"></i>Nuevo Empleado
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('employees.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email..." class="w-full pl-10 border-gray-300 rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
            <select name="department" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Todos</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" 
                class="text-white font-bold py-2 px-6 rounded-lg w-full transition-all border-none cursor-pointer"
                style="background: #1e293b; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Tabla -->
<div class="bg-white shadow-md rounded overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left whitespace-nowrap">Nombre</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Email</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Departamento</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Cargo</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Estado</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse($employees as $employee)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="mr-2">
                            <div class="bg-gray-200 text-gray-800 rounded-full w-8 h-8 flex items-center justify-center font-bold">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                        </div>
                        <span class="font-medium">{{ $employee->full_name }}</span>
                    </div>
                </td>
                <td class="py-3 px-6 text-left">{{ $employee->email }}</td>
                <td class="py-3 px-6 text-left">{{ $employee->department ?? '-' }}</td>
                <td class="py-3 px-6 text-left">{{ $employee->position ?? '-' }}</td>
                <td class="py-3 px-6 text-center">
                    <span class="px-2 py-1 rounded text-xs {{ $employee->status == 'active' ? 'bg-blue-lightest text-blue-dark' : 'bg-red-100 text-red-800' }}">
                        {{ $employee->status == 'active' ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center">
                        <a href="{{ route('employees.show', $employee->id) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('employees.edit', $employee->id) }}" class="w-4 mr-2 transform hover:text-gray-600 hover:scale-110">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este empleado?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500">
                    No se encontraron empleados registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $employees->links() }}
</div>
@endsection
