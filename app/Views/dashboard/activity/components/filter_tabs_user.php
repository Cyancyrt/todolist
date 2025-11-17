<nav class="flex space-x-2 sm:space-x-4 mb-6" 
     id="activityTabs" 
     data-default-type="<?= esc($type ?? 'personal') ?>" 
     role="tablist" 
     aria-label="Filter Activities">

    <!-- Personal Tab -->
    <button 
        class="activity-tab flex items-center space-x-2 px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-medium text-sm sm:text-base transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm bg-blue-600 text-white"
        data-type="personal"
        id="tab-personal"
        role="tab"
        aria-selected="<?= ($type ?? 'personal') === 'personal' ? 'true' : 'false' ?>"
        aria-controls="activity-list"
        title="Filter to Personal Activities">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Personal</span>
    </button>

    <!-- Social Tab (Coming Soon) -->
    <div class="relative group">
      <button 
          class="activity-tab flex items-center space-x-2 px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-medium text-sm sm:text-base bg-gray-300 text-gray-500 cursor-not-allowed shadow-sm"
          id="tab-social"
          role="tab"
          aria-selected="false"
          aria-disabled="true"
          title="Coming Soon"
          disabled>
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span>Social</span>
      </button>
      <span class="absolute left-1/2 -translate-x-1/2 -bottom-8 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
        🚧 Coming Soon
      </span>
    </div>

</nav>
