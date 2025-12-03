<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<main class="flex-1 min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-purple-50">
  <!-- Toast Notification -->
<div id="toast" class="fixed top-6 right-6 px-4 py-3 rounded-lg shadow-lg text-white hidden opacity-0 transition-all duration-300 z-50"></div>

  <form id="profile-form" class="w-full max-w-2xl bg-white rounded-2xl shadow-xl p-6 sm:p-8 space-y-6 border border-gray-100"
        action="<?= base_url('dashboard/profile/update/' . $user['id']) ?>" method="POST" novalidate>
    <?php csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <!-- Header -->
    <div class="text-center space-y-2">
      <div class="flex items-center justify-center space-x-2">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Profil</h2>
      </div>
      <p class="text-gray-600 text-sm sm:text-base">Perbarui informasi akun Anda dengan mudah dan aman.</p>
    </div>

    <!-- Informasi Profil -->
    <div class="space-y-6">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span>Detail Profil</span>
      </h3>
      
      <!-- Nama -->
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          <span>Nama Lengkap</span>
        </span>
        <input type="text" name="name" placeholder="Masukkan nama lengkap Anda" 
               class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:shadow-md"
               value="<?= esc($user['name'] ?? '') ?>" required minlength="2" maxlength="100">
        <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Nama minimal 2 karakter dan maksimal 100 karakter!</p>
      </label>

      <!-- Email -->
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
          </svg>
          <span>Email</span>
        </span>
        <input type="email" name="email" placeholder="Masukkan alamat email Anda" 
               class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-200 hover:shadow-md"
               value="<?= esc($user['email'] ?? '') ?>" required>
        <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Masukkan alamat email yang valid!</p>
      </label>
    </div>

    <!-- Tombol Simpan dan Batal -->
    <div class="pt-4 flex flex-row justify-end gap-4 w-full">
      <a href="<?= base_url('dashboard/profile') ?>" 
        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-3 border border-red-400 text-sm font-medium rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition duration-200 ease-in-out">
        Batal
      </a>

      <button type="submit"
              class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-3 rounded-lg text-white bg-blue-600 hover:bg-blue-700 active:scale-95 transition duration-200 ease-in-out shadow-md hover:shadow-lg">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Simpan
      </button>
  </div>

  </form>
  <!-- Modal Konfirmasi -->
<div id="confirmModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl w-11/12 max-w-md p-6 space-y-4">
    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Perubahan</h3>
    <p class="text-gray-600 text-sm">Apakah Anda yakin ingin menyimpan perubahan profil?</p>

    <div class="flex justify-end gap-3 pt-2">
      <button id="cancelConfirm" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">
        Batal
      </button>
      <button id="confirmSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
        Ya, Simpan
      </button>
    </div>
  </div>
</div>

</main>

<script>
const form = document.getElementById("profile-form");
const confirmModal = document.getElementById("confirmModal");
const confirmSubmit = document.getElementById("confirmSubmit");
const cancelConfirm = document.getElementById("cancelConfirm");
const toastEl = document.getElementById("toast");

let isConfirmed = false;

// Saat klik submit, tampilkan modal dulu
form.addEventListener("submit", function (e) {
  if (!isConfirmed) {
    e.preventDefault();
    confirmModal.classList.remove("hidden");
    confirmModal.classList.add("flex");
  }
});

// Jika user klik "Ya, simpan"
confirmSubmit.addEventListener("click", () => {
  isConfirmed = true;
  confirmModal.classList.add("hidden");
  form.submit();
});

// Batalkan konfirmasi
cancelConfirm.addEventListener("click", () => {
  confirmModal.classList.add("hidden");
});


// -------------------- TOAST MESSAGE --------------------

// Ambil pesan dari session flash (PHP output)
<?php if (session()->getFlashdata('success')): ?>
  showToast("<?= session()->getFlashdata('success') ?>", "success");
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
  showToast("<?= implode('<br>', session()->getFlashdata('errors')) ?>", "error");
<?php endif; ?>


function showToast(message, type) {
  toastEl.innerHTML = message;

  if (type === "success") {
    toastEl.classList.add("bg-green-500");
    toastEl.classList.remove("bg-red-500");
  } else {
    toastEl.classList.add("bg-red-500");
    toastEl.classList.remove("bg-green-500");
  }

  toastEl.classList.remove("hidden");
  setTimeout(() => toastEl.classList.remove("opacity-0"), 10);

  setTimeout(() => {
    toastEl.classList.add("opacity-0");
    setTimeout(() => toastEl.classList.add("hidden"), 300);
  }, 3500);
}
</script>

<?= $this->endSection(); ?>
