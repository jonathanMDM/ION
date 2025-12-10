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
                    <div id="qrcode"></div>
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

<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ route('assets.show', $asset->id) }}",
        width: 300,
        height: 300,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
</script>

<style>
    @media print {
        .sidebar, .navbar, button, a {
            display: none !important;
        }
        body {
            background: white !important;
        }
    }
    
    #qrcode {
        display: inline-block;
    }
    
    #qrcode img {
        margin: 0 auto;
    }
</style>
@endsection
