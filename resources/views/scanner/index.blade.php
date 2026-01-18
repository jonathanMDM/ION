@extends('layouts.app')

@section('page-title', 'Escáner de Códigos')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Escáner de Códigos de Barras / QR</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Apunta la cámara hacia un código de barras o QR para buscar el activo.</p>
        
        <!-- Scanner Container -->
        <div id="reader" class="w-full rounded-lg overflow-hidden mb-4"></div>
        
        <!-- Controls -->
        <div class="flex justify-center gap-4 mb-4">
            <button id="startBtn" onclick="startScanner()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
                <i class="fas fa-camera mr-2"></i>Iniciar Escáner
            </button>
            <button id="stopBtn" onclick="stopScanner()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow hidden">
                <i class="fas fa-stop mr-2"></i>Detener
            </button>
        </div>
        
        <!-- Result -->
        <div id="result" class="hidden bg-green-50 dark:bg-green-900 border border-[#5483B3]/200 dark:border-[#5483B3]/700 rounded-lg p-4">
            <p class="text-green-800 dark:text-green-100 font-semibold">Código detectado:</p>
            <p id="scannedCode" class="text-green-900 dark:text-green-50 font-mono text-lg"></p>
        </div>
    </div>
</div>

<!-- Include html5-qrcode library (local) -->
<script src="{{ asset('js/html5-qrcode.min.js') }}" type="text/javascript"></script>

<script>
    let html5QrcodeScanner = null;
    let html5Qrcode = null;

    async function startScanner() {
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        
        startBtn.classList.add('hidden');
        stopBtn.classList.remove('hidden');

        try {
            // Calculate responsive qrbox size
            const isMobile = window.innerWidth < 768;
            const qrboxSize = isMobile ? Math.min(250, window.innerWidth - 60) : 250;

            // Try to get camera devices first
            const devices = await Html5Qrcode.getCameras();
            
            if (devices && devices.length > 0) {
                console.log("Available cameras:", devices);
                
                // Find rear camera with multiple strategies
                let cameraId = devices[devices.length - 1].id; // Default to last camera (usually rear on mobile)
                
                // Strategy 1: Look for keywords in label
                for (let device of devices) {
                    const label = device.label.toLowerCase();
                    console.log("Camera label:", label);
                    
                    if (label.includes('back') || 
                        label.includes('rear') || 
                        label.includes('trasera') ||
                        label.includes('environment') ||
                        label.includes('posterior')) {
                        cameraId = device.id;
                        console.log("Found rear camera:", device.label);
                        break;
                    }
                }
                
                // Strategy 2: If multiple cameras, prefer the one that's NOT front/user
                if (devices.length > 1) {
                    for (let device of devices) {
                        const label = device.label.toLowerCase();
                        if (!label.includes('front') && 
                            !label.includes('user') && 
                            !label.includes('frontal') &&
                            !label.includes('selfie')) {
                            cameraId = device.id;
                            console.log("Selected non-front camera:", device.label);
                            break;
                        }
                    }
                }

                console.log("Using camera ID:", cameraId);

                // Use Html5Qrcode instead of Html5QrcodeScanner for better control
                html5Qrcode = new Html5Qrcode("reader");
                
                await html5Qrcode.start(
                    cameraId,
                    {
                        fps: 20, // Increased from 10 to 20 for faster scanning
                        qrbox: qrboxSize,
                        aspectRatio: 1.0
                    },
                    onScanSuccess,
                    onScanFailure
                );
                
                console.log("Scanner started successfully");
            } else {
                throw new Error("No se encontraron cámaras disponibles");
            }
        } catch (error) {
            console.error("Error starting scanner:", error);
            
            // Show user-friendly error
            let errorMessage = "Error al iniciar el escáner: ";
            if (error.name === 'NotAllowedError') {
                errorMessage += "Permiso de cámara denegado. Por favor, permite el acceso en la configuración de tu navegador.";
            } else if (error.name === 'NotFoundError') {
                errorMessage += "No se encontró ninguna cámara.";
            } else if (error.name === 'NotReadableError') {
                errorMessage += "La cámara está siendo usada por otra aplicación.";
            } else {
                errorMessage += error.message || "Error desconocido";
            }
            
            alert(errorMessage);
            
            // Reset buttons
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
        }
    }

    async function stopScanner() {
        try {
            if (html5Qrcode) {
                await html5Qrcode.stop();
                html5Qrcode.clear();
                html5Qrcode = null;
            }
        } catch (error) {
            console.error("Error stopping scanner:", error);
        }
        
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const result = document.getElementById('result');
        
        startBtn.classList.remove('hidden');
        stopBtn.classList.add('hidden');
        result.classList.add('hidden');
    }

    function onScanSuccess(decodedText, decodedResult) {
        console.log("Scan successful:", decodedText);
        
        // Show result
        document.getElementById('scannedCode').textContent = decodedText;
        document.getElementById('result').classList.remove('hidden');
        
        // Stop scanner
        stopScanner();
        
        // Check if the scanned text is a URL pointing to an asset
        let redirectUrl = null;
        
        // Pattern 1: Full URL like http://127.0.0.1:8000/assets/7
        const assetUrlPattern = /\/assets\/(\d+)/;
        const assetMatch = decodedText.match(assetUrlPattern);
        
        if (assetMatch) {
            // Extract asset ID and redirect to asset detail page
            const assetId = assetMatch[1];
            redirectUrl = "{{ route('assets.index') }}".replace('/assets', '/assets/' + assetId);
        } else if (/^\d+$/.test(decodedText)) {
            // Pattern 2: Just a number (asset ID)
            redirectUrl = "{{ route('assets.index') }}".replace('/assets', '/assets/' + decodedText);
        } else {
            // Pattern 3: Any other text - search for it
            redirectUrl = "{{ route('search') }}?q=" + encodeURIComponent(decodedText);
        }
        
        // Redirect after showing the result briefly
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 500); // Reduced from 1500ms to 500ms for faster response
    }

    function onScanFailure(error) {
        // Handle scan failure silently
        // Only log if it's not a common scanning error
        if (error && !error.includes("NotFoundException") && !error.includes("No MultiFormat Readers")) {
            console.warn(`Code scan error = ${error}`);
        }
    }

    // Check camera availability on page load
    document.addEventListener('DOMContentLoaded', async function() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            const startBtn = document.getElementById('startBtn');
            startBtn.disabled = true;
            startBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Cámara no disponible';
            startBtn.classList.add('opacity-50', 'cursor-not-allowed');
            alert('Tu navegador no soporta acceso a la cámara. Por favor, abre esta página en Safari o Chrome.');
        } else {
            // Check if we can get camera list
            try {
                const devices = await Html5Qrcode.getCameras();
                console.log("Cameras found:", devices.length);
            } catch (error) {
                console.error("Error checking cameras:", error);
            }
        }
    });
</script>
@endsection

