@extends('layouts.superadmin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Generar Factura para {{ $company->name }}</h2>
        <a href="{{ route('superadmin.companies.show', $company->id) }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-times"></i>
        </a>
    </div>

    <form action="{{ route('superadmin.companies.invoices.store', $company->id) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monto Pagado ($)</label>
                <input type="number" name="amount" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-medium/500 focus:ring-blue-medium" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Pago</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-medium/500 focus:ring-blue-medium" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Periodo</label>
            <select name="period_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-medium/500 focus:ring-blue-medium" required>
                <option value="monthly">Mensualidad</option>
                <option value="yearly">Anualidad</option>
                <option value="other">Otro</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Inicio del Periodo</label>
                <input type="date" name="period_start" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-medium/500 focus:ring-blue-medium" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fin del Periodo (Expiración)</label>
                <input type="date" name="period_end" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-medium/500 focus:ring-blue-medium" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas (Opcional)</label>
            <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-medium/500 focus:ring-blue-medium"></textarea>
        </div>

        <div class="bg-blue-50 p-4 rounded-md mb-6">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-2"></i>
                Al guardar:
                <ul class="list-disc list-inside ml-4 mt-2">
                    <li>Se generará un PDF profesional.</li>
                    <li>Se enviará automáticamente a <strong>{{ $company->email }}</strong>.</li>
                    <li>La suscripción de la empresa se actualizará hasta la fecha de fin de periodo.</li>
                </ul>
            </p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('superadmin.companies.show', $company->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-medium text-white rounded-md hover:bg-blue-dark font-bold">
                <i class="fas fa-paper-plane mr-2"></i>Generar y Enviar Factura
            </button>
        </div>
    </form>
</div>
@endsection
