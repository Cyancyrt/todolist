<!-- Header -->
<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <div class="flex items-center">
        <h1 class="text-2xl font-bold text-blue-600">RoutineHub</h1>
    </div>
    <div class="flex items-center space-x-6">
        <!-- Real-Time Clock -->
        <div class="text-lg font-medium text-gray-700" id="realTimeClock">00:00:00</div>
        <!-- Profile Menu -->
        <div class="dropdown">
            <button class="flex items-center space-x-2 bg-gray-100 px-4 py-2 rounded hover:bg-gray-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-gray-700">Profile</span>
            </button>
            <div class="dropdown-menu">
                <a href="<?= base_url('dashboard/profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Profile</a>
                <form action="<?= base_url('/logout') ?>" method="POST" class="m-0">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>

    