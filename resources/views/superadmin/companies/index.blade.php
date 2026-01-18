@extends('layouts.superadmin')

@section('page-title', 'Gestión de Empresas')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
    <p class="md:flex-1" style="color: #B0C4C9;">Administra las empresas registradas en la plataforma.</p>
    <a href="{{ route('superadmin.companies.create') }}" class="font-bold py-2 px-4 rounded-xl shadow-lg whitespace-nowrap text-center shrink-0 transition-transform hover:scale-105" style="background: var(--color-burnt-orange); color: #FFFFFF; box-shadow: 0 4px 15px rgba(254, 126, 60, 0.4);">
        <i class="fas fa-plus mr-2"></i>Nueva Empresa
    </a>
</div>

<div class="shadow-xl rounded-2xl overflow-hidden" style="background: var(--bg-card); border: 1px solid var(--color-blue-lagoon);">
    <div class="overflow-x-auto">
    <table class="min-w-full">
        <thead style="background: rgba(14, 104, 115, 0.2);">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--color-burnt-orange);">Empresa</th>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color: #B0C4C9;">Contacto</th>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color: #B0C4C9;">Estadísticas</th>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color: #B0C4C9;">Estado</th>
                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider" style="color: #B0C4C9;">Alertas Stock</th>
                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="color: #B0C4C9;">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y" style="divide-color: rgba(14, 104, 115, 0.1);">
            @forelse($companies as $company)
            <tr class="hover:bg-white/5 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-xl flex items-center justify-center shadow-lg" style="background: rgba(14, 104, 115, 0.2); color: var(--color-burnt-orange); border: 1px solid var(--color-blue-lagoon);">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-bold" style="color: #FFFFFF;">{{ $company->name }}</div>
                            <div class="text-xs" style="color: var(--color-blue-lagoon);">NIT: {{ $company->nit ?? 'N/A' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm" style="color: #FFFFFF;">{{ $company->email }}</div>
                    <div class="text-xs" style="color: #B0C4C9;">{{ $company->phone ?? 'Sin teléfono' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm" style="color: #FFFFFF;"><i class="fas fa-users mr-1" style="color: var(--color-blue-lagoon);"></i> {{ $company->users_count }} Usuarios</div>
                    <div class="text-sm" style="color: #B0C4C9;"><i class="fas fa-box mr-1" style="color: var(--color-blue-lagoon);"></i> {{ $company->assets_count }} Activos</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $company->status === 'active' ? 'bg-[#C1E8FF] text-[#052659]' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($company->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <form action="{{ route('superadmin.companies.toggle-low-stock-alerts', $company) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 transition-all focus:outline-none" style="{{ $company->low_stock_alerts_enabled ? 'background-color: var(--color-burnt-orange);' : 'background-color: #4B5563;' }}" title="{{ $company->low_stock_alerts_enabled ? 'Desactivar alertas' : 'Activar alertas' }}">
                            <span class="sr-only">Toggle low stock alerts</span>
                            <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $company->low_stock_alerts_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </form>
                    <div class="text-xs mt-1" style="color: #B0C4C9;">
                        {{ $company->low_stock_alerts_enabled ? 'Activas' : 'Inactivas' }}
                    </div>
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
                        <button type="submit" class="text-[#5483B3] hover:text-green-900" title="Crear Backup">
                            <i class="fas fa-database"></i>
                        </button>
                    </form>
                    <a href="{{ route('superadmin.companies.show', $company) }}" class="text-[#5483B3] hover:text-green-900 mr-3" title="Ver Detalles y Facturación">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('superadmin.companies.edit', $company) }}" class="text-[#5483B3] hover:text-indigo-900 mr-3" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('superadmin.companies.fields.index', $company) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Gestionar Campos">
                        <i class="fas fa-sliders-h"></i>
                    </a>
                    <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta empresa? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="hover:text-red-400 transition-colors" style="color: var(--color-lust);" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center" style="color: #B0C4C9;">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: rgba(14, 104, 115, 0.1);">
                        <i class="fas fa-building text-3xl" style="color: var(--color-blue-lagoon);"></i>
                    </div>
                    <p class="text-lg font-medium">No hay empresas registradas.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4 border-t" style="border-color: rgba(14, 104, 115, 0.2);">
        {{ $companies->links() }}
    </div>
</div>
@endsection
