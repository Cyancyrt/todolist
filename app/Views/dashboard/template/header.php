<!-- Header -->
<header class="bg-white shadow-md p-4 flex justify-between items-center">
<div id="toggleSidebar" class="flex items-center gap-2 cursor-pointer select-none">
    <!-- Hamburger visible on mobile & tablet -->
    <svg id="hamburgerIcon" class="w-6 h-6 text-gray-600 lg:hidden" fill="none" 
         stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>

    <!-- Responsive Title -->
    <h1 class="font-bold text-blue-600 text-xl lg:text-2xl transition-all duration-200">
        RoutinePath
    </h1>
</div>


    <div class="flex items-center space-x-6">
        <!-- Real-Time Clock -->
        <div class="text-lg font-medium text-gray-700" id="realTimeClock">00:00:00</div>
        <!-- Profile Menu -->
        <div class="dropdown">
           <button class="flex items-center space-x-2 bg-gray-100 px-4 py-2 rounded hover:bg-gray-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-gray-700 hidden md:inline">Profile</span>
            </button>
            <div class="dropdown-menu">
                <a href="<?= base_url('dashboard/profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Profile</a>
                <button id="openLogoutModal" 
                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                    Logout
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center 
    z-[999] opacity-0 pointer-events-none transition-all duration-200">

    <div class="bg-white rounded-xl p-6 w-80 shadow-lg scale-90 transition-all duration-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-3 text-center">Confirm Logout</h2>
        <p class="text-gray-600 text-center mb-6">Are you sure you want to log out?</p>

        <div class="flex justify-center gap-3">
            <button id="cancelLogout" 
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
                Cancel
            </button>

            <form id="logoutForm" action="<?= base_url('/logout') ?>" method="POST">
                <button type="submit" 
                    class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
