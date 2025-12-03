<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content'); ?>

  <!-- Main Content -->
    <main class="flex-1 p-3">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Activity List</h2>
            <?= view('dashboard/activity/components/filter_tabs_user') ?>
        </div>
        <div id="toastContainer" class="fixed top-5 right-5 space-y-2 z-50"></div>
        <div class="mb-6 flex space-x-4" id="planningIcons">
            <a href="<?= base_url('dashboard/activity/create') ?>" class="planning-icon plus" id="createBtn" title="Create New Activities">
                <svg class="w-6 h-6 text-blue-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="planning-tooltip" id="createTooltip">Create New Personal Activities</span>
            </a>
            <div class="planning-icon burger" id="viewAllBtn">
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
        <?php if (empty($data)): ?>
            <!-- Empty State Message -->
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-4m0 0l-2 2m2-2l2 2"></path>
                </svg>
                <p class="text-lg font-medium text-gray-600 mb-2">You don't have any activities yet, create one to stay productive!</p>
                <p class="text-sm text-gray-500">Get started by adding your first activity and boost your productivity today.</p>
                <a href="<?= base_url('dashboard/activity/create') ?>" class="inline-flex items-center mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Your First Activity
                </a>
            </div>
        <?php else: ?>
            <ul class="space-y-4">
                <?php foreach ($data as $task) : ?>
                <?= view('dashboard/activity/components/main_task', ['task' => $task]); ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?= view('dashboard/activity/components/modal_task') ?>
</main>
<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div id="bulkModalContent" class="bg-white rounded-lg shadow-xl p-6 transform scale-95 opacity-0 transition-all duration-300 ease-out">
    <div class="flex items-center mb-4">
      <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <h3 class="text-lg font-semibold text-gray-800">Delete Confirmation</h3>
    </div>
    <p id="bulkDeleteMessage" class="text-gray-600 mb-4">Are you sure you want to delete selected tasks?</p>
    <div class="flex justify-end space-x-3">
      <button id="cancelBulkDelete" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">Cancel</button>
      <button id="confirmBulkDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Delete</button>
    </div>
  </div>
</div>

<script>const BULK_DELETE_URL = "<?= base_url('dashboard/activity/bulk-delete') ?>"; </script>

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
