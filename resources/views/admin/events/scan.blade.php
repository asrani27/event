@extends('layouts.app')

@section('title', 'Scan Kehadiran - ' . $event->title)

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.events.show', $event) }}"
                    class="text-purple-600 hover:text-purple-800 transition duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Event</span>
                </a>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Scan Kehadiran</h2>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Scanner Section -->
        <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">


            <!-- Video Container -->
            <div class="relative bg-black rounded-lg overflow-hidden mb-4" style="aspect-ratio: 1/1;">
                <div id="qr-reader" class="w-full h-full"></div>

                <!-- Scanner Overlay -->
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute inset-0 border-2 border-white opacity-20"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <div class="w-48 h-48 border-2 border-purple-500 rounded-lg">
                            <div
                                class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-purple-500 rounded-tl-lg">
                            </div>
                            <div
                                class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-purple-500 rounded-tr-lg">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-purple-500 rounded-bl-lg">
                            </div>
                            <div
                                class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-purple-500 rounded-br-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Scanner Status -->
            <div id="scanner-status" class="mb-4">
                <div id="status-message" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm text-center">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Klik "Mulai Scan" untuk mengaktifkan kamera
                </div>
            </div>
            <!-- Controls -->
            <div class="flex space-x-3">
                <button id="start-scan" onclick="startScanning()"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Mulai Scan</span>
                </button>
                <button id="stop-scan" onclick="stopScanning()" disabled
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    <span>Stop Scan</span>
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrcodeScanner = null;
    let eventId = {{ $event->id }};

    function updateStatus(message, type = 'info') {
        const statusDiv = document.getElementById('status-message');
        const bgColors = {
            'info': 'bg-blue-500',
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'warning': 'bg-yellow-500'
        };
        
        statusDiv.className = `${bgColors[type]} text-white px-4 py-2 rounded-lg text-sm text-center`;
        statusDiv.innerHTML = message;
    }

    function startScanning() {
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        
        startBtn.disabled = true;
        stopBtn.disabled = false;
        
        updateStatus('Memulai scanner...', 'info');

        html5QrcodeScanner = new Html5Qrcode("qr-reader");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                handleScanSuccess(decodedText);
            },
            (errorMessage) => {
                // Ignore scan errors, they happen frequently
            }
        ).then(() => {
            updateStatus('Scanner aktif. Arahkan ke QR code pegawai.', 'success');
        }).catch((err) => {
            console.error(`Unable to start scanning: ${err}`);
            updateStatus('Gagal memulai kamera. Pastikan izin kamera diizinkan.', 'error');
            startBtn.disabled = false;
            stopBtn.disabled = true;
        });
    }

    function stopScanning() {
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                updateStatus('Scanner dihentikan.', 'info');
                startBtn.disabled = false;
                stopBtn.disabled = true;
            }).catch((err) => {
                console.error(`Unable to stop scanning: ${err}`);
            });
        }
    }

    function handleScanSuccess(decodedText) {
        console.log('QR Code scanned:', decodedText);
        
        // Temporarily stop scanning to prevent multiple scans
        if (html5QrcodeScanner) {
            html5QrcodeScanner.pause();
        }
        
        updateStatus('Memproses scan...', 'info');
        
        // Send to server
        const formData = new FormData();
        formData.append('qr_data', decodedText);
        
        fetch(`/admin/events/${eventId}/scan_kehadiran`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.already_scanned) {
                    // Handle already scanned case
                    updateStatus(data.message, 'warning');
                    
                    // Play different sound for already scanned
                    playWarningSound();
                    
                    // Resume scanning after 2 seconds
                    setTimeout(() => {
                        if (html5QrcodeScanner) {
                            html5QrcodeScanner.resume();
                            updateStatus('Scanner aktif. Arahkan ke QR code pegawai.', 'success');
                        }
                    }, 2000);
                } else {
                    // Handle successful first-time scan
                    updateStatus(data.message, 'success');
                    
                    // Play success sound if available
                    playSuccessSound();
                    
                    // Resume scanning after 2 seconds
                    setTimeout(() => {
                        if (html5QrcodeScanner) {
                            html5QrcodeScanner.resume();
                            updateStatus('Scanner aktif. Arahkan ke QR code pegawai.', 'success');
                        }
                    }, 2000);
                }
            } else {
                updateStatus(data.message, 'error');
                
                // Resume scanning after 3 seconds for errors
                setTimeout(() => {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.resume();
                        updateStatus('Scanner aktif. Arahkan ke QR code pegawai.', 'success');
                    }
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            updateStatus('Terjadi kesalahan. Silakan coba lagi.', 'error');
            
            // Resume scanning after 3 seconds for errors
            setTimeout(() => {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                    updateStatus('Scanner aktif. Arahkan ke QR code pegawai.', 'success');
                }
            }, 3000);
        });
    }


    function playSuccessSound() {
        // Create a simple beep sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    }

    function playWarningSound() {
        // Create a different beep sound for warning using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 400; // Lower frequency for warning
        oscillator.type = 'square'; // Different wave type for warning
        
        gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop();
        }
    });
</script>
@endpush
@endsection