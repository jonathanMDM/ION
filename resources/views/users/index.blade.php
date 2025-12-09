@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row md:items-center mb-6 gap-4">
    <h1 class="text-3xl font-bold text-gray-800 md:flex-1">Gestión de Usuarios</h1>
    <a href="{{ route('users.create') }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded text-center whitespace-nowrap shrink-0">
        <i class="fas fa-plus mr-2"></i>Agregar Usuario
    </a>
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left whitespace-nowrap">Nombre</th>
                <th class="py-3 px-6 text-left whitespace-nowrap">Email</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Rol</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Estado</th>
                <th class="py-3 px-6 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($users as $user)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left">
                    <span class="font-medium">{{ $user->name }}</span>
                </td>
                <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                <td class="py-3 px-6 text-center">
                    @if($user->isAdmin())
                        <span class="bg-gray-200 text-gray-900 px-3 py-1 rounded-full text-xs font-semibold">Admin</span>
                    @elseif($user->isEditor())
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Editor</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Visor</span>
                    @endif
                </td>
                <td class="py-3 px-6 text-center">
                    @if($user->is_active)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Activo</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Inactivo</span>
                    @endif
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="w-4 mr-2 transform hover:text-gray-600 hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        @if($user->id !== Auth::id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
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
