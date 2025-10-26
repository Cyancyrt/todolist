
<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-xl font-semibold mb-6">Dashboard Overview</h2>
            
            <!-- Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-700">Today's Summary</h3>
                    <p class="text-2xl font-bold text-green-600">5 Tasks Completed</p>
                    <p class="text-sm text-gray-500">Out of 8 planned</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-700">Weekly Routine</h3>
                    <p class="text-2xl font-bold text-blue-600">3 Social Activities</p>
                    <p class="text-sm text-gray-500">Gotong royong scheduled</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-700">Automation Alerts</h3>
                    <p class="text-2xl font-bold text-orange-600">2 Pending</p>
                    <p class="text-sm text-gray-500">Notifications set</p>
                </div>
            </div>

            <!-- Interactive Calendar Section -->
            <!-- Penempatan rapi: Ditempatkan di atas tasks/notes untuk visibilitas tinggi, dengan grid responsif. Kalendar jelas untuk semua umur: Font besar, ikon sederhana, navigasi mudah (tombol prev/next), dan interaktif (klik hari untuk highlight task). -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex justify-between items-center mb-4">
                    <button id="prevMonth" class="bg-blue-100 p-2 rounded hover:bg-blue-200">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h3 id="monthYear" class="text-xl font-semibold text-gray-700">October 2023</h3>
                    <button id="nextMonth" class="bg-blue-100 p-2 rounded hover:bg-blue-200">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-2 text-center">
                    <div class="font-medium text-gray-600">Sun</div>
                    <div class="font-medium text-gray-600">Mon</div>
                    <div class="font-medium text-gray-600">Tue</div>
                    <div class="font-medium text-gray-600">Wed</div>
                    <div class="font-medium text-gray-600">Thu</div>
                    <div class="font-medium text-gray-600">Fri</div>
                    <div class="font-medium text-gray-600">Sat</div>
                    <!-- Days will be populated by JS -->
                </div>
                <div id="calendarDays" class="grid grid-cols-7 gap-2 mt-2">
                    <!-- Calendar days generated dynamically -->
                </div>
            </div>

            <!-- Tasks and Notes Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Real-Time To-Do List -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Real-Time Tasks</h3>
                    <ul class="space-y-2">
                        <li class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span>Buang sampah ke TPA - 08:00 AM</span>
                            <button class="bg-green-500 text-white px-2 py-1 rounded text-sm">Done</button>
                        </li>
                        <li class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span>List belanja mingguan - Ongoing</span>
                            <button class="bg-green-500 text-white px-2 py-1 rounded text-sm">Done</button>
                        </li>
                        <li class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span>Gotong royong RT - 10:00 AM (Social)</span>
                            <button class="bg-green-500 text-white px-2 py-1 rounded text-sm">Done</button>
                        </li>
                    </ul>
                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">Add New Task</button>
                </div>

                <!-- Notes and Activities -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Notes & Activities</h3>
                    <div class="space-y-4">
                        <div class="p-3 bg-gray-50 rounded">
                            <h4 class="font-medium">Resep Masak Nasi Goreng</h4>
                            <p class="text-sm text-gray-600">Relasi: Task memasak pukul 12:00. Bahan: nasi, telur, bawang...</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded">
                            <h4 class="font-medium">Prosedur Gotong Royong</h4>
                            <p class="text-sm text-gray-600">Relasi: Social activity. Langkah: Kumpul di lapangan, bagi tugas...</p>
                        </div>
                    </div>
                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">Add Note</button>
                </div>
            </div>
        </main>
    </div>
    <?php $this->endSection(); ?>
    <?php $this->section('scripts'); ?>
    <script src="<?= base_url('assets/js/calendar.js') ?>"></script>
    <?php $this->endSection(); ?>