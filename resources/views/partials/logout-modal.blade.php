<!-- Logout Confirmation Modal -->
<div id="logoutModal"
    class="fixed inset-0 hidden flex items-center justify-center h-full w-full z-50 backdrop-blur-sm bg-gray-900 bg-opacity-75 transition-opacity duration-300">
    <div class="p-5 border w-96 shadow-lg rounded-lg transform transition-all duration-300 scale-95 opacity-0"
        id="modalContent">
        <div class="mt-3">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
            </div>

            <!-- Message -->
            <div class="text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Konfirmasi Logout</h3>
                <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-3">
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                        Ya, Logout
                    </button>
                </form>
                <button type="button" onclick="hideLogoutConfirmation()"
                    class="flex-1 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
