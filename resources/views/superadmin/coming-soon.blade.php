@extends('layouts.superadmin')

@section('page-title', 'Funcionalidad en Desarrollo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <i class="fas fa-tools text-6xl text-indigo-500"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">
            {{ $title ?? 'Funcionalidad en Desarrollo' }}
        </h1>
        <p class="text-gray-600 mb-6">
            {{ $message ?? 'Esta sección está actualmente en desarrollo y estará disponible próximamente.' }}
        </p>
        <a href="{{ route('superadmin.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-medium text-white rounded-lg hover:bg-blue-dark transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Dashboard
        </a>
    </div>
</div>
@endsection
