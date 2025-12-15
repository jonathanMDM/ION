@extends('layouts.superadmin')

@section('page-title', 'Validación de Cliente para Soporte')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Validation Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-user-check mr-2 text-blue-600"></i>Validar Identidad del Cliente
        </h2>
        <p class="text-gray-600 mb-6">
            Ingrese el NIT de la empresa o el correo electrónico del usuario para validar su identidad antes de brindar soporte telefónico.
        </p>

        <form action="{{ route('superadmin.support.validate') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Validación</label>
                    <select name="validation_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="nit" {{ old('validation_type') == 'nit' ? 'selected' : '' }}>NIT de Empresa</option>
                        <option value="email" {{ old('validation_type') == 'email' ? 'selected' : '' }}>Correo Electrónico</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dato a Validar</label>
                    <input type="text" name="validation_value" value="{{ old('validation_value') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej: 900123456 o admin@empresa.com" required>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition duration-200">
                    <i class="fas fa-search mr-2"></i>Validar Cliente
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    @if(isset($company))
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg shadow-md p-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-green-800">
                <i class="fas fa-check-circle mr-2"></i>Cliente Validado
            </h3>
            <span class="px-4 py-1 rounded-full text-sm font-bold {{ $company->status == 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ $company->status == 'active' ? 'ACTIVO' : 'INACTIVO' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Company Details -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Datos de la Empresa</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500 block">Nombre Legal</span>
                        <span class="font-medium text-gray-900">{{ $company->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">NIT / Identificación</span>
                        <span class="font-medium text-gray-900">{{ $company->nit }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">Dirección</span>
                        <span class="font-medium text-gray-900">{{ $company->address ?? 'No registrada' }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">Teléfono</span>
                        <span class="font-medium text-gray-900">{{ $company->phone ?? 'No registrado' }}</span>
                    </div>
                </div>
            </div>

            <!-- Admin User Details -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Contacto Principal (Admin)</h4>
                @if($user)
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500 block">Nombre Completo</span>
                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">Correo Electrónico</span>
                        <span class="font-medium text-gray-900">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block">Último Acceso</span>
                        <span class="font-medium text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i A') : 'Nunca' }}</span>
                    </div>
                </div>
                @else
                <div class="text-yellow-600 bg-yellow-50 p-3 rounded">
                    <i class="fas fa-exclamation-triangle mr-2"></i>No se encontró un usuario administrador principal.
                </div>
                @endif
            </div>
        </div>

        <!-- Subscription & Support Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Suscripción</p>
                        <p class="text-lg font-bold text-gray-900">
                            @if($company->subscription_expires_at)
                                {{ $company->subscription_expires_at->format('d/m/Y') }}
                            @else
                                Sin límite
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-calendar-alt text-3xl text-blue-500"></i>
                </div>
                @if($company->subscription_expires_at)
                    @php
                        $daysLeft = now()->diffInDays($company->subscription_expires_at, false);
                    @endphp
                    <p class="text-xs mt-2 {{ $daysLeft < 30 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $daysLeft > 0 ? $daysLeft . ' días restantes' : 'Expirada' }}
                    </p>
                @endif
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Usuarios</p>
                        <p class="text-lg font-bold text-gray-900">{{ $company->users()->count() }} / {{ $company->user_limit }}</p>
                    </div>
                    <i class="fas fa-users text-3xl text-green-500"></i>
                </div>
                <p class="text-xs mt-2 text-gray-600">
                    {{ $company->user_limit - $company->users()->count() }} disponibles
                </p>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Activos</p>
                        <p class="text-lg font-bold text-gray-900">{{ $company->assets()->count() }}</p>
                    </div>
                    <i class="fas fa-box text-3xl text-purple-500"></i>
                </div>
                <p class="text-xs mt-2 text-gray-600">
                    Registrados en el sistema
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('superadmin.companies.show', $company->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                <i class="fas fa-eye mr-2"></i>Ver Detalles Completos
            </a>
            @if($company->status == 'active')
            <a href="{{ route('superadmin.support.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                <i class="fas fa-headset mr-2"></i>Iniciar Soporte
            </a>
            @else
            <button disabled class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                <i class="fas fa-ban mr-2"></i>Cliente Inactivo
            </button>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
