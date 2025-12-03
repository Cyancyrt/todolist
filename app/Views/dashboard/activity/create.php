<?= $this->extend('dashboard/template/layout') ?>
<?= $this->section('content') ?>

<style>
@keyframes slide-in {
  from { opacity: 0; transform: translateX(50px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes fade-in {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}
.animate-slide-in { animation: slide-in 0.4s ease-out; }
.animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>

<?php if(session()->getFlashdata('error')): ?>
<div id="alert-error" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 animate-slide-in z-50">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    <span><?= session()->getFlashdata('error') ?></span>
    <button class="ml-3 text-white hover:text-gray-200" onclick="this.parentElement.remove()">✕</button>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('errors')): ?>
<?php foreach (session('errors') as $error): ?>
    <div id="alert-error" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 animate-slide-in z-50">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    <span><?= $error ?></span>
    <button class="ml-3 text-white hover:text-gray-200" onclick="this.parentElement.remove()">✕</button>
</div>
<?php endforeach; ?>
<?php endif; ?>

<main class="flex-1 min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-purple-50">
  <form id="activity-form" class="w-full max-w-3xl bg-white rounded-2xl shadow-xl p-6 sm:p-8 space-y-6 border border-gray-100"
        action="<?= base_url('dashboard/activity/save') ?>" method="POST" novalidate>
    
    <div class="text-center space-y-2">
      <div class="flex items-center justify-center space-x-2">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Buat Aktivitas Baru</h2>
      </div>
      <p class="text-gray-600 text-sm sm:text-base">Rencanakan aktivitas Anda dengan mudah dan fleksibel.</p>
    </div>

    <div class="space-y-4">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span>Informasi Umum</span>
      </h3>
      
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
          <span>Nama Aktivitas</span>
        </span>
        <input type="text" name="name" placeholder="Contoh: Belajar JavaScript" 
               class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:shadow-md"
               required minlength="3" maxlength="150">
        <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Nama aktivitas minimal 3 karakter ya!</p>
      </label>

      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
          <span>Tipe Aktivitas</span>
        </span>
        <select name="type" class="mt-1 block w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-200 hover:shadow-md">
          <option value="personal">Personal (untuk diri sendiri)</option>
          <option value="social">Social (bersama orang lain)</option>
        </select>
      </label>

      <input type="hidden" name="created_by" value="<?= session()->get('user_id') ?>">

      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
          <span>Deskripsi (Opsional)</span>
        </span>
        <textarea name="description" placeholder="Ceritakan sedikit tentang aktivitas ini..."
                  class="mt-1 block w-full border border-gray-300 rounded-lg p-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-200 hover:shadow-md resize-none"
                  maxlength="500" rows="3"></textarea>
      </label>
    </div>

    <div class="space-y-4">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>Pengaturan Jadwal</span>
      </h3>
      
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
          <span>Tanggal & Waktu</span>
        </span>
        <input type="text" id="schedule-dates" 
               class="mt-1 block w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:shadow-md cursor-pointer"
               placeholder="Klik untuk pilih satu atau beberapa tanggal & jam" required>
        <p class="text-orange-600 text-sm mt-1 hidden animate-pulse">💡 Pilih setidaknya satu tanggal & jam!</p>
        
        <div id="selected-dates" class="mt-3 flex flex-wrap gap-2"></div>
      </label>
    </div>

    <div class="space-y-4">
      <h3 class="flex items-center space-x-2 text-lg font-semibold text-gray-700">
        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        <span>Pengaturan Pengulangan</span>
      </h3>
      
      <label class="block">
        <span class="flex items-center space-x-2 text-gray-700 font-medium mb-2">
          <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
          <span>Pengulangan</span>
        </span>
        <select name="recurrence" class="mt-1 block w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-200 hover:shadow-md">
          <option value="none" selected>Tidak Ada (sekali saja)</option>
          <option value="daily">Harian (setiap hari)</option>
          <option value="weekly">Mingguan (setiap minggu)</option>
          <option value="monthly">Bulanan (setiap bulan)</option>
        </select>
      </label>
    </div>

    <div class="pt-4">
      <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-4 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <span class="flex items-center justify-center space-x-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          <span>Simpan Aktivitas</span>
        </span>
      </button>
    </div>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Hapus Alert Flashdata Otomatis
    setTimeout(() => {
        const alerts = document.querySelectorAll('#alert-success, #alert-error');
        alerts.forEach(el => {
            el.style.transition = "opacity 0.5s";
            el.style.opacity = "0";
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);

    // 2. Inisialisasi Flatpickr
    const fp = flatpickr("#schedule-dates", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        mode: "multiple",
        time_24hr: true,
        disableMobile: "true", // Penting mencegah UI native
        minDate: "today",      // Mencegah pemilihan tanggal di masa lalu via UI
        onChange: function(selectedDates, dateStr, instance) {
            updateSelectedDates(selectedDates);
            validateDateInput(); 
        }
    });

    // Mencegah submit saat enter di field tanggal
    document.getElementById('schedule-dates').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') e.preventDefault();
    });

    // Helper: Format Tanggal
    function formatLocal(date) {
        if (!date) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const h = String(date.getHours()).padStart(2, '0');
        const min = String(date.getMinutes()).padStart(2, '0');
        return `${y}-${m}-${d} ${h}:${min}`;
    }

    // Helper: Update Recurrence State
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
            if (recurrenceSelect.value === 'daily' || recurrenceSelect.value === 'weekly') {
                recurrenceSelect.value = 'none';
            }
        } else {
            for (let i = 0; i < options.length; i++) {
                options[i].disabled = false;
            }
        }
    }

    // Helper: Update UI Chips
    function updateSelectedDates(dates) {
        const container = document.getElementById('selected-dates');
        container.innerHTML = ''; 
        
        dates.forEach((date, index) => {
            const dateStr = date.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            const chip = document.createElement('div');
            chip.className = 'inline-flex items-center space-x-2 bg-gradient-to-r from-blue-100 to-purple-100 text-gray-800 px-3 py-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in';
            chip.innerHTML = `
              <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span class="text-sm font-medium">${dateStr} ${timeStr}</span>
              <button type="button" class="text-red-500 hover:text-red-700 hover:scale-110 transition-all duration-150" title="Hapus tanggal ini">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
            `;
            
            chip.querySelector('button').addEventListener('click', (e) => {
                e.preventDefault(); 
                const newDates = fp.selectedDates.filter((_, i) => i !== index);
                fp.setDate(newDates);
                updateSelectedDates(newDates);
                validateDateInput();
            });
            
            container.appendChild(chip);
            
            // Hidden Input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'next_run_at[]';
            input.value = formatLocal(date);  
            container.appendChild(input);
        });

        updateRecurrenceState();
    }

    // ============================================================
    // PERBAIKAN UTAMA: Validasi Tanggal (Empty & Past Date)
    // ============================================================
    function validateDateInput() {
        const input = document.getElementById('schedule-dates');
        const parent = input.closest('label');
        const error = parent.querySelector('p');

        // 1. Cek Apakah Kosong
        if (fp.selectedDates.length === 0) {
            error.textContent = "💡 Pilih setidaknya satu tanggal & jam!";
            error.classList.remove('hidden');
            error.classList.remove('text-red-600');
            error.classList.add('text-orange-600');
            
            input.classList.remove('border-green-400');
            input.classList.remove('border-red-400');
            input.classList.add('border-orange-400');
            return false;
        } 
        
        // 2. Cek Apakah Waktu Lampau (Logic JS)
        const now = new Date();
        now.setMinutes(now.getMinutes() - 1); // Toleransi 1 menit

        const hasPastDate = fp.selectedDates.some(date => date < now);

        if (hasPastDate) {
            error.textContent = "⛔ Tanggal tidak boleh waktu lampau!";
            error.classList.remove('hidden');
            error.classList.remove('text-orange-600');
            error.classList.add('text-red-600'); 

            input.classList.remove('border-green-400');
            input.classList.remove('border-orange-400');
            input.classList.add('border-red-400'); 
            return false;
        }

        // 3. Valid
        error.classList.add('hidden');
        input.classList.remove('border-orange-400');
        input.classList.remove('border-red-400');
        input.classList.add('border-green-400');
        return true;
    }

    // 3. Validasi Real-time Input Biasa
    // FIX: Tambahkan :not([type="hidden"]) untuk menghindari input created_by
    document.querySelectorAll('#activity-form input:not(#schedule-dates):not([type="hidden"]), #activity-form textarea, #activity-form select').forEach(input => {
        input.addEventListener('input', () => {
            const parent = input.closest('label');
            if (!parent) return; // Skip jika tidak ada label
            const error = parent.querySelector('p');
            
            if (!input.checkValidity()) {
                if(error) error.classList.remove('hidden');
                input.classList.add('border-orange-400');
                input.classList.remove('border-green-400');
            } else {
                if(error) error.classList.add('hidden');
                input.classList.remove('border-orange-400');
                input.classList.add('border-green-400');
            }
        });
    });

    // 4. Validasi Form saat Submit
    document.getElementById('activity-form').addEventListener('submit', (e) => {
        let isValid = true;

        if (!validateDateInput()) isValid = false;

        document.querySelectorAll('#activity-form input:not(#schedule-dates):not([type="hidden"]), #activity-form textarea, #activity-form select').forEach(input => {
            const parent = input.closest('label');
            if (!parent) return; // Skip
            const error = parent.querySelector('p');
            
            if (!input.checkValidity()) {
                if(error) error.classList.remove('hidden');
                input.classList.add('border-orange-400');
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            const firstError = document.querySelector('.border-orange-400, .border-red-400');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

});
</script>
<?= $this->endSection() ?>