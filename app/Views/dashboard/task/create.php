<?= $this->extend('dashboard/template/layout') ?>
<?= $this->section('content') ?>

<main class="flex-1 min-h-screen flex flex-col items-center justify-center p-6">
  <div class="relative w-full">
    <button id="back-btn" type="button"
      class="absolute top-0 start-0 mt-4 mr-4 w-8 h-8 sm:w-10 sm:h-10 bg-white hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-blue-500 transition-all duration-200 hover:scale-110"
      title="Kembali ke Daftar Task">
      <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </button>
  </div>
  <form id="task-form" action="<?= base_url('dashboard/task/save') ?>" method="POST">
    <!-- 📄 Container semua "lembar kertas" -->
   <div id="paper-container" class="w-full max-w-6xl grid grid-flow-row place-items-center gap-6 transition-all">
      <div class="paper p-6 relative bg-white shadow-lg rounded-2xl transition-all duration-300 transform scale-100">
        <button id="clear-btn" type="button"
          class="absolute top-3 right-3 w-8 h-8 bg-transparent hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 transition-all duration-200 hover:scale-110"
          title="Hapus semua catatan di editor">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
        </button>
        <input 
          type="text" 
          name="titles[]" 
          placeholder="Judul Task" 
          class="w-full text-2xl font-semibold border-none outline-none bg-transparent mb-4 placeholder-gray-400"
        >
        <div id="editorjs" class="editor-container mb-6"></div>

        <!-- Ganti bagian input/select yang lama -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6 flex-wrap items-center justify-start">
      <div id="due-time-btn" class="flex items-center gap-2 p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-200 transform hover:scale-105 cursor-pointer">
        <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <span id="due-time-text" class="text-gray-700 text-sm sm:text-base truncate">Pilih Tanggal & Waktu</span>
        <input type="hidden" id="due-time-value" name="due_time">
      </div>
      <!-- Prioritas -->
      <button type="button" id="priority-btn" class="flex items-center gap-2 p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-200 transform hover:scale-105 min-w-0 flex-1 sm:flex-none">
        <svg id="priority-icon" class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
          <!-- Ikon bendera default (abu-abu) -->
          <path d="M4 21V3h16l-2 5 2 5H4z"/>
        </svg>
        <input type="hidden" id="priority-value" name="priority">
        <span id="priority-text" class="text-gray-700 text-sm sm:text-base truncate">Pilih Prioritas</span>
      </button>

      <!-- Pengulangan (Recurrence) -->
      <button type="button" id="recurrence-btn" class="flex items-center gap-2 p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-200 transform hover:scale-105 min-w-0 flex-1 sm:flex-none">
        <svg class="w-6 h-6 text-green-500 animate-spin-slow flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <input type="hidden" id="recurrence-value" name="recurrence">
        <span id="recurrence-text" class="text-gray-700 text-sm sm:text-base truncate">Pilih Pengulangan</span>
      </button>
    </div>

      <!-- Modal untuk Prioritas (sederhana, muncul saat klik) -->
      <div id="priority-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
          <h3 class="text-lg font-semibold mb-4">Pilih Prioritas</h3>
          <div class="flex gap-4">
            <button type="button" class="priority-option flex flex-col items-center p-4 rounded-lg bg-red-100 hover:bg-red-200" data-value="high">
              <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4 21V3h16l-2 5 2 5H4z"/></svg>
              <span class="text-sm mt-2">Tinggi</span>
            </button>
            <button type="button" class="priority-option flex flex-col items-center p-4 rounded-lg bg-yellow-100 hover:bg-yellow-200" data-value="medium">
              <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4 21V3h16l-2 5 2 5H4z"/></svg>
              <span class="text-sm mt-2">Sedang</span>
            </button>
            <button type="button" class="priority-option flex flex-col items-center p-4 rounded-lg bg-green-100 hover:bg-green-200" data-value="low">
              <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4 21V3h16l-2 5 2 5H4z"/></svg>
              <span class="text-sm mt-2">Rendah</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Modal untuk Recurrence (lengkap) -->
      <div id="recurrence-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
          <h3 class="text-lg font-semibold mb-4">Pilih Pengulangan</h3>
          <div class="grid grid-cols-2 gap-4">
            <!-- Tidak Ada -->
            <button type="button" class="recurrence-option flex flex-col items-center p-4 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all duration-150" data-value="none">
              <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
              <span class="text-sm mt-2">Tidak Ada</span>
            </button>

            <!-- Harian -->
            <button type="button" class="recurrence-option flex flex-col items-center p-4 rounded-lg bg-blue-100 hover:bg-blue-200 transition-all duration-150" data-value="daily">
              <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
              <span class="text-sm mt-2">Harian</span>
            </button>

            <!-- Mingguan -->
            <button type="button" class="recurrence-option flex flex-col items-center p-4 rounded-lg bg-yellow-100 hover:bg-yellow-200 transition-all duration-150" data-value="weekly">
              <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8h18M3 16h18M8 4v16M16 4v16" />
              </svg>
              <span class="text-sm mt-2">Mingguan</span>
            </button>

            <!-- Bulanan -->
            <button type="button" class="recurrence-option flex flex-col items-center p-4 rounded-lg bg-green-100 hover:bg-green-200 transition-all duration-150" data-value="monthly">
              <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="text-sm mt-2">Bulanan</span>
            </button>
          </div>
        </div>
      </div>


        <!-- Hidden input untuk form -->
        <input type="hidden" name="contents[]" class="editor-content-input">
      </div>
    </div>

    <!-- Tombol utama -->
    <button id="create-btn"
      class="w-full mt-6 text-white font-medium py-3 px-4 rounded-lg bg-gradient-to-r from-blue-500 to-green-500">
      Buat Task
    </button>
  </form>

  <!-- Tombol ➕ mengambang -->
  <button id="add-paper-btn" type="button"
    class="fixed bottom-10 right-10 bg-blue-600 text-white text-3xl w-14 h-14 rounded-full shadow-lg hover:scale-110 transition-transform duration-200">➕</button>
</main>

<!-- 📚 EditorJS -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  let editors = [];

  function initEditor(holderId) {
    const editor = new EditorJS({
      holder: holderId,
      tools: {
        header: Header,
        list: EditorjsList,
        paragraph: Paragraph,
        checklist: { class: Checklist, inlineToolbar: true }
      },
      placeholder: "Tulis catatan di sini...",
      data: {
        time: Date.now(),
        blocks: [
          { type: "checklist", data: { items: [{ text: "aktivitas 1", checked: true }] } }
        ]
      }
    });
    editors.push(editor);
  }

  initEditor('editorjs');

  // ➕ Tambah note baru
document.getElementById('add-paper-btn').addEventListener('click', () => {
  const container = document.getElementById('paper-container');
  const newId = `editorjs-${Date.now()}`;
  const paper = document.createElement('div');
  paper.className = "paper p-6 relative bg-white shadow-lg rounded-2xl opacity-0 scale-95 transition-all duration-300";

  paper.innerHTML = `
    <button type="button" class="delete-note-btn absolute top-3 right-3 text-red-500 text-xl hover:scale-125 transition-transform duration-150">✖</button>
    <input type="text" name="titles[]" placeholder="Judul Note Baru"
      class="w-full text-2xl font-semibold border-none outline-none bg-transparent mb-4 placeholder-gray-400">
    <div id="${newId}" class="editor-container mb-6"></div>
    <input type="hidden" name="contents[]" class="editor-content-input">
  `;

  container.appendChild(paper);
  initEditor(newId);

  // Animasi masuk
  requestAnimationFrame(() => {
    paper.style.opacity = 1;
    paper.style.transform = 'scale(1)';
  });

  // ✨ Tambahkan class 'multiple' agar grid bisa wrap ke bawah
  if (container.children.length > 1) {
    container.classList.add("multiple");
  }

  // 🗑️ Hapus note
  paper.querySelector('.delete-note-btn').addEventListener('click', () => {
    paper.style.opacity = 0;
    paper.style.transform = "scale(0.9)";
    setTimeout(() => {
      paper.remove();
      if (container.children.length <= 1) {
        container.classList.remove("multiple");
      }
    }, 200);
  });
});

  // 🧹 Hapus semua isi EditorJS
  document.getElementById('clear-btn').addEventListener('click', async () => {
    for (const e of editors) await e.clear();
    alert("🧹 Semua catatan dihapus!");
  });

  // 🚪 Kembali ke daftar task
  document.getElementById('back-btn').addEventListener('click', () => {
    window.location.href = "<?= base_url('dashboard/task') ?>";
  });

  // 📨 Saat form disubmit
  document.getElementById('create-btn').addEventListener('click', async (e) => {
    e.preventDefault();
    const form = document.getElementById('task-form');
    const contentInputs = document.querySelectorAll('.editor-content-input');

    for (let i = 0; i < editors.length; i++) {
      const output = await editors[i].save();
      contentInputs[i].value = JSON.stringify(output);
    }

    form.submit();
  });
</script>
<script>
// Benerin Flatpickr untuk tanggal (pastikan CDN dimuat)
flatpickr("#due-time-btn", {
  enableTime: true,
  dateFormat: "Y-m-d H:i",
  clickOpens: true,
  onChange: function(selectedDates, dateStr) {
    document.getElementById('due-time-text').textContent = dateStr || "Pilih Tanggal & Waktu";
    document.getElementById('due-time-value').value = dateStr;
  }
});

// Modal Prioritas
document.getElementById('priority-btn').addEventListener('click', () => {
  document.getElementById('priority-modal').classList.remove('hidden');
});

document.querySelectorAll('.priority-option').forEach(btn => {
  btn.addEventListener('click', () => {
    const value = btn.dataset.value;
    document.getElementById('priority-text').textContent = value === 'high' ? 'Tinggi' : value === 'medium' ? 'Sedang' : 'Rendah';
    document.getElementById('priority-icon').style.color = value === 'high' ? 'red' : value === 'medium' ? 'yellow' : 'green';    
    document.getElementById('priority-value').value = value;
    document.getElementById('priority-modal').classList.add('hidden');
  });
});



document.getElementById('recurrence-btn').addEventListener('click', () => {
  document.getElementById('recurrence-modal').classList.remove('hidden');
});

//  Pilihan recurrence (none, daily, weekly, monthly)
document.querySelectorAll('.recurrence-option').forEach(btn => {
  btn.addEventListener('click', () => {
    const value = btn.dataset.value;
    const textEl = document.getElementById('recurrence-text');
    const modal = document.getElementById('recurrence-modal');
    const button = document.getElementById('recurrence-btn');
    const hiddenInput = document.getElementById('recurrence-value'); // <— tambahkan ini
    let label = "";
    let bgClass = "";

    // hapus warna lama button
    button.classList.remove(
      "bg-gray-100",
      "bg-blue-100",
      "bg-yellow-100",
      "bg-green-100",
      "hover:bg-gray-200",
      "hover:bg-blue-200",
      "hover:bg-yellow-200",
      "hover:bg-green-200"
    );

    switch (value) {
      case "none":
        label = "Tidak Ada";
        bgClass = "bg-gray-100 hover:bg-gray-200";
        break;
      case "daily":
        label = "Harian";
        bgClass = "bg-blue-100 hover:bg-blue-200";
        break;
      case "weekly":
        label = "Mingguan";
        bgClass = "bg-yellow-100 hover:bg-yellow-200";
        break;
      case "monthly":
        label = "Bulanan";
        bgClass = "bg-green-100 hover:bg-green-200";
        break;
    }

    textEl.textContent = label;
    button.classList.add(...bgClass.split(" "));
    hiddenInput.value = value; // <— simpan pilihan ke hidden input
    modal.classList.add('hidden');
  });
});
</script>
<?= $this->endSection() ?>
