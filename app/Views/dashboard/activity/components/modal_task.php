<div id="subActivityModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-10 transition-opacity duration-300">
    <div class="bg-white w-full max-w-sm mx-4 rounded-xl shadow-lg p-6 transform scale-95 transition-transform duration-300" style="max-height: 80vh; overflow-y: auto;">
        <!-- Header dengan Tombol di Kanan Atas -->
        <div class="bg-blue-50 text-gray-800 p-4 rounded-t-xl -m-6 mb-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Detail Sub-Activity</h2>
            <!-- Tombol Edit dan Tutup di Kanan -->
            <div class="flex items-center gap-2">
                <a id="editSubtaskBtn"
                    href="#"
                        class="text-blue-600 hover:text-blue-700 flex items-center gap-1 text-sm font-medium px-2 py-1 rounded-md transition"
                        title="Edit Sub-Task">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="hidden sm:inline">Edit</span>
                    </a>
                <button class="close-sub-modal text-gray-600 hover:text-gray-800 flex items-center gap-1 text-sm font-medium px-2 py-1 rounded-md transition" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Body: Tetap sama -->
        <div class="sub-modal-body space-y-3 mt-4">
            <p class="text-sm text-gray-700"><strong>Nama:</strong> <span id="subName" class="text-gray-900">-</span></p>
            <div id="editorjs-checklist" class="editor-container"></div>
            <p class="text-sm text-gray-700 flex items-center gap-1">
                <strong class="text-gray-800">Batas Waktu:</strong>
                <span id="subDeadline" class="text-gray-900 font-medium">-</span>
            </p>
            <p class="text-sm text-gray-700"><strong>Status:</strong> <span id="subStatus" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span></p>
        </div>
        <!-- Tombol Checklist di Bawah: Tetap ada untuk konfirmasi -->
        <div class="flex flex-col gap-3 mt-6">
            <form method="POST" id="statusForm" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <button id="statusBtn" class="text-white text-sm font-medium px-4 py-3 rounded-lg transition-colors duration-200 min-h-[44px]">
                    <!-- Text diubah lewat JS -->
                </button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>

<style>
#editorjs-checklist {
  max-height: 100px;   /* atau sesuai kebutuhan */
  overflow-y: hidden;    /* scroll jika konten melebihi tinggi */
  border: 1px solid #e5e7eb; /* border tipis agar jelas */
  border-radius: 0.5rem;
  padding: 0.5rem;
  background-color: #f9fafb; /* beda warna agar terlihat read-only */
}
</style>