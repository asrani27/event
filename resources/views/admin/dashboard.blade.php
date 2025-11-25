@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Event Management</h2>
        <p class="text-gray-600">Selamat datang! Berikut adalah ringkasan statistik event Anda hari ini.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Events Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-100 hover:shadow-xl transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Event</p>
                    <p class="text-2xl font-bold text-gray-800">{{ App\Models\Event::count() }}</p>
                    <p class="text-xs text-green-600 mt-2">Semua event terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-blue-100 hover:shadow-xl transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Event Akan Datang</p>
                    <p class="text-2xl font-bold text-gray-800">{{ App\Models\Event::where('status', 'upcoming')->count() }}</p>
                    <p class="text-xs text-blue-600 mt-2">Belum dimulai</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ongoing Events Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-green-100 hover:shadow-xl transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Sedang Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-800">{{ App\Models\Event::where('status', 'ongoing')->count() }}</p>
                    <p class="text-xs text-green-600 mt-2">Event aktif</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Participants Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-pink-100 hover:shadow-xl transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-800">{{ App\Models\Participant::count() }}</p>
                    <p class="text-xs text-pink-600 mt-2">Semua peserta terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Events by Status Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Event berdasarkan Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Akan Datang</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ App\Models\Event::where('status', 'upcoming')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Sedang Berlangsung</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ App\Models\Event::where('status', 'ongoing')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Selesai</span>
                    </div>
                    <span class="text-lg font-bold text-gray-600">{{ App\Models\Event::where('status', 'completed')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Dibatalkan</span>
                    </div>
                    <span class="text-lg font-bold text-red-600">{{ App\Models\Event::where('status', 'cancelled')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Terbaru</h3>
            <div class="space-y-4">
                @php
                    $recentEvents = App\Models\Event::latest()->take(5)->get();
                @endphp
                @if($recentEvents->count() > 0)
                    @foreach($recentEvents as $event)
                    <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-200">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $event->title }}</p>
                            <p class="text-xs text-gray-500">{{ $event->date->format('d M Y') }} - {{ $event->location }}</p>
                        </div>
                        @switch($event->status)
                            @case('upcoming')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Akan Datang
                                </span>
                                @break
                            @case('ongoing')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Berlangsung
                                </span>
                                @break
                            @case('completed')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Selesai
                                </span>
                                @break
                            @case('cancelled')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Dibatalkan
                                </span>
                                @break
                        @endswitch
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500">Belum ada event</p>
                        <p class="text-sm text-gray-400 mt-1">Buat event pertama Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.events.create') }}"
                class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Event Baru
            </a>
            <a href="{{ route('admin.events.index') }}"
                class="flex items-center justify-center px-4 py-2 bg-white border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Kelola Event
            </a>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center justify-center px-4 py-2 bg-white border-2 border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Keluar
            </a>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection
