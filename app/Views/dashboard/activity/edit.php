<?= $this->extend('dashboard/template/layout') ?>
<?= $this->section('content'); ?>
<main class="flex-1 min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-purple-50">
  <form id="activity-form" class="w-full max-w-3xl bg-white rounded-2xl shadow-xl p-6 sm:p-8 space-y-6 border border-gray-100"
        action="<?= base_url('dashboard/activity/update/' . $activity['id']) ?>" method="POST" novalidate>
        <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <!-- Header dengan Ikon -->
    <div class="text-center space-y-2">
      <div class="flex items-center justify-center space-x-2">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Buat Aktivitas Baru</h2>
      </div>
      <p class="text-gray-600 text-sm sm:text-base">Rencanakan aktivitas Anda dengan mudah dan fleksibel.</p>
    </div>

    <!-- Informasi Umum -->
    <div class="space-y-4">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span>Informasi Umum</span>
      </h3>
      
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <span>Nama Aktivitas</span>
        </span>
        <input type="text" name="name" placeholder="Contoh: Belajar JavaScript atau Jalan-jalan ke pantai" 
               class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:shadow-md"
               required minlength="3" maxlength="150" value="<?= $activity['name'] ?>">
        <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Nama aktivitas minimal 3 karakter ya!</p>
      </label>

      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
          <span>Tipe Aktivitas</span>
        </span>
        <select name="type"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-200 hover:shadow-md">
            <option value="personal" <?= ($activity['type'] ?? '') === 'personal' ? 'selected' : '' ?>>
                Personal (untuk diri sendiri)
            </option>
            <option value="social" <?= ($activity['type'] ?? '') === 'social' ? 'selected' : '' ?>>
                Social (bersama orang lain)
            </option>
        </select>
      </label>

      <input type="hidden" name="created_by" value="<?= session()->get('user_id') ?>">

      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
          </svg>
          <span>Deskripsi (Opsional)</span>
        </span>
        <textarea name="description" placeholder="Ceritakan sedikit tentang aktivitas ini... misalnya, tujuan atau tips!"
                  class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-200 hover:shadow-md resize-none"
                  maxlength="500" rows="3"><?= $activity['description'] ?></textarea>
        <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Deskripsi maksimal 500 karakter, tapi boleh kosong ya!</p>
      </label>
    </div>

    <!-- Pengaturan Jadwal -->
    <div class="space-y-4">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Pengaturan Jadwal</span>
      </h3>
      
      <!-- Dalam div Pengaturan Jadwal -->
<label class="block">
  <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span>Tanggal & Waktu</span>
  </span>
  <!-- Input Flatpickr -->
  <input type="text" id="schedule-dates" 
         class="mt-1 block w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:shadow-md"
         placeholder="Klik untuk pilih satu atau beberapa tanggal & jam" required>
  <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Pilih satu atau beberapa tanggal & jam!</p>
  
  <!-- Container untuk chips + hidden inputs -->
  <div id="selected-dates" class="mt-3 flex flex-wrap gap-2"></div>
</label>

    <!-- Pengaturan Pengulangan -->
    <div class="space-y-4">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span>Pengaturan Pengulangan</span>
      </h3>
      
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span>Pengulangan</span>
        </span>
        <select name="recurrence"
            class="mt-1 block w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-200 hover:shadow-md">
            <option value="none"   <?= $activity['recurrence'] === 'none' ? 'selected' : '' ?>>Tidak Ada (sekali saja)</option>
            <option value="daily"  <?= $activity['recurrence'] === 'daily' ? 'selected' : '' ?>>Harian (setiap hari)</option>
            <option value="weekly" <?= $activity['recurrence'] === 'weekly' ? 'selected' : '' ?>>Mingguan (setiap minggu)</option>
            <option value="monthly"<?= $activity['recurrence'] === 'monthly' ? 'selected' : '' ?>>Bulanan (setiap bulan)</option>
        </select>
      </label>
    </div>

    <!-- Tombol Simpan -->
    <div class="pt-4">
      <button type="submit"
              class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-4 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <span class="flex items-center justify-center space-x-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>Simpan Aktivitas</span>
        </span>
      </button>
    </div>
  </form>
</main>

<!-- Flatpickr CSS & JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
const preselectedDates = <?= json_encode(array_map(function($d) {
      return date('Y-m-d\TH:i', strtotime($d['schedule_date']));
  }, $schedules)); ?>;
  // Inisialisasi Flatpickr multi-datetime
const fp = flatpickr("#schedule-dates", {
  enableTime: true,
  dateFormat: "Y-m-d H:i",
  mode: "multiple",
  time_24hr: true,
  defaultDate:preselectedDates,
  onChange: function(selectedDates, dateStr, instance) {
    updateSelectedDates(selectedDates);
  }
});
updateSelectedDates(fp.selectedDates);
function updateRecurrenceState() {
    const recurrenceSelect = document.querySelector('select[name="recurrence"]');
    const options = recurrenceSelect.options;

    if (fp.selectedDates.length > 1) {
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === 'daily' || options[i].value === 'weekly') {
                options[i].disabled = true;
            } else {
                options[i].disabled = false;
            }
        }

        // Jika opsi yang sekarang terpilih tidak valid, ganti ke 'none'
        if (recurrenceSelect.value === 'daily' || recurrenceSelect.value === 'weekly') {
            recurrenceSelect.value = 'none';
        }
    } else {
        // Aktifkan semua opsi
        for (let i = 0; i < options.length; i++) {
            options[i].disabled = false;
        }
    }
}

// Fungsi untuk update tampilan chips
function updateSelectedDates(dates) {
    const container = document.getElementById('selected-dates');
    container.innerHTML = ''; // Clear existing chips
    
    dates.forEach((date, index) => {
        const dateStr = date.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
        const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        
        const chip = document.createElement('div');
        chip.className = 'inline-flex items-center space-x-2 bg-gradient-to-r from-blue-100 to-purple-100 text-gray-800 px-3 py-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in';
        chip.innerHTML = `
          <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="text-sm font-medium">${dateStr} ${timeStr}</span>
          <button type="button" class="text-red-500 hover:text-red-700 hover:scale-110 transition-all duration-150" title="Hapus tanggal ini" data-index="${index}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        `;
        
        chip.querySelector('button').addEventListener('click', () => {
            fp.setDate(fp.selectedDates.filter((_, i) => i !== index));
            updateSelectedDates(fp.selectedDates);
        });
        
        container.appendChild(chip);
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'schedule_date[]';
        input.value = date.toISOString().slice(0, 16).replace('T', ' ');
        container.appendChild(input);
    });

    // Update state recurrence setiap kali tanggal berubah
    updateRecurrenceState();
}

// Tambah CSS untuk animasi fade-in (jika belum ada)
const style = document.createElement('style');
style.textContent = `
  @keyframes fade-in {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
  }
  .animate-fade-in {
    animation: fade-in 0.3s ease-out;
  }
`;
document.head.appendChild(style);

// Validasi real-time (tetap sama, tapi pastikan input valid jika ada dates)
document.querySelectorAll('#activity-form input, #activity-form textarea, #activity-form select').forEach(input => {
  input.addEventListener('input', () => {
    const parent = input.closest('label');
    const error = parent.querySelector('p');
    if (input.id === 'schedule-dates') {
      // Custom validasi: cek jika ada dates dipilih
      if (fp.selectedDates.length === 0) {
        error.classList.remove('hidden');
        input.classList.add('border-orange-400');
      } else {
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
        input.classList.add('border-green-400');
      }
    } else {
      if (!input.checkValidity()) {
        error.classList.remove('hidden');
        input.classList.add('border-orange-400');
      } else {
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
        input.classList.add('border-green-400');
      }
    }
  });
});

// Validasi form sebelum submit (update untuk cek dates)
document.getElementById('activity-form').addEventListener('submit', (e) => {
  let valid = true;
  document.querySelectorAll('#activity-form input, #activity-form textarea, #activity-form select').forEach(input => {
    const parent = input.closest('label');
    const error = parent.querySelector('p');
    if (input.id === 'schedule-dates') {
      if (fp.selectedDates.length === 0) {
        error.classList.remove('hidden');
        input.classList.add('border-orange-400');
        valid = false;
      } else {
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
      }
    } else {
      if (!input.checkValidity()) {
        error.classList.remove('hidden');
        input.classList.add('border-orange-400');
        valid = false;
      } else {
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
      }
    }
  });
  if (!valid) e.preventDefault();
});


  // Validasi real-time dengan feedback ramah
  document.querySelectorAll('#activity-form input, #activity-form textarea, #activity-form select').forEach(input => {
    input.addEventListener('input', () => {
      const parent = input.closest('label');
      const error = parent.querySelector('p');
      if (!input.checkValidity()) {
        error.classList.remove('hidden');
        input.classList.add('border-orange-400');
      } else {
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
        input.classList.add('border-green-400');
      }
    });
  });

  // Validasi form sebelum submit
  document.getElementById('activity-form').addEventListener('submit', (e) => {
    let valid = true;
    document.querySelectorAll('#activity-form input, #activity-form textarea, #activity-form select').forEach(input => {
      const parent = input.closest('label');
      const error = parent.querySelector('p');
      if (!input.checkValidity()) {
        error.classList.remove('hidden');
        input.classList.add('border-orange-400');
        valid = false;
      } else {
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
      }
    });
    if (!valid) e.preventDefault();
  });
</script>
<?= $this->endSection() ?>
