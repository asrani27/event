<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Event Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg border-b border-purple-100 fixed top-0 left-0 right-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side - Logo and menu toggle -->
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button id="mobileMenuButton"
                        class="lg:hidden p-2 rounded-md text-gray-600 hover:text-purple-600 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Logo -->
                    <div class="flex items-center ml-2 lg:ml-0">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">E</span>
                            </div>
                        </div>
                        <h1
                            class="ml-3 text-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                            Event Management
                        </h1>
                    </div>
                </div>

                <!-- Right side - User menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->

                    <!-- User dropdown -->
                    <div class="relative">
                        <button
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-50 transition duration-200">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">A</span>
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">Admin User</span>
                            <svg class="hidden md:block w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex h-screen pt-16">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto lg:ml-0">
            @yield('content')
        </main>
    </div>

    <!-- Logout Confirmation Modal -->
    @include('partials.logout-modal')

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-opacity-50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Scripts -->
    @stack('scripts')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', toggleSidebar);

        // Logout confirmation functions
        function showLogoutConfirmation() {
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideLogoutConfirmation() {
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('modalContent');
            
            // Fade out animation
            modal.classList.remove('opacity-100');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            // Hide after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideLogoutConfirmation();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideLogoutConfirmation();
            }
        });
    </script>
</body>

</html>