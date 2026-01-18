@extends('layouts.superadmin')

@section('page-title', 'Logs de Actividad Global')

@section('content')
<div class="mb-6">
    <p class="text-gray-600">Monitoreo completo de todas las acciones realizadas en el sistema.</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('superadmin.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
            <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#5483B3]/500 focus:ring focus:ring-indigo-200">
                <option value="">Todos los usuarios</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
            <select name="company_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#5483B3]/500 focus:ring focus:ring-indigo-200">
                <option value="">Todas las empresas</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Acción</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Buscar acción..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#5483B3]/500 focus:ring focus:ring-indigo-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#5483B3]/500 focus:ring focus:ring-indigo-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#5483B3]/500 focus:ring focus:ring-indigo-200">
        </div>

        <div class="md:col-span-3 lg:col-span-5 flex gap-2">
            <button type="submit" class="bg-[#5483B3] hover:bg-[#052659] text-white font-bold py-2 px-4 rounded shadow">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
            <a href="{{ route('superadmin.activity-logs') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow">
                <i class="fas fa-times mr-2"></i>Limpiar
            </a>
            <a href="{{ route('superadmin.activity-logs.export', request()->all()) }}" class="bg-[#5483B3] hover:bg-[#052659] text-white font-bold py-2 px-4 rounded shadow ml-auto">
                <i class="fas fa-file-export mr-2"></i>Exportar CSV
            </a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center text-[#5483B3]">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $log->user ? $log->user->name : 'Usuario eliminado' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user ? $log->user->email : 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($log->user && $log->user->company)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-900">
                                {{ $log->user->company->name }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">Sin empresa</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <span class="font-medium block">{{ $log->action }}</span>
                        @if($log->changes)
                            <div class="mt-1 text-xs text-gray-500 bg-gray-50 p-2 rounded border border-gray-200 max-w-xs overflow-x-auto">
                                @if(isset($log->changes['before']) || isset($log->changes['after']))
                                    {{-- Update Event --}}
                                    @if(isset($log->changes['before']) && count($log->changes['before']) > 0)
                                        <div class="mb-1">
                                            <span class="font-semibold text-red-600">Antes:</span>
                                            @foreach($log->changes['before'] as $key => $value)
                                                <div>{{ $key }}: {{ is_array($value) ? json_encode($value) : $value }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if(isset($log->changes['after']) && count($log->changes['after']) > 0)
                                        <div>
                                            <span class="font-semibold text-[#5483B3]">Después:</span>
                                            @foreach($log->changes['after'] as $key => $value)
                                                <div>{{ $key }}: {{ is_array($value) ? json_encode($value) : $value }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    {{-- Create/Delete Event --}}
                                    @foreach($log->changes as $key => $value)
                                        <div><span class="font-semibold">{{ $key }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->model_type ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->ip_address ?? 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-3 text-gray-300"></i>
                        <p>No hay logs que coincidan con los filtros aplicados.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $logs->links() }}
    </div>
</div>
@endsection
