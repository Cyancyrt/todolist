<!-- Filter Tabs and Sorting -->
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
    <!-- Time Filter Tabs -->
    <div class="flex space-x-2">
        <div class="tab active">Hari Ini</div>
        <div class="tab">Minggu Ini</div>
        <div class="tab">Bulan Ini</div>
        <div class="tab" >Semua</div>
    </div>
    <!-- Sorting Dropdown -->
    <div class="sort-dropdown">
        <select id="sortSelect">
            <option value="due_time">Sort by Due Time</option>
            <option value="priority">Sort by Priority</option>
            <option value="status">Sort by Status</option>
        </select>
    </div>
</div>