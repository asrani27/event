@extends('layouts.app')

@section('title', 'Riwayat Scan - ' . $event->title)

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
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Scan</h2>
        <p class="text-gray-600">Riwayat scan kehadiran untuk event: {{ $event->title }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Event Information -->
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
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        @switch($event->status)
                        @case('upcoming')
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Akan Datang
                        </span>
                        @break
                        @case('ongoing')
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Sedang Berlangsung
                        </span>
                        @break
                        @case('completed')
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Selesai
                        </span>
                        @break
                        @case('cancelled')
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Dibatalkan
                        </span>
                        @break
                        @endswitch
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Statistik Kehadiran</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-purple-100 text-sm">Total Peserta</p>
                        <p class="text-2xl font-bold">{{ $event->current_participants }}</p>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm">Sudah Hadir</p>
                        <p id="hadir-count" class="text-2xl font-bold">{{
                            $event->participants->where('status_kehadiran', 'hadir')->count() }}</p>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm">Belum Hadir</p>
                        <p class="text-2xl font-bold">{{
                            $event->participants->where('status_kehadiran', 'terdaftar')->count() }}</p>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm">Tidak Hadir</p>
                        <p class="text-2xl font-bold">{{
                            $event->participants->where('status_kehadiran', 'tidak_hadir')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Scan History -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
                    <h3 class="text-lg font-semibold text-gray-800">Scan Terbaru</h3>
                    <div
                        class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                        <!-- Filter Status -->
                        <select id="status-filter" onchange="filterByStatus()"
                            class="w-full sm:w-auto text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="terdaftar">Terdaftar</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                        </select>

                        <!-- Search -->
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="search-scan" placeholder="Cari nama atau NIP..."
                                class="w-full text-sm border border-gray-300 rounded px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-purple-500">

                        </div>
                    </div>
                </div>

                <div id="scan-history" class="space-y-3 max-h-96 overflow-y-auto">
                    @if ($event->participants->count() > 0)
                    @foreach ($event->participants->sortByDesc('updated_at') as $participant)
                    <div class="scan-item border rounded-lg p-4 {{ $participant->status_kehadiran == 'hadir' ? 'bg-green-50 border-green-200' : ($participant->status_kehadiran == 'tidak_hadir' ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200') }}"
                        data-status="{{ $participant->status_kehadiran }}"
                        data-name="{{ strtolower($participant->nama) }}" data-nip="{{ strtolower($participant->nip) }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0">
                                    @if ($participant->status_kehadiran == 'hadir')
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @elseif ($participant->status_kehadiran == 'tidak_hadir')
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <!-- Participant Info -->
                                <div>
                                    <p
                                        class="font-semibold {{ $participant->status_kehadiran == 'hadir' ? 'text-green-800' : ($participant->status_kehadiran == 'tidak_hadir' ? 'text-red-800' : 'text-blue-800') }}">
                                        {{ $participant->nama }}
                                    </p>
                                    <p
                                        class="text-sm {{ $participant->status_kehadiran == 'hadir' ? 'text-green-600' : ($participant->status_kehadiran == 'tidak_hadir' ? 'text-red-600' : 'text-blue-600') }}">
                                        {{ $participant->nip }}
                                    </p>
                                    <p
                                        class="text-xs {{ $participant->status_kehadiran == 'hadir' ? 'text-green-500' : ($participant->status_kehadiran == 'tidak_hadir' ? 'text-red-500' : 'text-blue-500') }}">
                                        {{ $participant->jabatan }} - {{ $participant->skpd }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status and Time -->
                            <div class="text-right">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->status_kehadiran == 'hadir' ? 'bg-green-100 text-green-800' : ($participant->status_kehadiran == 'tidak_hadir' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $participant->status_kehadiran == 'hadir' ? 'Hadir' :
                                    ($participant->status_kehadiran == 'tidak_hadir' ? 'Tidak Hadir' : 'Terdaftar') }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if ($participant->check_in)
                                    {{ $participant->check_in->format('H:i') }} WIB
                                    @else
                                    -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                        <p class="text-gray-500">Belum ada peserta yang terdaftar</p>
                        <p class="text-sm text-gray-400 mt-1">Tambahkan peserta terlebih dahulu untuk melihat riwayat
                            scan</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Present Percentage -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-green-600">Tingkat Kehadiran</p>
                            <p class="text-2xl font-bold text-green-800">
                                @if ($event->current_participants > 0)
                                {{ round(($event->participants->where('status_kehadiran', 'hadir')->count() /
                                $event->current_participants) * 100) }}%
                                @else
                                0%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Scans Today -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600">Scan Hari Ini</p>
                            <p class="text-2xl font-bold text-blue-800">
                                {{ $event->participants->where('status_kehadiran', 'hadir')->where('check_in', '>=',
                                today()->startOfDay())->where('check_in', '<=', today()->endOfDay())->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Last Scan -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600">Scan Terakhir</p>
                            <p class="text-lg font-bold text-purple-800">
                                @if ($lastScan = $event->participants->where('check_in', '!=',
                                null)->sortByDesc('check_in')->first())
                                {{ $lastScan->check_in->format('H:i') }}
                                @else
                                -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br /> <br /> <br /> <br />
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Filter functionality
    function filterByStatus() {
        const statusFilter = document.getElementById('status-filter').value;
        const searchTerm = document.getElementById('search-scan').value.toLowerCase();
        applyFilters(statusFilter, searchTerm);
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-scan');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const statusFilter = document.getElementById('status-filter').value;
                const searchTerm = this.value.toLowerCase();
                applyFilters(statusFilter, searchTerm);
            });
        }
    });

    function applyFilters(statusFilter, searchTerm) {
        const scanItems = document.querySelectorAll('.scan-item');
        let visibleCount = 0;

        scanItems.forEach(item => {
            const status = item.getAttribute('data-status');
            const name = item.getAttribute('data-name');
            const nip = item.getAttribute('data-nip');

            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesSearch = !searchTerm || name.includes(searchTerm) || nip.includes(searchTerm);

            if (matchesStatus && matchesSearch) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show no results message if needed
        const scanHistory = document.getElementById('scan-history');
        let noResultsMessage = document.getElementById('no-results-message');
        
        if (visibleCount === 0 && scanItems.length > 0) {
            if (!noResultsMessage) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.id = 'no-results-message';
                noResultsDiv.className = 'text-center py-8 bg-yellow-50 rounded-lg border border-yellow-200';
                noResultsDiv.innerHTML = `
                    <svg class="w-12 h-12 mx-auto mb-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-yellow-800 font-medium">Tidak ada hasil yang ditemukan</p>
                    <p class="text-sm text-yellow-600 mt-1">Coba gunakan filter atau kata kunci pencarian yang berbeda</p>
                `;
                scanHistory.appendChild(noResultsDiv);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }

    // Auto-refresh functionality (optional - refresh every 30 seconds)
    let refreshInterval;
    
    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            // You can add AJAX call here to refresh the scan history
            console.log('Auto-refreshing scan history...');
        }, 30000); // 30 seconds
    }

    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }

    // Start auto-refresh when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
    });

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
</script>

@endpush
@endsection