<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>

  <!-- Main Content -->
    <main class="flex-1 p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Activity List</h2>
        <nav class="flex space-x-4">
          <button class="bg-blue-100 px-4 py-2 rounded hover:bg-blue-200">Personal</button>
          <button class="bg-green-100 px-4 py-2 rounded hover:bg-green-200">Social</button>
        </nav>
      </div>

      <!-- Unique "Planning" Icons: Mengganti tombol CRUD dengan ikon yang menarik dan tidak tampak seperti tombol biasa -->
        <div class="mb-6 flex space-x-4" id="planningIcons">
            <!-- Create New Task -->
            <a href="<?= base_url('dashboard/activity/create') ?>" class="planning-icon plus" id="createBtn" title="Create New Task">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <div class="planning-tooltip">Create New Activities</div>
            </a>

            <!-- View All Tasks -->
            <div class="planning-icon burger" id="viewAllBtn" onclick="alert('Navigating to View All Tasks Page!')">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <div class="planning-tooltip">View All Activities</div>
            </div>

            <!-- Delete Selected (hidden default) -->
            <button class="planning-icon delete hidden" id="deleteSelectedBtn" title="Delete Selected">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <div class="planning-tooltip">Delete Selected</div>
            </button>
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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-700 mb-2 sm:mb-0">Your Activities</h3>
            <div class="flex space-x-2">
            <button id="selectModeBtn" class="flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400" title="Aktifkan mode pilih">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Pilih</span>
            </button>
            <button id="selectAllBtn" class="hidden flex items-center space-x-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400 animate-fade-in" title="Pilih semua aktivitas">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z M9 12l2 2 4-4"></path>
                </svg>
                <span class="text-sm font-medium">Pilih Semua</span>
            </button>
            <button id="cancelSelectBtn" class="hidden flex items-center space-x-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 animate-fade-in" title="Batalkan mode pilih">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="text-sm font-medium">Batal</span>
            </button>
            </div>
        </div>
          <ul class="space-y-4">
              <!-- Item 1: Klik untuk edit, ikon delete dan tombol cek kalendar -->
            <li class="task-item group relative flex justify-between items-center py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 cursor-pointer" data-id="1">
            <div class="flex items-start space-x-3">
                <input type="checkbox" class="task-checkbox hidden mt-1" />
                <!-- Ikon Expand/Collapse Baru -->
                <button class="expand-btn text-gray-500 hover:text-gray-700 mt-1" onclick="toggleSubTasks(this)">
                    <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-800">Buang sampah ke TPA</h4>
                    <p class="text-sm text-gray-600">
                        <strong>Time:</strong> 08:00 AM | 
                        <strong>Status:</strong> <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span> | 
                        <strong>Priority:</strong> <span class="bg-gray-100 text-red-500 px-2 py-1 rounded text-l text-bold">High</span>
                    </p>
                    <!-- Sub-Activities List Baru (Hidden Default) -->
                    <div class="sub-tasks mt-3 pl-6 hidden transition-all duration-300 ease-in-out max-h-0 overflow-hidden">
                        <ul class="space-y-2">
                            <!-- Contoh Sub-Task 1 -->
                            <li class="flex items-center space-x-2 text-sm text-gray-700">
                                <input type="checkbox" class="sub-task-checkbox" />
                                <span>Kumpulkan sampah organik</span>
                                <span class="bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Done</span>
                            </li>
                            <!-- Contoh Sub-Task 2 -->
                            <li class="flex items-center space-x-2 text-sm text-gray-700">
                                <input type="checkbox" class="sub-task-checkbox" />
                                <span>Angkut ke TPA</span>
                                <span class="bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded text-xs">Pending</span>
                            </li>
                            <!-- Jika kosong: <li class="text-sm text-gray-500">Tidak ada sub-activities</li> -->
                        </ul>
                    </div>
                </div>
            </div>

                    <div class="flex items-center space-x-2 item-actions">
                    <!-- Main action icon -->
                    <div class="action-icon relative" title="Actions">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>

                        <!-- Mini-action menu (hidden default) -->
                        <div class="mini-action-menu absolute right-0 top-full mt-2 w-36 bg-white border rounded-lg shadow-lg hidden z-10">
                            <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="event.stopPropagation(); alert('Checking Calendar!')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Calendar
                            </button>
                            <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="event.stopPropagation(); window.location.href='<?= base_url('dashboard/activity/create') ?>'">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Task
                            </button>
                        </div>
                    </div>

                    <!-- Delete icon tetap -->
                    <svg class="w-5 h-5 text-red-500 cursor-pointer hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="event.stopPropagation(); alert('Deleting: Buang sampah ke TPA')">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
            </li>
          </ul>
      </div>
    </main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
  const selectModeBtn = document.getElementById('selectModeBtn');
  const selectAllBtn = document.getElementById('selectAllBtn');
  const cancelSelectBtn = document.getElementById('cancelSelectBtn');
  const checkboxes = document.querySelectorAll('.task-checkbox');
  const itemActions = document.querySelectorAll('.item-actions');
  let isSelectMode = false;

  selectModeBtn.addEventListener('click', () => {
    isSelectMode = !isSelectMode;
    checkboxes.forEach(cb => cb.classList.toggle('hidden', !isSelectMode));
    selectAllBtn.classList.toggle('hidden', !isSelectMode);
    cancelSelectBtn.classList.toggle('hidden', !isSelectMode);

    // Toggle tombol CRUD
    createBtn.classList.toggle('hidden', isSelectMode);
    viewAllBtn.classList.toggle('hidden', isSelectMode);
    deleteSelectedBtn.classList.toggle('hidden', !isSelectMode);

    // Toggle visibility action icons per task
    itemActions.forEach(action => action.classList.toggle('hidden', isSelectMode));
});

  cancelSelectBtn.addEventListener('click', () => {
    isSelectMode = false;
    checkboxes.forEach(cb => { cb.checked = false; cb.classList.add('hidden'); });
    selectAllBtn.classList.add('hidden');
    cancelSelectBtn.classList.add('hidden');
    deleteSelectedBtn.classList.add('hidden');
    createBtn.classList.remove('hidden');
    viewAllBtn.classList.remove('hidden');

    // Tampilkan kembali action icons saat select mode dibatalkan
    itemActions.forEach(action => action.classList.remove('hidden'));
});

  selectAllBtn.addEventListener('click', () => {
    const allChecked = [...checkboxes].every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
  });

  // Klik item toggle checkbox jika sedang select mode
  document.querySelectorAll('.task-item').forEach(item => {
    item.addEventListener('click', e => {
      if (!isSelectMode) return; // jika bukan mode select abaikan
      if (e.target.closest('.action-icon') || e.target.closest('svg')) return; // abaikan klik icon
      const checkbox = item.querySelector('.task-checkbox');
      checkbox.checked = !checkbox.checked;
    });
  });
});
const createBtn = document.getElementById('createBtn');
const viewAllBtn = document.getElementById('viewAllBtn');
const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');



// Tombol Delete dipakai untuk hapus batch
deleteSelectedBtn.addEventListener('click', () => {
    const selectedTasks = [...checkboxes].filter(cb => cb.checked);
    if (selectedTasks.length === 0) {
        alert('Pilih minimal satu aktivitas untuk dihapus!');
        return;
    }

    if (!confirm(`Hapus ${selectedTasks.length} aktivitas terpilih?`)) return;

    selectedTasks.forEach(cb => {
        const item = cb.closest('.task-item');
        item.remove(); // atau panggil AJAX untuk hapus dari server
    });

    // Reset mode select setelah delete
    isSelectMode = false;
    checkboxes.forEach(cb => { cb.checked = false; cb.classList.add('hidden'); });
    selectAllBtn.classList.add('hidden');
    cancelSelectBtn.classList.add('hidden');
    deleteSelectedBtn.classList.add('hidden');
    createBtn.classList.remove('hidden');
    viewAllBtn.classList.remove('hidden');
});

</script>
<script>
document.querySelectorAll('.action-icon').forEach(icon => {
    const menu = icon.querySelector('.mini-action-menu');

    icon.addEventListener('click', e => {
        e.stopPropagation();
        // Tutup semua menu lain
        document.querySelectorAll('.mini-action-menu').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
        });
        menu.classList.toggle('hidden');
    });
});

// Tutup menu jika klik di luar
document.addEventListener('click', () => {
    document.querySelectorAll('.mini-action-menu').forEach(menu => menu.classList.add('hidden'));
});
</script>

<script>
    // Fungsi toggle sub-tasks
function toggleSubTasks(btn) {
    const subTasks = btn.closest('.task-item').querySelector('.sub-tasks');
    const icon = btn.querySelector('svg');
    
    if (subTasks.classList.contains('hidden')) {
        subTasks.classList.remove('hidden');
        subTasks.style.maxHeight = subTasks.scrollHeight + 'px'; // Smooth expand
        icon.style.transform = 'rotate(180deg)'; // Rotate chevron
    } else {
        subTasks.style.maxHeight = '0px'; // Smooth collapse
        setTimeout(() => subTasks.classList.add('hidden'), 300); // Delay untuk animasi
        icon.style.transform = 'rotate(0deg)';
    }
}
// Pastikan tidak konflik dengan event klik task-item (untuk select mode)
document.querySelectorAll('.task-item').forEach(item => {
    item.addEventListener('click', e => {
        if (e.target.closest('.expand-btn') || e.target.closest('.sub-tasks')) return; // Abaikan klik pada expand atau sub-tasks
        // ... (kode select mode yang sudah ada)
    });
});
</script>
<?= $this->endSection() ?>