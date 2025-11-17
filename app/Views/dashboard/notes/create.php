<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<main class="flex-1 min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-purple-50">
  <div class="w-full max-w-3xl">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
      <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <?= implode('<br>', session()->getFlashdata('errors')) ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <form id="note-form" class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 space-y-6 border border-gray-100"
          action="<?= base_url('dashboard/notes/save') ?>" method="POST" novalidate>
      <?php csrf_field() ?>

      <!-- Header dengan Ikon -->
      <div class="text-center space-y-2">
        <div class="flex items-center justify-center space-x-2">
          <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Buat Catatan Baru</h2>
        </div>
        <p class="text-gray-600 text-sm sm:text-base">Catat aktivitas dan pikiran Anda dengan mudah dan terorganisir.</p>
      </div>

      <!-- Informasi Catatan -->
      <div class="space-y-4">
        <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
          <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Informasi Catatan</span>
        </h3>

        <div class="grid grid-cols-1 gap-4">
          <label class="block">
            <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
              <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
              <span>Judul Catatan</span>
            </span>
            <input type="text" name="title" placeholder="Contoh: Ide proyek baru atau Renungan harian"
                   class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-200 hover:shadow-md"
                   required minlength="1" maxlength="150">
            <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Judul catatan wajib diisi, maksimal 150 karakter!</p>
          </label>

          <label class="block">
            <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
              <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
              </svg>
              <span>Isi Catatan (Opsional)</span>
            </span>
            <textarea name="content" placeholder="Tulis detail catatan Anda di sini... bisa berupa ide, catatan harian, atau apa saja!"
                      class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-200 hover:shadow-md resize-none"
                      maxlength="10000" rows="5"></textarea>
            <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Isi catatan maksimal 10.000 karakter, tapi boleh kosong ya!</p>
          </label>
        </div>
      </div>

      <!-- Aktivitas (Opsional) -->
      <div class="space-y-4">
        <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
          <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Aktivitas (Opsional)</span>
        </h3>

        <div x-data="{ open: false, selectedText: 'Pilih aktivitas (opsional)', selectedValue: '' }" class="relative">
          <button type="button"
                  @click="open = !open"
                  class="w-full text-left border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-blue-400 flex justify-between items-center hover:shadow-md transition-all duration-200">
            <span x-text="selectedText"></span>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Dropdown List -->
          <ul x-show="open" @click.outside="open = false"
              class="absolute z-10 bg-white border border-gray-300 rounded-lg mt-2 w-full max-h-48 overflow-y-auto shadow-lg transition-all duration-200">
            <?php if (isset($activities) && is_array($activities)): ?>
              <?php foreach ($activities as $activity): ?>
                <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                    @click="selectedText='<?= esc($activity['name']) ?>'; selectedValue='<?= esc($activity['id']) ?>'; open=false;">
                  <?= esc($activity['name']) ?>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>

          <!-- Hidden input untuk dikirim ke server -->
          <input type="hidden" name="activity_id" :value="selectedValue">
        </div>
      </div>

      <!-- Tombol Simpan -->
      <div class="pt-4">
        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-4 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <span class="flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Simpan Catatan</span>
          </span>
        </button>
      </div>
    </form>
  </div>
</main>

<script src="//unpkg.com/alpinejs" defer></script>
<?= $this->endSection(); ?>
