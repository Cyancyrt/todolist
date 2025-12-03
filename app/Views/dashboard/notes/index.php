<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<style>
  /* Tambahkan CSS untuk checkbox */
  .invisible-checkbox {
    opacity: 0;
    pointer-events: none;
    position: absolute;
    left: 16px; /* Sesuaikan posisi horizontal */
    top: 50%;
    transform: translateY(-50%);
    transition: opacity 0.2s ease;
  }
  .checkbox-visible {
    opacity: 1;
    pointer-events: auto;
  }
  .note-item {
    position: relative; /* Pastikan relative untuk absolute positioning checkbox */
  }
</style>

<div class="flex-1 p-3">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold">Note List</h2>
  </div>
  
  <div id="toastContainer" class="fixed top-5 right-5 space-y-2 z-50"></div>

<?php if (session()->getFlashdata('errors')): ?>
  <div id="autoAlert" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
    <?= implode('<br>', session()->getFlashdata('errors')) ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
  <div id="autoAlert" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
    <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

  <div class="mb-6 flex space-x-4" id="planningIcons">
      <a href="<?= base_url('dashboard/notes/create') ?>" class="planning-icon plus" id="btnCreateNote" title="Create New Note">
          <svg class="w-6 h-6 text-blue-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          <span class="planning-tooltip" id="createTooltip">Create New Note</span>
      </a>

      <div class="planning-icon burger" id="btnViewAllNotes" onclick="alert('Navigating to View All Notes Page!')">
          <svg class="w-6 h-6 text-green-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
          </svg>
          <span class="planning-tooltip" id="viewTooltip">View All Notes</span>
      </div>

      <button class="planning-icon delete hidden" id="btnDeleteSelectedNotes" title="Delete Selected">
          <svg class="w-6 h-6 text-red-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          <span class="planning-tooltip">Delete Selected</span>
      </button>
  </div>

  <div class="flex justify-end mb-4 space-x-2">
    <button id="btnSelectModeNotes" class="flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-2 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400" title="Aktifkan mode pilih">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <span class="text-sm font-medium">Pilih</span>
    </button>
    
    <button id="btnSelectAllNotes" class="hidden flex items-center space-x-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-2 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400 animate-fade-in" title="Pilih semua catatan">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z M9 12l2 2 4-4"></path>
      </svg>
      <span class="text-sm font-medium">Pilih Semua</span>
    </button>
    
    <button id="btnCancelSelectNotes" class="hidden flex items-center space-x-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-2 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 animate-fade-in" title="Batalkan mode pilih">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
      <span class="text-sm font-medium">Batal</span>
    </button>
  </div>


  <div class="bg-white p-3 rounded-lg shadow-md">
    <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center space-x-2">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <span>Your Notes</span>
    </h3>
    <ul id="noteList" class="space-y-4">
      <?php if (empty($notes)): ?>
        <div class="text-center py-12">
            <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-4m0 0l-2 2m2-2l2 2"></path>
            </svg>
            <p class="text-lg font-medium text-gray-600 mb-2">You don't have any notes yet, create one to stay productive!</p>
            <p class="text-sm text-gray-500">Get started by jotting down your first note and organize your thoughts today.</p>
            <a href="<?= base_url('dashboard/notes/create') ?>" class="inline-flex items-center mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Your First Note
            </a>
        </div>
      <?php else: ?>
        <?php foreach ($notes as $note) { ?>
        <li class="note-item group relative flex justify-between items-center py-4 px-4 border-b border-gray-200 hover:bg-blue-50 transition-all duration-200 cursor-pointer rounded-lg" data-id="<?= $note['id'] ?>">
          <div class="flex items-start space-x-3 flex-1">
            <input type="checkbox" class="note-checkbox invisible-checkbox mt-1" />
            <div class="flex-1 min-w-0">
              <h4 class="text-lg font-semibold text-gray-800 truncate"><?= esc($note['title']) ?></h4>
              <p class="text-sm text-gray-600 mt-1 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                <?= truncate_text($note['content'], 100) ?>
              </p>
              <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                <span class="flex items-center space-x-1">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>Created: <?= date('d M Y, H:i', strtotime($note['created_at'])); ?></span>
                </span>
                <?php if (isset($note['updated_at']) && $note['updated_at'] != $note['created_at']): ?>
                <span class="flex items-center space-x-1">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  <span>Updated: <?= date('d M Y, H:i', strtotime($note['updated_at'])); ?></span>
                </span>
                <?php endif; ?>
                <?php if (isset($note['activity_name'])): ?>
                <span class="flex items-center space-x-1">
                  <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>Activity: <?= esc($note['activity_name']) ?></span>
                </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <a href="<?= base_url('dashboard/notes/edit/' . esc($note['id'])) ?>" 
               class="absolute top-3 right-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
              <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </a>
            <form action="<?= base_url('dashboard/notes/delete/' . $note['id']) ?>" method="post" class="deleteFormNotes">
              <?= csrf_field() ?>
                <button type="submit" class="delete-btn text-red-500 hover:text-red-700 transition-colors p-1" title="Delete Note">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1z" />
                    </svg>
                </button>
            </form>
          </div>
        </li>
        <?php } ?>
      <?php endif; ?>
    </ul>
  </div>
</div>

<div id="modalDeleteSingleNote" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div class="bg-white rounded-lg shadow-xl p-6 transform scale-95 opacity-0 transition-all duration-300 ease-out modal-content">
    <div class="flex items-center mb-4">
      <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <h3 class="text-lg font-semibold text-gray-800">Delete Confirmation</h3>
    </div>
    <p class="text-gray-600 mb-4">Are you sure you want to delete this note? This action cannot be undone.</p>
    <div class="flex justify-end space-x-3">
      <button id="btnCancelDeleteSingleNote" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">Cancel</button>
      <button id="btnConfirmDeleteSingleNote" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Delete</button>
    </div>
  </div>
</div>

<div id="modalBulkDeleteNotes" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div id="bulkModalContentNotes" class="modal-content bg-white rounded-lg shadow-xl p-6 transform scale-95 opacity-0 transition-all duration-300 ease-out">
    <div class="flex items-center mb-4">
      <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <h3 class="text-lg font-semibold text-gray-800">Delete Confirmation</h3>
    </div>
    <p id="msgBulkDeleteNotes" class="text-gray-600 mb-4">Are you sure you want to delete selected notes?</p>
    <div class="flex justify-end space-x-3">
      <button id="btnCancelModalNotes" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">Cancel</button>
      <button id="btnConfirmDeleteNotes" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Delete</button>
    </div>
  </div>
</div>


<script>const BULK_DELETE_URL = "<?= base_url('dashboard/notes/bulk-delete') ?>";</script>
<script src="<?= base_url('assets/js/main.js') ?>" type="module"></script>
<script src="<?= base_url('assets/js/notes/script.js') ?>"></script>

<?= $this->endSection() ?>