@extends('layouts.app')

@section('page-title', 'Proveedores')

@section('content')
<div class="flex flex-col md:flex-row md:items-center mb-6 gap-4">
    <h1 class="text-3xl font-bold text-gray-800 md:flex-1">Proveedores</h1>
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_suppliers'))
    <a href="{{ route('suppliers.create') }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap shrink-0">
        <i class="fas fa-plus mr-2"></i>Agregar Proveedor
    </a>
    @endif
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left whitespace-nowrap">Nombre</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Contacto</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Email</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Teléfono</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Activos</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($suppliers as $supplier)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left">
                    <span class="font-medium">{{ $supplier->name }}</span>
                </td>
                <td class="py-3 px-6 text-left">{{ $supplier->contact_name ?? '-' }}</td>
                <td class="py-3 px-6 text-left">{{ $supplier->email ?? '-' }}</td>
                <td class="py-3 px-6 text-left">{{ $supplier->phone ?? '-' }}</td>
                <td class="py-3 px-6 text-center">
                    <span class="bg-gray-200 text-gray-900 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $supplier->assets_count }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center">
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_suppliers'))
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="w-4 mr-2 transform hover:text-gray-600 hover:scale-110" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
