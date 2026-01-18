@extends('layouts.app')

@section('page-title', 'Detalle de Movimiento')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('asset-movements.index') }}" class="text-gray-800 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Volver al Historial
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Detalle del Movimiento</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información del Activo -->
            <div class="col-span-2 bg-gray-50 p-4 rounded">
                <h3 class="font-bold text-gray-700 mb-3">Activo</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">ID Único:</label>
                        <p class="font-medium">{{ $movement->asset->custom_id }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Nombre:</label>
                        <p class="font-medium">{{ $movement->asset->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Movimiento -->
            <div>
                <label class="font-bold text-gray-700">Ubicación Origen:</label>
                <p class="mt-1">
                    <span class="px-3 py-2 bg-red-100 text-red-800 rounded inline-block">
                        {{ $movement->fromLocation->name ?? 'Sin ubicación previa' }}
                    </span>
                </p>
            </div>

            <div>
                <label class="font-bold text-gray-700">Ubicación Destino:</label>
                <p class="mt-1">
                    <span class="px-3 py-2 bg-blue-lightest text-blue-dark rounded inline-block">
                        {{ $movement->toLocation->name }}
                    </span>
                </p>
            </div>

            <div>
                <label class="font-bold text-gray-700">Usuario:</label>
                <p class="mt-1">{{ $movement->user->name }}</p>
            </div>

            <div>
                <label class="font-bold text-gray-700">Fecha y Hora:</label>
                <p class="mt-1">{{ $movement->moved_at->format('d/m/Y H:i:s') }}</p>
            </div>

            @if($movement->reason)
            <div class="col-span-2">
                <label class="font-bold text-gray-700">Razón del Movimiento:</label>
                <p class="mt-1 bg-gray-100 p-3 rounded">{{ $movement->reason }}</p>
            </div>
            @endif
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('assets.show', $movement->asset->id) }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-box mr-2"></i>Ver Activo
            </a>
        </div>
    </div>
</div>
@endsection
