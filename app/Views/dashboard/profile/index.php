<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<main class="flex-1 min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-purple-50">
  <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl p-6 sm:p-8 space-y-6 border border-gray-100">
    
    <!-- Header -->
    <div class="text-center space-y-2">
      <div class="flex items-center justify-center space-x-2">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Profil Saya</h2>
      </div>
      <p class="text-gray-600 text-sm sm:text-base">Informasi akun Anda yang dapat dilihat dan dikelola.</p>
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
  <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 border-opacity-50">
    <label class="block text-sm font-medium text-gray-700 mb-2">
      Nama Lengkap
    </label>
    <p class="text-gray-900 text-base">
      <?= esc($user['name'] ?? 'Tidak tersedia') ?>
    </p>
    <p class="text-xs text-gray-500 mt-1">Nama lengkap Anda yang terdaftar di akun ini.</p>
  </div>

  <!-- Username -->
  <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 border-opacity-50">
    <label class="block text-sm font-medium text-gray-700 mb-2">
      Role
    </label>
    <p class="text-gray-900 text-base">
      <?= esc($user['role'] ?? 'Tidak tersedia') ?>
    </p>
    <p class="text-xs text-gray-500 mt-1">Role Anda untuk identifikasi.</p>
  </div>

  <!-- Email -->
  <div class="bg-gray-50 p-4 rounded-lg border border-black-200 border-opacity-50">
    <label class="block text-sm font-medium text-gray-700 mb-2">
      Email
    </label>
    <p class="text-gray-900 text-base">
      <?= esc($user['email'] ?? 'Tidak tersedia') ?>
    </p>
    <p class="text-xs text-gray-500 mt-1">Alamat email utama Anda untuk notifikasi dan komunikasi.</p>
  </div>
</div>

    <!-- Tombol Aksi (Opsional, jika ingin edit) -->
    <div class="pt-4 flex justify-center">
      <a href="<?= base_url('dashboard/profile/edit/' . $user['id']) ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Edit Profil
      </a>
    </div>
  </div>
</main>
<?= $this->endSection(); ?>
