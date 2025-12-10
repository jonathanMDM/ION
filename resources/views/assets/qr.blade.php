@extends('layouts.app')

@section('page-title', 'Código QR - ' . $asset->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ $asset->name }}</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">ID: {{ $asset->custom_id }}</p>
            
            <!-- QR Code -->
            <div class="flex justify-center mb-6">
                <div class="bg-white p-4 rounded-lg shadow-inner">
                    @php
                        $qrUrl = route('assets.show', $asset->id);
                        // Using QuickChart.io API for QR code generation
                        $qrImageUrl = 'https://quickchart.io/qr?text=' . urlencode($qrUrl) . '&size=300';
                    @endphp
                    <img src="{{ $qrImageUrl }}" alt="QR Code para {{ $asset->name }}" class="w-[300px] h-[300px]" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\'%3E%3Crect width=\'300\' height=\'300\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'Arial\' font-size=\'16\' fill=\'%23666\'%3EQR Code no disponible%3C/text%3E%3C/svg%3E'">
                    <p class="text-xs text-gray-500 mt-2 text-center">Escanea para ver detalles</p>
                </div>
            </div>
            
            <!-- Asset Info -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Ubicación</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $asset->location->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Estado</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ ucfirst($asset->status) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Categoría</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $asset->subcategory->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Subcategoría</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $asset->subcategory->name }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-center gap-4">
                <a href="{{ route('assets.show', $asset) }}" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
                    <i class="fas fa-eye mr-2"></i>Ver Detalles
                </a>
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow">
                    <i class="fas fa-print mr-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .navbar, button, a {
            display: none !important;
        }
        body {
            background: white !important;
        }
    }
</style>
@endsection
