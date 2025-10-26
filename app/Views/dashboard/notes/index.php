<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<main class="flex-1 p-6">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold">Note List</h2>
    <nav class="flex space-x-4">
      <button class="bg-blue-100 px-4 py-2 rounded hover:bg-blue-200 transition-colors">Personal</button>
      <button class="bg-green-100 px-4 py-2 rounded hover:bg-green-200 transition-colors">Social</button>
    </nav>
  </div>

  <!-- Mode buttons -->
  <div id="actionButtons" class="mb-6 flex space-x-4 transition-all duration-300">
    <!-- Create -->
    <div id="createIcon" class="planning-icon plus" title="Create New Note">
      <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
      </svg>
      <div class="planning-tooltip">Create New Note</div>
    </div>

    <!-- View All -->
    <div id="viewAllIcon" class="planning-icon burger" title="View All Notes">
      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
      </svg>
      <div class="planning-tooltip">View All Notes</div>
    </div>
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

<style>
  .scale-100 { transform: scale(1) !important; opacity: 1 !important; }
  .planning-icon, .delete-selected-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 48px; height: 48px; border-radius: 50%; cursor: pointer;
    transition: all 0.3s ease; position: relative; overflow: hidden;
  }
  .planning-icon.plus { background: linear-gradient(135deg, #E3F2FD, #BBDEFB); }
  .planning-icon.burger { background: linear-gradient(135deg, #E8F5E9, #C8E6C9); }
  .planning-icon:hover, .delete-selected-icon:hover { transform: scale(1.1); }
  .planning-tooltip {
    position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%);
    background: #333; color: white; padding: 4px 8px; border-radius: 4px;
    font-size: 12px; opacity: 0; visibility: hidden; transition: opacity 0.3s ease;
  }
  .planning-icon:hover .planning-tooltip { opacity: 1; visibility: visible; }
  .delete-selected-icon {
    background: linear-gradient(135deg, #FFCDD2, #EF5350);
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('deleteModal');
  const modalContent = document.getElementById('modalContent');
  const selectModeBtn = document.getElementById('selectModeBtn');
  const selectAllBtn = document.getElementById('selectAllBtn');
  const createIcon = document.getElementById('createIcon');
  const viewAllIcon = document.getElementById('viewAllIcon');
  const checkboxes = document.querySelectorAll('.note-checkbox');
  let noteToDelete = null;
  let isSelectMode = false;

  // Delete modal logic
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      noteToDelete = btn.closest('.note-item');
      modal.classList.remove('hidden');
      setTimeout(() => modalContent.classList.add('scale-100'), 10);
    });
  });

  document.getElementById('cancelDelete').addEventListener('click', () => {
    modalContent.classList.remove('scale-100');
    setTimeout(() => modal.classList.add('hidden'), 300);
  });

  document.getElementById('confirmDelete').addEventListener('click', () => {
    if (noteToDelete) noteToDelete.remove();
    modalContent.classList.remove('scale-100');
    setTimeout(() => modal.classList.add('hidden'), 300);
  });

  // Select mode
    selectModeBtn.addEventListener('click', () => {
    const active = selectModeBtn.dataset.active === 'true';
    selectModeBtn.dataset.active = !active;
    checkboxes.forEach(cb => cb.classList.toggle('hidden', active));
    selectAllBtn.classList.toggle('hidden', active);

    const deleteBtns = document.querySelectorAll('.delete-btn');

    if (!active) {
        // Masuk mode select
        isSelectMode = true;
        createIcon.style.display = 'none';
        viewAllIcon.style.display = 'none';
        showDeleteSelected();
        cancelSelectBtn.classList.remove('hidden'); // tampilkan tombol cancel
        deleteBtns.forEach(btn => btn.classList.add('hidden')); // ✅ sembunyikan tombol delete
    } else {
        // Keluar mode select
        isSelectMode = false;
        cancelSelectBtn.classList.add('hidden');
        deleteBtns.forEach(btn => btn.classList.remove('hidden')); // ✅ tampilkan kembali tombol delete
        restoreIcons();
    }
    });
    // Cancel select mode
    document.getElementById('cancelSelectBtn').addEventListener('click', () => {
        selectModeBtn.dataset.active = false;
        isSelectMode = false;
        checkboxes.forEach(cb => {
            cb.checked = false;
            cb.classList.add('hidden');
        });
        selectAllBtn.classList.add('hidden');
        cancelSelectBtn.classList.add('hidden');
        restoreIcons();

        // ✅ tampilkan kembali tombol delete
        document.querySelectorAll('.delete-btn').forEach(btn => btn.classList.remove('hidden'));
    });

  selectAllBtn.addEventListener('click', () => {
    const allChecked = [...checkboxes].every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
  });

  // Dynamic delete-selected icon
  function showDeleteSelected() {
    const container = document.getElementById('actionButtons');
    const del = document.createElement('div');
    del.id = 'deleteSelectedIcon';
    del.className = 'delete-selected-icon';
    del.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1z" />
        </svg>
      <div class="planning-tooltip">Delete Selected</div>
    `;
    del.addEventListener('click', () => {
      const selected = [...checkboxes].filter(cb => cb.checked);
      if (selected.length === 0) return alert('No notes selected');
      modal.classList.remove('hidden');
      setTimeout(() => modalContent.classList.add('scale-100'), 10);
    });
    container.appendChild(del);
  }

  function restoreIcons() {
    const container = document.getElementById('actionButtons');
    document.getElementById('deleteSelectedIcon')?.remove();
    createIcon.style.display = 'inline-flex';
    viewAllIcon.style.display = 'inline-flex';
  }

  // Edit note on click
  document.querySelectorAll('.note-item').forEach(item => {
    item.addEventListener('click', e => {
        // Abaikan klik di tombol delete atau checkbox
        if (e.target.closest('.delete-btn') || e.target.closest('.note-checkbox')) return;

        if (isSelectMode) {
        //  Jika sedang mode select, toggle checkbox
        const checkbox = item.querySelector('.note-checkbox');
        checkbox.checked = !checkbox.checked;
        } else {
        //  Jika bukan mode select, lanjutkan ke mode edit
        alert('Editing: ' + item.querySelector('h4').textContent);
        }
    });
    });
});
</script>

<?= $this->endSection() ?>
