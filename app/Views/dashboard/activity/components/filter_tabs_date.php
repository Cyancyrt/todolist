<!-- Filter Tabs and Sorting -->
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
    <!-- Time Filter Tabs -->
    <div class="flex space-x-2" id="timeFilters">
        <button class="tab px-4 py-2 rounded-lg <?= ($filter == 'today') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" 
                data-filter="today">Hari Ini</button>
        
        <button class="tab px-4 py-2 rounded-lg <?= ($filter == 'week') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" 
                data-filter="week">Minggu Ini</button>
        
        <button class="tab px-4 py-2 rounded-lg <?= ($filter == 'month') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" 
                data-filter="month">Bulan Ini</button>
        
        <button class="tab px-4 py-2 rounded-lg <?= ($filter == 'all') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" 
                data-filter="all">Semua</button>
    </div>

    <div class="sort-dropdown">
        <select id="sortSelect" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="due_time" <?= ($sort == 'due_time') ? 'selected' : '' ?>>Sort by Due Time</option>
            <option value="priority" <?= ($sort == 'priority') ? 'selected' : '' ?>>Sort by Priority</option>
            <option value="status"   <?= ($sort == 'status')   ? 'selected' : '' ?>>Sort by Status</option>
        </select>
    </div>


</div>