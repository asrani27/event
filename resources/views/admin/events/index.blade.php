@extends('layouts.app')

@section('title', 'Event Management')

@section('content')
<div class="p-6 pb-20 lg:pb-6">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Manajemen Event</h2>
            <p class="text-gray-600">Kelola semua event dalam sistem</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
            class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-200 flex items-center space-x-2 mt-4 md:mt-0 w-full md:w-auto justify-center md:justify-start">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Event</span>
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Events Table -->
    <div class="bg-white rounded-xl shadow-lg border border-purple-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Peserta</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($events as $event)
                    <tr class="hover:bg-purple-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($event->description, 50)
                                }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $event->date->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $event->time->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($event->jenis)
                            @case('terbuka')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Terbuka
                            </span>
                            @break
                            @case('tertutup')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Tertutup
                            </span>
                            @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($event->status)
                            @case('upcoming')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Akan Datang
                            </span>
                            @break
                            @case('ongoing')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Sedang Berlangsung
                            </span>
                            @break
                            @case('completed')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Selesai
                            </span>
                            @break
                            @case('cancelled')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Dibatalkan
                            </span>
                            @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $event->current_participants }} / {{
                                $event->max_participants }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-gradient-to-r from-purple-600 to-pink-600 h-2 rounded-full"
                                    style="width: {{ ($event->current_participants / $event->max_participants) * 100 }}%">
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.events.show', $event) }}"
                                    class="text-purple-600 hover:text-purple-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.events.edit', $event) }}"
                                    class="text-blue-600 hover:text-blue-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada event</p>
                                <p class="text-sm mt-1">Mulai dengan membuat event pertama Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($events->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $events->links() }}
    </div>
    @endif
    <br /> <br /> <br /> <br />
</div>
@endsection
