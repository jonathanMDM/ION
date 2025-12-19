@extends('layouts.superadmin')

@section('page-title', 'Nuevo Webhook')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow transition-colors">
    <form action="{{ route('superadmin.webhooks.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                Nombre
            </label>
            <input type="text" name="name" id="name" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required placeholder="Ej: Integración Slack">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="url">
                URL del Payload
            </label>
            <input type="url" name="url" id="url" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" required placeholder="https://api.example.com/webhook">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="secret">
                Secreto (Opcional)
            </label>
            <input type="text" name="secret" id="secret" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-white bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-colors" placeholder="Para firmar el payload (HMAC)">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                Eventos a escuchar
            </label>
            <div class="flex flex-col gap-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="events[]" value="asset.created" class="form-checkbox h-5 w-5 text-gray-800">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Activo Creado (asset.created)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="events[]" value="asset.updated" class="form-checkbox h-5 w-5 text-gray-800">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Activo Actualizado (asset.updated)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="events[]" value="asset.assigned" class="form-checkbox h-5 w-5 text-gray-800">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Activo Asignado (asset.assigned)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="events[]" value="maintenance.created" class="form-checkbox h-5 w-5 text-gray-800">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Mantenimiento Registrado (maintenance.created)</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('superadmin.webhooks.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white font-bold transition-colors">
                Cancelar
            </a>
            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Crear Webhook
            </button>
        </div>
    </form>
</div>
@endsection
