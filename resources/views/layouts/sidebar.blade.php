<aside id="sidebar"
    class="w-64 bg-white shadow-xl border-r border-purple-100 fixed lg:relative lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out h-full z-30 pt-16 lg:pt-0">
    <div class="h-full overflow-y-auto">
        <!-- Navigation menu -->
        <nav class="p-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->is('admin/dashboard') ? 'bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 border border-purple-200' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' }} transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Event -->
            <a href="{{ route('admin.events.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->is('admin/events*') ? 'bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 border border-purple-200' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' }} transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="font-medium">Event</span>
            </a>

            <!-- Logout -->
            <button type="button" onclick="showLogoutConfirmation()"
                class="flex items-center space-x-3 p-3 rounded-lg text-red-600 hover:bg-red-50 transition duration-200 w-full text-left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </nav>
    </div>
</aside>