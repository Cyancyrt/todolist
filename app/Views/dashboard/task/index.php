
<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Task List</h2>
        <nav class="flex space-x-4">
          <button class="bg-blue-100 px-4 py-2 rounded hover:bg-blue-200">Personal</button>
          <button class="bg-green-100 px-4 py-2 rounded hover:bg-green-200">Social</button>
        </nav>
      </div>

      <!-- Unique "Planning" Icons: Mengganti tombol CRUD dengan ikon yang menarik dan tidak tampak seperti tombol biasa -->
      <div class="mb-6 flex space-x-4">
          <!-- Ikon untuk Create New Task: Ikon plus dengan efek hover yang membuat penasaran -->
          <a href="<?= base_url('dashboard/task/create') ?>" class="planning-icon plus" title="Create New Task">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <div class="planning-tooltip">Create New Task</div>
            </a>
          <!-- Ikon untuk View All Tasks: Ikon list dengan efek hover yang membuat penasaran -->
          <div class="planning-icon burger" onclick="alert('Navigating to View All Tasks Page!')">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
              </svg>
              <div class="planning-tooltip">View All Tasks</div>
          </div>
      </div>
      <!-- Filter Tabs and Sorting -->
      <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
          <!-- Time Filter Tabs -->
          <div class="flex space-x-2">
              <div class="tab active" onclick="filterTasks('today')">Hari Ini</div>
              <div class="tab" onclick="filterTasks('week')">Minggu Ini</div>
              <div class="tab" onclick="filterTasks('month')">Bulan Ini</div>
              <div class="tab" onclick="filterTasks('all')">Semua</div>
          </div>
          <!-- Sorting Dropdown -->
          <div class="sort-dropdown">
              <select id="sortSelect" onchange="sortTasks()">
                  <option value="due_time">Sort by Due Time</option>
                  <option value="priority">Sort by Priority</option>
                  <option value="status">Sort by Status</option>
              </select>
          </div>
      </div>
      <!-- Task List -->
      <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-medium text-gray-700 mb-4">Your Tasks</h3>
          <ul class="space-y-4">
              <!-- Item 1: Klik untuk edit, ikon delete dan tombol cek kalendar -->
              <li class="task-item flex justify-between items-center py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200" onclick="alert('Editing: Buang sampah ke TPA')">
                  <div>
                      <h4 class="text-lg font-semibold text-gray-800">Buang sampah ke TPA</h4>
                      <p class="text-sm text-gray-600"><strong>Time:</strong> 08:00 AM | <strong>Status:</strong> <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span> | <strong>Priority:</strong> <span class="bg-gray-100 text-red-500 px-2 py-1 rounded text-l text-bold">High</span></p>
                  </div>
                  <div class="flex items-center space-x-2">
                      <!-- Tombol cek kalendar: Lingkaran dengan ikon panah -->
                      <div class="action-icon" onclick="event.stopPropagation(); alert('Checking Calendar on Dashboard!')" title="Check Calendar">
                          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                      </div>
                      <!-- Ikon delete: Sampah merah -->
                      <svg class="w-5 h-5 text-red-500 cursor-pointer hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="event.stopPropagation(); alert('Deleting: Buang sampah ke TPA')">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                  </div>
              </li>
              <!-- Item 2: Klik untuk edit, ikon delete -->
              <li class="task-item flex justify-between items-center py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200" onclick="alert('Editing: List belanja mingguan')">
                  <div>
                      <h4 class="text-lg font-semibold text-gray-800">List belanja mingguan</h4>
                      <p class="text-sm text-gray-600"><strong>Time:</strong> Ongoing | <strong>Status:</strong> <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Completed</span></p>
                  </div>
                  <div class="flex items-center space-x-2">
                      <!-- Tombol cek kalendar: Lingkaran dengan ikon panah -->
                      <div class="action-icon" onclick="event.stopPropagation(); alert('Checking Calendar on Dashboard!')" title="Check Calendar">
                          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                      </div>
                      <!-- Ikon delete: Sampah merah -->
                      <svg class="w-5 h-5 text-red-500 cursor-pointer hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="event.stopPropagation(); alert('Deleting: Buang sampah ke TPA')">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                  </div>
              </li>
              <!-- Item 3: Klik untuk edit, ikon delete -->
              <li class="task-item flex justify-between items-center py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200" onclick="alert('Editing: Gotong royong RT')">
                  <div>
                      <h4 class="text-lg font-semibold text-gray-800">Gotong royong RT</h4>
                      <p class="text-sm text-gray-600"><strong>Time:</strong> 10:00 AM | <strong>Status:</strong> <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">In Progress</span></p>
                  </div>
                   <div class="flex items-center space-x-2">
                      <!-- Tombol cek kalendar: Lingkaran dengan ikon panah -->
                      <div class="action-icon" onclick="event.stopPropagation(); alert('Checking Calendar on Dashboard!')" title="Check Calendar">
                          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                      </div>
                      <!-- Ikon delete: Sampah merah -->
                      <svg class="w-5 h-5 text-red-500 cursor-pointer hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="event.stopPropagation(); alert('Deleting: Buang sampah ke TPA')">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                  </div>
              </li>
          </ul>
      </div>

    </main>
  </div>

<?= $this->endSection() ?>


