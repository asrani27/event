@extends('layouts.app')

@section('title', 'Event Details')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.events.index') }}"
                    class="text-purple-600 hover:text-purple-800 transition duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Event</span>
                </a>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.events.scan_kehadiran', $event) }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Scan Kehadiran</span>
                </a>
                <a href="{{ route('admin.events.edit', $event) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Edit</span>
                </a>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $event->title }}</h2>
        <p class="text-gray-600">Detail informasi event</p>
    </div>

    <!-- Event Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Info Card -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Informasi Event</h3>
                    @switch($event->status)
                    @case('upcoming')
                    <span
                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        Akan Datang
                    </span>
                    @break
                    @case('ongoing')
                    <span
                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Sedang Berlangsung
                    </span>
                    @break
                    @case('completed')
                    <span
                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        Selesai
                    </span>
                    @break
                    @case('cancelled')
                    <span
                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Dibatalkan
                    </span>
                    @break
                    @endswitch
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h4>
                    <p class="text-gray-600 leading-relaxed">{{ $event->description }}</p>
                </div>

                <!-- Event Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Date -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium text-gray-900">{{ $event->date->format('d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu</p>
                            <p class="font-medium text-gray-900">{{ $event->time->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Lokasi</p>
                            <p class="font-medium text-gray-900">{{ $event->location }}</p>
                        </div>
                    </div>

                    <!-- Max Participants -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kapasitas</p>
                            <p class="font-medium text-gray-900">{{ $event->max_participants }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants Progress -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Progress Peserta</h3>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Peserta Terdaftar</span>
                        <span id="participant-count" class="text-sm font-medium text-gray-900">{{
                            $event->current_participants }} / {{
                            $event->max_participants }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div id="progress-bar"
                            class="bg-gradient-to-r from-purple-600 to-pink-600 h-3 rounded-full transition-all duration-300"
                            style="width: {{ ($event->current_participants / $event->max_participants) * 100 }}%"></div>
                    </div>
                    <p id="progress-text" class="text-sm text-gray-600 mt-2">
                        {{ round(($event->current_participants / $event->max_participants) * 100) }}% terisi
                    </p>
                </div>

                <div id="slot-status">
                    @if ($event->current_participants < $event->max_participants)
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">
                                <strong id="available-slots">{{ $event->max_participants - $event->current_participants
                                    }}</strong> slot tersedia
                            </p>
                        </div>
                        @else
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">Event sudah penuh!</p>
                        </div>
                        @endif
                </div>
            </div>

            <!-- Quick Add Participant -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Tambah Peserta</h3>
                            <p class="text-sm text-gray-500">Pilih metode penambahan peserta</p>
                        </div>
                    </div>
                    <span id="quick-add-status" class="text-sm font-medium">
                        @if ($event->current_participants < $event->max_participants)
                            <span class="text-green-600">{{ $event->max_participants - $event->current_participants }}
                                slot tersedia</span>
                            @else
                            <span class="text-red-600">Event penuh</span>
                            @endif
                    </span>
                </div>

                <!-- Modern Tab Navigation -->
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-100 to-pink-100 rounded-xl opacity-50">
                    </div>
                    <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-1">
                        <nav class="flex space-x-1">
                            <button onclick="switchTab('single')" id="single-tab"
                                class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-md transform scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Tambah Satuan</span>
                            </button>
                            <button onclick="switchTab('import')" id="import-tab"
                                class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <span>Import Excel</span>
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Single Add Form -->
                <div id="single-add-form">
                    @if ($event->current_participants < $event->max_participants)
                        <div class="space-y-4">
                            <div
                                class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-800">Tambah Peserta Individual</h4>
                                </div>
                                <p class="text-sm text-gray-600 mb-4">Cari dan pilih pegawai dari database untuk
                                    ditambahkan ke event ini.</p>
                            </div>

                            <div class="flex space-x-3">
                                <div class="flex-1">
                                    <select id="quick_pegawai_select" name="pegawai_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Pilih Pegawai...</option>
                                    </select>
                                </div>
                                <button onclick="quickAddParticipant()"
                                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Tambah Peserta</span>
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="p-6 bg-red-50 border border-red-200 rounded-lg text-center">
                            <div
                                class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-red-800 font-medium">Event sudah mencapai kapasitas maksimum</p>
                        </div>
                        @endif
                </div>

                <!-- Import Form -->
                <div id="import-form" class="hidden">
                    @if ($event->current_participants < $event->max_participants)
                        <div class="space-y-6">
                            <div
                                class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border border-green-100">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-800">Import Excel Massal</h4>
                                </div>
                                <div class="text-sm text-gray-600 space-y-2">
                                    <div class="flex items-start space-x-2">
                                        <span class="text-green-600 mt-0.5">•</span>
                                        <span>Format file: .xlsx atau .xls</span>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="text-green-600 mt-0.5">•</span>
                                        <span>NIP harus berada di kolom A (dimulai dari baris 1)</span>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="text-green-600 mt-0.5">•</span>
                                        <span>Pastikan NIP yang ada di Excel sudah terdaftar sebagai pegawai</span>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="text-green-600 mt-0.5">•</span>
                                        <span>Sistem akan otomatis menambahkan pegawai yang valid ke event</span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                <div
                                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="w-full">
                                        <input type="file" id="excel-file" accept=".xlsx,.xls"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                                        <p id="file-name" class="text-xs text-gray-500 mt-2">Belum ada file dipilih</p>
                                    </div>
                                    <button onclick="importExcel()"
                                        class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg hover:from-green-700 hover:to-blue-700 transition duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <span>Import Excel</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Import Progress -->
                            <div id="import-progress" class="hidden">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Progress Import:</h4>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                        <div id="import-progress-bar"
                                            class="bg-gradient-to-r from-blue-600 to-green-600 h-3 rounded-full transition-all duration-300"
                                            style="width: 0%"></div>
                                    </div>
                                    <p id="import-status" class="text-sm text-gray-600">Memproses...</p>
                                </div>
                            </div>

                            <!-- Import Results -->
                            <div id="import-results" class="hidden">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Hasil Import:</h4>
                                    <div id="import-summary" class="text-sm space-y-2"></div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-6 bg-red-50 border border-red-200 rounded-lg text-center">
                            <div
                                class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-red-800 font-medium">Event sudah mencapai kapasitas maksimum</p>
                        </div>
                        @endif
                </div>
            </div>

            <!-- Participants List -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Daftar Peserta</h3>
                    @if ($event->participants->count() > 0)
                    <a href="{{ route('admin.events.export_excel', $event) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Export Kehadiran</span>
                    </a>
                    @else
                    <button disabled
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed transition duration-200 flex items-center space-x-2"
                        title="Tambahkan peserta terlebih dahulu untuk mengaktifkan export">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Export Kehadiran</span>
                    </button>
                    @endif
                </div>

                <div id="participants-list">
                    @if ($event->participants->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIP</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jabatan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SKPD</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Kehadiran</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="participants-tbody">
                                @foreach ($event->participants as $key => $participant)
                                <tr class="hover:bg-gray-50" data-participant-id="{{ $participant->id }}">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $participant->nip }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $participant->nama }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $participant->jabatan }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $participant->skpd }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @switch($participant->status_kehadiran)
                                        @case('terdaftar')
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Terdaftar
                                        </span>
                                        @break
                                        @case('hadir')
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Hadir
                                        </span>
                                        @break
                                        @case('tidak_hadir')
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Hadir
                                        </span>
                                        @break
                                        @default
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $participant->status_kehadiran }}
                                        </span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex space-x-2">
                                            <select
                                                onchange="updateAttendanceStatus({{ $participant->id }}, this.value)"
                                                class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                <option value="terdaftar" {{ $participant->status_kehadiran ==
                                                    'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                                <option value="hadir" {{ $participant->status_kehadiran == 'hadir' ?
                                                    'selected' : '' }}>Hadir</option>
                                                <option value="tidak_hadir" {{ $participant->status_kehadiran ==
                                                    'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                            </select>
                                            <button onclick="deleteParticipant({{ $participant->id }})"
                                                class="text-red-600 hover:text-red-900 transition duration-150">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <p class="text-gray-500">Belum ada peserta yang terdaftar</p>
                        <p class="text-sm text-gray-400 mt-1">Gunakan "Tambah Peserta Cepat" di atas untuk menambahkan
                            peserta</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.events.edit', $event) }}"
                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Event
                    </a>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- Event Statistics -->
            <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Statistik Event</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-purple-100 text-sm">Total Kapasitas</p>
                        <p class="text-2xl font-bold">{{ $event->max_participants }}</p>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm">Terisi</p>
                        <p id="stats-current" class="text-2xl font-bold">{{ $event->current_participants }}</p>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm">Sisa Slot</p>
                        <p id="stats-remaining" class="text-2xl font-bold">{{ $event->max_participants -
                            $event->current_participants }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let currentEventId = {{ $event->id }};
    let maxParticipants = {{ $event->max_participants }};
    let currentParticipants = {{ $event->current_participants }};

    function updateParticipantCount(newCount) {
        currentParticipants = newCount;
        const percentage = (currentParticipants / maxParticipants) * 100;
        const remaining = maxParticipants - currentParticipants;

        // Update progress bar
        document.getElementById('progress-bar').style.width = percentage + '%';
        document.getElementById('progress-text').textContent = Math.round(percentage) + '% terisi';
        document.getElementById('participant-count').textContent = currentParticipants + ' / ' + maxParticipants;

        // Update statistics
        const statsCurrent = document.getElementById('stats-current');
        const statsRemaining = document.getElementById('stats-remaining');
        if (statsCurrent) statsCurrent.textContent = currentParticipants;
        if (statsRemaining) statsRemaining.textContent = remaining;

        // Update slot status
        const slotStatus = document.getElementById('slot-status');
        if (slotStatus) {
            if (currentParticipants < maxParticipants) {
                slotStatus.innerHTML = `
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800">
                            <strong id="available-slots">${remaining}</strong> slot tersedia
                        </p>
                    </div>
                `;
            } else {
                slotStatus.innerHTML = `
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">Event sudah penuh!</p>
                    </div>
                `;
            }
        }

        // Update quick add status and form
        const quickAddStatus = document.getElementById('quick-add-status');
        const quickAddForm = document.getElementById('quick-add-form');
        
        if (quickAddStatus) {
            if (currentParticipants < maxParticipants) {
                quickAddStatus.innerHTML = `<span class="text-green-600">${remaining} slot tersedia</span>`;
            } else {
                quickAddStatus.innerHTML = `<span class="text-red-600">Event penuh</span>`;
            }
        }

        if (quickAddForm) {
            if (currentParticipants < maxParticipants) {
                quickAddForm.innerHTML = `
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                            <div class="flex items-center space-x-2 mb-3">
                                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-800">Tambah Peserta Individual</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Cari dan pilih pegawai dari database untuk ditambahkan ke event ini.</p>
                        </div>
                        
                        <div class="flex space-x-3">
                            <div class="flex-1">
                                <select id="quick_pegawai_select" name="pegawai_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Pilih Pegawai...</option>
                                </select>
                            </div>
                            <button onclick="quickAddParticipant()"
                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Peserta</span>
                            </button>
                        </div>
                    </div>
                `;
                // Re-initialize select2
                initializeSelect2();
            } else {
                quickAddForm.innerHTML = `
                    <div class="p-6 bg-red-50 border border-red-200 rounded-lg text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-red-800 font-medium">Event sudah mencapai kapasitas maksimum</p>
                    </div>
                `;
            }
        }

        // Update available slots count
        const availableSlotsElement = document.getElementById('available-slots');
        if (availableSlotsElement) {
            availableSlotsElement.textContent = remaining;
        }
    }

    function addParticipantToTable(participant, index) {
        // Check if table exists, if not create it
        let tbody = document.getElementById('participants-tbody');
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.setAttribute('data-participant-id', participant.id);
        
        const rowNumber = index || (tbody ? tbody.children.length + 1 : 1);
        
        row.innerHTML = `
            <td class="px-4 py-3 text-sm text-gray-900">${rowNumber}</td>
            <td class="px-4 py-3 text-sm text-gray-900">${participant.nip}</td>
            <td class="px-4 py-3 text-sm font-medium text-gray-900">${participant.nama}</td>
            <td class="px-4 py-3 text-sm text-gray-600">${participant.jabatan}</td>
            <td class="px-4 py-3 text-sm text-gray-600">${participant.skpd}</td>
            <td class="px-4 py-3 text-sm">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    Terdaftar
                </span>
            </td>
            <td class="px-4 py-3 text-sm">
                <div class="flex space-x-2">
                    <select onchange="updateAttendanceStatus(${participant.id}, this.value)"
                        class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="terdaftar" selected>Terdaftar</option>
                        <option value="hadir">Hadir</option>
                        <option value="tidak_hadir">Tidak Hadir</option>
                    </select>
                    <button onclick="deleteParticipant(${participant.id})"
                        class="text-red-600 hover:text-red-900 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </td>
        `;

        if (!tbody) {
            // Table doesn't exist, create it from empty state
            const participantsList = document.getElementById('participants-list');
            participantsList.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKPD</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="participants-tbody"></tbody>
                    </table>
                </div>
            `;
            tbody = document.getElementById('participants-tbody');
        }
        
        tbody.appendChild(row);
        
        // Update row numbers for all rows
        updateRowNumbers();
    }

    function updateRowNumbers() {
        const tbody = document.getElementById('participants-tbody');
        if (tbody) {
            const rows = tbody.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const firstCell = rows[i].cells[0];
                if (firstCell) {
                    firstCell.textContent = i + 1;
                }
            }
        }
    }

    function removeParticipantFromTable(participantId) {
        const row = document.querySelector(`tr[data-participant-id="${participantId}"]`);
        if (row) {
            row.remove();
            
            // Update row numbers
            updateRowNumbers();
            
            // Check if table is empty and show empty state
            const tbody = document.getElementById('participants-tbody');
            if (tbody && tbody.children.length === 0) {
                const participantsList = document.getElementById('participants-list');
                participantsList.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada peserta yang terdaftar</p>
                        <p class="text-sm text-gray-400 mt-1">Gunakan "Tambah Peserta Cepat" di atas untuk menambahkan peserta</p>
                    </div>
                `;
            }
        }
    }

    function showMessage(message, type = 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `fixed top-4 right-4 bg-${type === 'success' ? 'green' : 'red'}-50 border border-${type === 'success' ? 'green' : 'red'}-200 rounded-lg p-4 z-50 shadow-lg`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-${type === 'success' ? 'green' : 'red'}-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="${type === 'success' 
                            ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' 
                            : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}">
                    </path>
                </svg>
                <span class="text-${type === 'success' ? 'green' : 'red'}-800">${message}</span>
            </div>
        `;
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    function quickAddParticipant() {
        const selectedPegawai = $('#quick_pegawai_select').val();
        
        if (!selectedPegawai) {
            showMessage('Silakan pilih pegawai terlebih dahulu.', 'error');
            return;
        }
        
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            showMessage('CSRF token tidak ditemukan. Silakan refresh halaman.', 'error');
            return;
        }
        
        // Get employee data from Select2
        const select2Data = $('#quick_pegawai_select').select2('data');
        let pegawaiData = null;
        
        if (select2Data && select2Data.length > 0) {
            pegawaiData = select2Data[0].data;
        }
        
        const formData = new FormData();
        formData.append('pegawai_id', selectedPegawai);
        
        fetch(`/admin/events/${currentEventId}/participants`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfTokenElement.getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                
                // If we have employee data, add participant to table
                if (pegawaiData) {
                    const newParticipant = {
                        id: data.participant_id || Date.now(),
                        nip: pegawaiData.nip,
                        nama: pegawaiData.nama,
                        jabatan: pegawaiData.jabatan || '-',
                        skpd: pegawaiData.skpd_nama || '-'
                    };
                    addParticipantToTable(newParticipant);
                } else {
                    // If we don't have employee data, fetch it
                    fetch(`api/pegawai/search?q=${selectedPegawai}`)
                        .then(response => response.json())
                        .then(pegawai => {
                            if (pegawai && pegawai.length > 0) {
                                const emp = pegawai.find(p => p.id == selectedPegawai);
                                if (emp) {
                                    const newParticipant = {
                                        id: data.participant_id || Date.now(),
                                        nip: emp.nip,
                                        nama: emp.nama,
                                        jabatan: emp.jabatan || '-',
                                        skpd: emp.skpd_nama || '-'
                                    };
                                    addParticipantToTable(newParticipant);
                                }
                            }
                        });
                }
                
                // Update participant count
                updateParticipantCount(data.current_participants);
                
                // Clear selection
                $('#quick_pegawai_select').val(null).trigger('change');
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    }

    function deleteParticipant(participantId) {
        if (!confirm('Apakah Anda yakin ingin menghapus peserta ini?')) {
            return;
        }
        
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            showMessage('CSRF token tidak ditemukan. Silakan refresh halaman.', 'error');
            return;
        }
        
        fetch(`/admin/events/${currentEventId}/participants/${participantId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfTokenElement.getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Remove participant from table
                removeParticipantFromTable(participantId);
                
                // Update participant count
                updateParticipantCount(data.current_participants);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    }

    function updateAttendanceStatus(participantId, newStatus) {
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            showMessage('CSRF token tidak ditemukan. Silakan refresh halaman.', 'error');
            return;
        }
        
        fetch(`/admin/events/${currentEventId}/participants/${participantId}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfTokenElement.getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status_kehadiran: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Update status display in table
                const row = document.querySelector(`tr[data-participant-id="${participantId}"]`);
                if (row) {
                    const statusCell = row.cells[5]; // Status column (updated to account for No column)
                    const statusClass = {
                        'terdaftar': 'bg-blue-100 text-blue-800',
                        'hadir': 'bg-green-100 text-green-800',
                        'tidak_hadir': 'bg-red-100 text-red-800'
                    };
                    const statusText = {
                        'terdaftar': 'Terdaftar',
                        'hadir': 'Hadir',
                        'tidak_hadir': 'Tidak Hadir'
                    };
                    
                    statusCell.innerHTML = `
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass[newStatus]}">
                            ${statusText[newStatus]}
                        </span>
                    `;
                }
            } else {
                showMessage(data.message, 'error');
                // Revert the select value
                const row = document.querySelector(`tr[data-participant-id="${participantId}"]`);
                if (row) {
                    const select = row.querySelector('select');
                    if (select) {
                        select.value = data.current_status || 'terdaftar';
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    }

    function switchTab(tab) {
        const singleTab = document.getElementById('single-tab');
        const importTab = document.getElementById('import-tab');
        const singleForm = document.getElementById('single-add-form');
        const importForm = document.getElementById('import-form');

        if (tab === 'single') {
            // Active state for single tab
            singleTab.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-md transform scale-105';
            importTab.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-all duration-200';
            singleForm.classList.remove('hidden');
            importForm.classList.add('hidden');
        } else if (tab === 'import') {
            // Active state for import tab
            singleTab.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-all duration-200';
            importTab.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 bg-gradient-to-r from-green-600 to-blue-600 text-white shadow-md transform scale-105';
            singleForm.classList.add('hidden');
            importForm.classList.remove('hidden');
        }
    }

    function importExcel() {
        const fileInput = document.getElementById('excel-file');
        const file = fileInput.files[0];

        if (!file) {
            showMessage('Silakan pilih file Excel terlebih dahulu.', 'error');
            return;
        }

        // Check file extension
        const fileName = file.name.toLowerCase();
        if (!fileName.endsWith('.xlsx') && !fileName.endsWith('.xls')) {
            showMessage('Format file tidak valid. Silakan pilih file .xlsx atau .xls', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', file);

        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            showMessage('CSRF token tidak ditemukan. Silakan refresh halaman.', 'error');
            return;
        }

        // Show progress
        document.getElementById('import-progress').classList.remove('hidden');
        document.getElementById('import-results').classList.add('hidden');
        document.getElementById('import-progress-bar').style.width = '0%';
        document.getElementById('import-status').textContent = 'Mengupload file...';

        fetch(`/admin/events/${currentEventId}/import-excel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfTokenElement.getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update progress
                document.getElementById('import-progress-bar').style.width = '100%';
                document.getElementById('import-status').textContent = 'Import selesai!';

                // Show results
                setTimeout(() => {
                    document.getElementById('import-progress').classList.add('hidden');
                    document.getElementById('import-results').classList.remove('hidden');
                    
                    const summary = document.getElementById('import-summary');
                    summary.innerHTML = `
                        <div class="text-green-700">
                            <p><strong>✓ Berhasil:</strong> ${data.success_count} peserta ditambahkan</p>
                        </div>
                        ${data.duplicate_count > 0 ? `
                            <div class="text-yellow-700">
                                <p><strong>⚠ Duplikat:</strong> ${data.duplicate_count} peserta sudah terdaftar</p>
                            </div>
                        ` : ''}
                        ${data.not_found_count > 0 ? `
                            <div class="text-red-700">
                                <p><strong>✗ Tidak ditemukan:</strong> ${data.not_found_count} NIP tidak valid</p>
                            </div>
                        ` : ''}
                        ${data.details && data.details.length > 0 ? `
                            <div class="mt-3 max-h-40 overflow-y-auto">
                                <p class="font-semibold text-gray-700 mb-2">Detail:</p>
                                ${data.details.map(detail => `
                                    <div class="text-xs ${detail.status === 'success' ? 'text-green-600' : detail.status === 'duplicate' ? 'text-yellow-600' : 'text-red-600'}">
                                        ${detail.status === 'success' ? '✓' : detail.status === 'duplicate' ? '⚠' : '✗'} 
                                        NIP: ${detail.nip} - ${detail.message}
                                    </div>
                                `).join('')}
                            </div>
                        ` : ''}
                    `;

                    // Add successful participants to table
                    if (data.participants && data.participants.length > 0) {
                        data.participants.forEach(participant => {
                            addParticipantToTable(participant);
                        });
                    }

                    // Update participant count
                    updateParticipantCount(data.current_participants);

                    // Clear file input
                    fileInput.value = '';
                    document.getElementById('file-name').textContent = 'Belum ada file dipilih';

                    showMessage(data.message, 'success');
                }, 1000);
            } else {
                document.getElementById('import-progress').classList.add('hidden');
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('import-progress').classList.add('hidden');
            showMessage('Terjadi kesalahan saat mengimport. Silakan coba lagi.', 'error');
        });
    }

    // File input change handler
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('excel-file');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
                document.getElementById('file-name').textContent = fileName;
            });
        }
    });

    function initializeSelect2() {
        $('#quick_pegawai_select').select2({
            placeholder: 'Cari nama atau NIP pegawai...',
            allowClear: true,
            ajax: {
                url: '{{ route("pegawai.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: `${item.nama} (${item.nip}) - ${item.jabatan || '-'}`,
                                data: item
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });
    }

    $(document).ready(function() {
        initializeSelect2();
    });
</script>

@endpush
@endsection
