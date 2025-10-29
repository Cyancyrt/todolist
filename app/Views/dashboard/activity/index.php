<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content'); ?>

  <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Activity List</h2>
            <?= view('dashboard/activity/components/filter_tabs_user') ?>
        </div>
        <div class="mb-6 flex space-x-4" id="planningIcons">
            <a href="<?= base_url('dashboard/activity/create') ?>" class="planning-icon plus" id="createBtn" title="Create New Activities">
                <svg class="w-6 h-6 text-blue-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="planning-tooltip" id="createTooltip">Create New Personal Activities</span>
            </a>
            <div class="planning-icon burger" id="viewAllBtn" onclick="alert('Navigating to View All Tasks Page!')">
                <svg class="w-6 h-6 text-green-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <span class="planning-tooltip" id="viewTooltip">View All Personal Activities</span>
            </div>
            <button class="planning-icon delete hidden" id="deleteSelectedBtn" title="Delete Selected">
                <svg class="w-6 h-6 text-red-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span class="planning-tooltip">Delete Selected</span>
            </button>
        </div>
        <?= view('dashboard/activity/components/filter_tabs_date') ?>
      <!-- Task List -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-700 mb-2 sm:mb-0 title-case">Your Personal Activities</h3>
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
            <?php foreach ($data as $task) : ?>
            <?= view('dashboard/activity/components/main_task', ['task' => $task]); ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?= view('dashboard/activity/components/modal_task') ?>
</main>
<script src="<?= base_url('assets/js/main.js') ?>" type="module"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll('#activityTabs .activity-tab');
  const createTooltip = document.getElementById('createTooltip');
  const createIcon = document.querySelector('#createBtn svg');
  const viewTooltip = document.querySelector('#viewAllBtn .planning-tooltip');
  const viewIcon = document.querySelector('#viewAllBtn svg');
  const titleCase = document.querySelector('h3.title-case');

  // Default (personal)
  let currentType = 'personal';

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // Ambil tipe tab (personal/social)
      const selectedType = tab.getAttribute('data-type');
      currentType = selectedType;

      // Ubah teks tooltip sesuai tipe
      if (selectedType === 'social') {
        titleCase.textContent = 'Your Social Activities';
        createTooltip.textContent = 'Create New Social Activities';
        viewTooltip.textContent = 'View All Social Activities';

        // Ganti warna ikon agar terasa beda konteks
        createIcon.classList.remove('text-blue-600');
        createIcon.classList.add('text-green-600');
        viewIcon.classList.remove('text-green-600');
        viewIcon.classList.add('text-blue-600');
      } else {
        titleCase.textContent = 'Your Personal Activities';
        createTooltip.textContent = 'Create New Personal Activities';
        viewTooltip.textContent = 'View All Personal Activities';

        // Kembalikan warna semula
        createIcon.classList.remove('text-green-600');
        createIcon.classList.add('text-blue-600');
        viewIcon.classList.remove('text-blue-600');
        viewIcon.classList.add('text-green-600');
      }

      // Tandai tab aktif
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });
});

</script>

<?= $this->endSection() ?>
