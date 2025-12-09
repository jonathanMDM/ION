@extends('layouts.superadmin')

@section('page-title', 'Nueva Empresa')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Información de la Empresa</h3>
            <p class="mt-1 text-sm text-gray-500">Registra una nueva empresa y su administrador inicial.</p>
        </div>
        
        <form action="{{ route('superadmin.companies.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Company Details -->
                <div class="col-span-2">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">Datos de la Empresa</h4>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nit" class="block text-sm font-medium text-gray-700 mb-1">NIT / RUC / ID Fiscal</label>
                    <input type="text" name="nit" id="nit" value="{{ old('nit') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('nit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="user_limit" class="block text-sm font-medium text-gray-700 mb-1">Límite de Usuarios *</label>
                    <input type="number" name="user_limit" id="user_limit" value="{{ old('user_limit', 10) }}" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('user_limit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Número máximo de usuarios que puede crear esta empresa</p>
                </div>

                <div>
                    <label for="subscription_expires_at" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Expiración</label>
                    <input type="date" name="subscription_expires_at" id="subscription_expires_at" value="{{ old('subscription_expires_at') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
                    @error('subscription_expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Dejar vacío para membresía sin límite de tiempo</p>
                </div>

                <!-- Admin User Details -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">Administrador Inicial</h4>
                </div>

                <div>
                    <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Administrador *</label>
                    <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('admin_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">Email del Administrador *</label>
                    <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                    <input type="password" name="admin_password" id="admin_password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200" required minlength="8">
                    @error('admin_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('superadmin.companies.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow">
                    Cancelar
                </a>
                <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow">
                    Crear Empresa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
