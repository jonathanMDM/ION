@extends('layouts.app')

@section('page-title', 'Logs de Actividad')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Registro de Actividades del Sistema</h2>
    <p class="text-gray-600 mt-1">Historial completo de cambios realizados en el sistema</p>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h3>
    <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
            <select name="user_id" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Todos los Usuarios</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
            <select name="model" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Todos los Modelos</option>
                @foreach($models as $model)
                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                        {{ class_basename($model) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
            <select name="action" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Todas las Acciones</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="md:col-span-5 flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded">
                <i class="fas fa-filter mr-2"></i>Aplicar Filtros
            </button>
            @if(request()->hasAny(['user_id', 'model', 'action', 'date_from', 'date_to']))
                <a href="{{ route('activity-logs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-times mr-2"></i>Limpiar
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Total de Registros</p>
        <p class="text-2xl font-bold text-gray-800">{{ $logs->total() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Creaciones</p>
        <p class="text-2xl font-bold text-[#5483B3]">{{ $logs->where('action', 'created')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Actualizaciones</p>
        <p class="text-2xl font-bold text-gray-800">{{ $logs->where('action', 'updated')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Eliminaciones</p>
        <p class="text-2xl font-bold text-red-600">{{ $logs->where('action', 'deleted')->count() }}</p>
    </div>
</div>

<!-- Tabla de Logs -->
<div class="bg-white shadow-md rounded overflow-x-auto">
    <table class="min-w-full w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Usuario</th>
                <th class="py-3 px-6 text-left">Acción</th>
                <th class="py-3 px-6 text-left">Modelo</th>
                <th class="py-3 px-6 text-left">ID</th>
                <th class="py-3 px-6 text-left">Cambios</th>
                <th class="py-3 px-6 text-left">IP</th>
                <th class="py-3 px-6 text-left">Fecha</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse($logs as $log)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left">
                    <span class="font-medium">{{ $log->user->name ?? 'Sistema' }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="px-2 py-1 rounded text-xs
                        {{ $log->action == 'created' ? 'bg-[#C1E8FF] text-[#052659]' : '' }}
                        {{ $log->action == 'updated' ? 'bg-gray-200 text-gray-900' : '' }}
                        {{ $log->action == 'deleted' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($log->action) }}
                    </span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ class_basename($log->model) }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="font-mono text-xs">#{{ $log->model_id }}</span>
                </td>
                <td class="py-3 px-6 text-left">
                    @if($log->changes)
                        <button onclick="toggleChanges('changes-{{ $log->id }}')" class="text-gray-800 hover:text-gray-900 text-xs">
                            <i class="fas fa-eye mr-1"></i>Ver cambios
                        </button>
                        <div id="changes-{{ $log->id }}" class="hidden mt-2 bg-gray-50 p-2 rounded text-xs max-w-md overflow-auto">
                            <pre class="whitespace-pre-wrap">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @else
                        <span class="text-gray-400 text-xs">Sin cambios</span>
                    @endif
                </td>
                <td class="py-3 px-6 text-left">
                    <span class="font-mono text-xs">{{ $log->ip_address }}</span>
                </td>
                <td class="py-3 px-6 text-left whitespace-nowrap">
                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-gray-500">
                    No se encontraron registros de actividad.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6">
    {{ $logs->links() }}
</div>

<script>
function toggleChanges(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}
</script>
@endsection
