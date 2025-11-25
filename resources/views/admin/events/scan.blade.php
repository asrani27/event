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
        <p class="text-gray-600">Scan QR code pegawai untuk mencatat kehadiran di event: {{ $event->title }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Scanner Section -->
        <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
            <div class="mb-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">QR Code Scanner</h3>
                <p class="text-sm text-gray-600">Arahkan kamera ke QR code pegawai untuk mencatat kehadiran</p>
            </div>

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

                <!-- Status Overlay -->
                <div id="scanner-status" class="absolute top-4 left-4 right-4">
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

        <!-- Results Section -->
        <div class="space-y-6">
            <!-- Event Info -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Event</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event:</span>
                        <span class="font-medium">{{ $event->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-medium">{{ $event->date->format('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Waktu:</span>
                        <span class="font-medium">{{ $event->time->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lokasi:</span>
                        <span class="font-medium">{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Scans -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Scan Terbaru</h3>
                <div id="recent-scans" class="space-y-3 max-h-64 overflow-y-auto">
                    <div class="text-center text-gray-500 py-8">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                        <p class="text-sm">Belum ada scan yang dilakukan</p>
                        <p class="text-xs text-gray-400 mt-1">Scan QR code pegawai untuk mencatat kehadiran</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Statistik Kehadiran</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-purple-100 text-sm">Total Peserta</p>
                        <p class="text-2xl font-bold">{{ $event->current_participants }}</p>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm">Sudah Hadir</p>
                        <p id="hadir-count" class="text-2xl font-bold">{{
                            $event->participants->where('status_kehadiran', 'hadir')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrcodeScanner = null;
    let eventId = {{ $event->id }};
    let scanCount = 0;
    let hadirCount = {{ $event->participants->where('status_kehadiran', 'hadir')->count() }};

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
                updateStatus('Kehadiran berhasil dicatat!', 'success');
                addRecentScan(data.participant);
                
                // Update hadir count
                hadirCount++;
                document.getElementById('hadir-count').textContent = hadirCount;
                
                // Play success sound if available
                playSuccessSound();
                
                // Resume scanning after 2 seconds
                setTimeout(() => {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.resume();
                        updateStatus('Scanner aktif. Arahkan ke QR code pegawai.', 'success');
                    }
                }, 2000);
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

    function addRecentScan(participant) {
        const recentScansDiv = document.getElementById('recent-scans');
        
        // Remove empty state if it exists
        const emptyState = recentScansDiv.querySelector('.text-center');
        if (emptyState) {
            emptyState.remove();
        }
        
        // Create new scan item
        const scanItem = document.createElement('div');
        scanItem.className = 'p-3 bg-green-50 border border-green-200 rounded-lg';
        scanItem.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-green-800">${participant.nama}</p>
                    <p class="text-sm text-green-600">${participant.nip}</p>
                    <p class="text-xs text-green-500">${participant.jabatan} - ${participant.skpd}</p>
                </div>
                <div class="text-right">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs text-green-600">Hadir</p>
                </div>
            </div>
        `;
        
        // Add to top of list
        recentScansDiv.insertBefore(scanItem, recentScansDiv.firstChild);
        
        // Keep only last 10 scans
        const scans = recentScansDiv.children;
        if (scans.length > 10) {
            recentScansDiv.removeChild(scans[scans.length - 1]);
        }
        
        scanCount++;
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

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop();
        }
    });
</script>
@endpush
@endsection
