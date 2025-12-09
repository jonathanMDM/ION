@extends('layouts.superadmin')

@section('page-title', 'Gestión de Empresas')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
    <p class="text-gray-600 md:flex-1">Administra las empresas registradas en la plataforma.</p>
    <a href="{{ route('superadmin.companies.create') }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow whitespace-nowrap text-center shrink-0">
        <i class="fas fa-plus mr-2"></i>Nueva Empresa
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estadísticas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($companies as $company)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $company->name }}</div>
                            <div class="text-sm text-gray-500">NIT: {{ $company->nit ?? 'N/A' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $company->email }}</div>
                    <div class="text-sm text-gray-500">{{ $company->phone ?? 'Sin teléfono' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><i class="fas fa-users mr-1 text-gray-400"></i> {{ $company->users_count }} Usuarios</div>
                    <div class="text-sm text-gray-500"><i class="fas fa-box mr-1 text-gray-400"></i> {{ $company->assets_count }} Activos</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($company->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    @if($company->users->where('role', 'admin')->first())
                    <form action="{{ route('superadmin.users.impersonate', $company->users->where('role', 'admin')->first()) }}" method="POST" class="inline-block mr-3">
                        @csrf
                        <button type="submit" class="text-purple-600 hover:text-purple-900" title="Ingresar como Soporte">
                            <i class="fas fa-headset"></i>
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('superadmin.backups.create', $company) }}" method="POST" class="inline-block mr-3">
                        @csrf
                        <button type="submit" class="text-green-600 hover:text-green-900" title="Crear Backup">
                            <i class="fas fa-database"></i>
                        </button>
                    </form>
                    <a href="{{ route('superadmin.companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('superadmin.companies.fields.index', $company) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Gestionar Campos">
                        <i class="fas fa-sliders-h"></i>
                    </a>
                    <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta empresa? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                    <i class="fas fa-building text-4xl mb-3 text-gray-300"></i>
                    <p>No hay empresas registradas.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $companies->links() }}
    </div>
</div>
@endsection
