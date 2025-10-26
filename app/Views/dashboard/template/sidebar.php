<!-- Sidebar: Simplified, Unique, and Hover-Expandable -->
<aside class="w-20 bg-white shadow-md p-4 flex flex-col items-center space-y-6 hidden md:flex overflow-hidden transition-all duration-400 ease-in-out hover:w-48 group">
    
    <!-- Dashboard -->
    <a href="<?= base_url('dashboard'); ?>" class="flex items-center space-x-4 w-full group-hover:pl-2 transition-all duration-300 ease-in-out">
        <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
            </svg>
        </div>
        <span class="sidebar-item text-gray-700 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Dashboard</span>
    </a>

    <!-- Tasks -->
    <a href="<?= base_url('dashboard/activity'); ?>" class="flex items-center space-x-4 w-full group-hover:pl-2 transition-all duration-300 ease-in-out">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <span class="sidebar-item text-gray-700 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Activity</span>
    </a>

    <!-- Notes -->
    <a href="<?= base_url('dashboard/notes'); ?>" id="notes" class="flex items-center space-x-4 w-full group-hover:pl-2 transition-all duration-300 ease-in-out">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </div>
        <span class="sidebar-item text-gray-700 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Notes</span>
    </a>

    <!-- Summary -->
    <a href="/summary" class="flex items-center space-x-4 w-full group-hover:pl-2 transition-all duration-300 ease-in-out">
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <span class="sidebar-item text-gray-700 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Summary</span>
    </a>

    <!-- Calendar -->
    <a href="<?= base_url('dashboard/calendar'); ?>" class="flex items-center space-x-4 w-full group-hover:pl-2 transition-all duration-300 ease-in-out">
        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <span class="sidebar-item text-gray-700 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Calendar</span>
    </a>

    <!-- Automation -->
    <a href="/automation" class="flex items-center space-x-4 w-full group-hover:pl-2 transition-all duration-300 ease-in-out">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <span class="sidebar-item text-gray-700 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Automation</span>
    </a>
</aside>
