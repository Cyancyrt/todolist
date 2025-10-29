<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<main class="flex-1 p-6">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold">Note List</h2>
  </div>

  <div class="mb-6 flex space-x-4" id="planningIcons">
      <a href="<?= base_url('dashboard/notes/create') ?>" class="planning-icon plus" id="createBtn" title="Create New Activities">
          <svg class="w-6 h-6 text-blue-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          <span class="planning-tooltip" id="createTooltip">Create New Note</span>
      </a>
      <div class="planning-icon burger" id="viewAllBtn" onclick="alert('Navigating to View All Tasks Page!')">
          <svg class="w-6 h-6 text-green-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
          </svg>
          <span class="planning-tooltip" id="viewTooltip">View All Notes</span>
      </div>
      <button class="planning-icon delete hidden" id="deleteSelectedBtn" title="Delete Selected">
          <svg class="w-6 h-6 text-red-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          <span class="planning-tooltip">Delete Selected</span>
      </button>
  </div>
  <!-- Select controls -->
  <div class="flex flex-col sm:flex-row justify-end mb-4 space-y-2 sm:space-y-0 sm:space-x-2">
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

  <!-- Note List -->
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-medium text-gray-700 mb-4">Your Notes</h3>
    <ul id="noteList" class="space-y-4">
      <!-- Example Note 1 -->
      <li class="note-item group relative flex justify-between items-center py-3 border-b border-gray-200 hover:bg-blue-50 transition-all duration-200 cursor-pointer" data-id="1">
        <div class="flex items-start space-x-3">
          <input type="checkbox" class="note-checkbox hidden mt-1" />
          <div>
            <h4 class="text-lg font-semibold text-gray-800">Resep Masak Nasi Goreng</h4>
            <p class="text-sm text-gray-600">Relasi: Task memasak pukul 12:00. Bahan: nasi, telur, bawang...</p>
          </div>
        </div>
        <button class="delete-btn text-red-500 hover:text-red-700 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1z" />
          </svg>
        </button>
        <div class="absolute top-3 right-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
        </div>
      </li>

      <!-- Example Note 2 -->
      <li class="note-item group relative flex justify-between items-center py-3 border-b border-gray-200 hover:bg-blue-50 transition-all duration-200 cursor-pointer" data-id="2">
        <div class="flex items-start space-x-3">
          <input type="checkbox" class="note-checkbox hidden mt-1" />
          <div>
            <h4 class="text-lg font-semibold text-gray-800">Prosedur Gotong Royong</h4>
            <p class="text-sm text-gray-600">Relasi: Social activity. Langkah: Kumpul di lapangan, bagi tugas...</p>
          </div>
        </div>
        <button class="delete-btn text-red-500 hover:text-red-700 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1z" />
          </svg>
        </button>
        <div class="absolute top-3 right-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
        </div>
      </li>
    </ul>
  </div>
</main>

<!-- Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div id="modalContent" class="bg-white rounded-lg shadow-xl p-6 transform scale-95 opacity-0 transition-all duration-300 ease-out">
    <div class="flex items-center mb-4">
      <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <h3 class="text-lg font-semibold text-gray-800">Delete Confirmation</h3>
    </div>
    <p class="text-gray-600 mb-4">Are you sure you want to delete this note? This action cannot be undone.</p>
    <div class="flex justify-end space-x-3">
      <button id="cancelDelete" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">Cancel</button>
      <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Delete</button>
    </div>
  </div>
</div>


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
