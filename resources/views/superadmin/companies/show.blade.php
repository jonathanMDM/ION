@extends('layouts.superadmin')

@section('page-title', 'Detalles de la Empresa')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $company->name }}</h2>
            <p class="text-gray-600 mt-1">Información detallada y gestión de la empresa</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('superadmin.companies.edit', $company) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-bold">
                <i class="fas fa-edit mr-2"></i>Editar Empresa
            </a>
            <a href="{{ route('superadmin.companies.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded font-bold">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<!-- Company Info Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de la Empresa</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">NIT</p>
            <p class="font-semibold">{{ $company->nit ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-semibold">{{ $company->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Teléfono</p>
            <p class="font-semibold">{{ $company->phone ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Dirección</p>
            <p class="font-semibold">{{ $company->address ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Subscription & Invoicing -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-start mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Suscripción y Facturación</h3>
        <a href="{{ route('superadmin.companies.invoices.create', $company->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-bold">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Registrar Pago y Enviar Factura
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-3 bg-gray-50 rounded border border-gray-200">
            <span class="text-xs text-gray-500 uppercase font-bold">Estado Suscripción</span>
            <div class="flex items-center mt-1">
                @if($company->subscription_expires_at && $company->subscription_expires_at->isPast())
                    <span class="text-red-600 font-bold"><i class="fas fa-exclamation-circle mr-1"></i> EXPIRADA</span>
                @else
                    <span class="text-green-600 font-bold"><i class="fas fa-check-circle mr-1"></i> AL DÍA</span>
                @endif
            </div>
        </div>
        <div class="p-3 bg-gray-50 rounded border border-gray-200">
            <span class="text-xs text-gray-500 uppercase font-bold">Fecha de Expiración</span>
            <div class="mt-1 font-bold">
                {{ $company->subscription_expires_at ? $company->subscription_expires_at->format('d/m/Y') : 'Sin fecha registrada' }}
            </div>
        </div>
    </div>

    <!-- Invoice History -->
    @if($company->invoices->count() > 0)
    <div class="mt-6">
        <h4 class="text-md font-semibold text-gray-700 mb-3"><i class="fas fa-history mr-2"></i>Historial de Facturas</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha Pago</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($company->invoices()->latest()->get() as $invoice)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->payment_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $invoice->period_start->format('d/m/Y') }} - {{ $invoice->period_end->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">${{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('superadmin.invoices.download', $invoice) }}" 
                               class="text-indigo-600 hover:text-indigo-900" 
                               title="Ver/Descargar PDF"
                               target="_blank">
                                <i class="fas fa-file-pdf text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Usuarios</p>
                <p class="text-2xl font-bold text-gray-800">{{ $company->users_count }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-box text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Activos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $company->assets_count }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Fecha de Registro</p>
                <p class="text-lg font-bold text-gray-800">{{ $company->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Company Limits -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Límites y Configuración</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <p class="text-sm text-gray-500">Límite de Usuarios</p>
            <p class="font-semibold">{{ $company->user_limit ?? 'Ilimitado' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Límite de Activos</p>
            <p class="font-semibold">{{ $company->asset_limit ?? 'Ilimitado' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Estado</p>
            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($company->status) }}
            </span>
        </div>
    </div>
</div>
@endsection
