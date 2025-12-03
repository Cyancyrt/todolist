<?= $this->extend('dashboard/template/layout') ?>
<?= $this->section('content') ?>
<main class="flex-1 min-h-screen flex flex-col items-center justify-center p-6">
  <!-- Flash Message Toast -->
<div class="fixed top-5 right-5 z-50 space-y-3">
  <?php if (session()->getFlashdata('errors')): ?>
    <div class="animate-slide toast bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg flex items-start gap-3">
      <div><?= implode('<br>', session()->getFlashdata('errors')) ?></div>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="animate-slide toast bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg flex items-start gap-3">
      <div><?= session()->getFlashdata('error') ?></div>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="animate-slide toast bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg flex items-start gap-3">
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>
</div>

  <div class="relative w-full">
    <button id="back-btn" type="button"
      class="absolute top-0 start-0 mt-4 mr-4 w-8 h-8 sm:w-10 sm:h-10 bg-white hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-blue-500 transition-all duration-200 hover:scale-110"
      title="Kembali ke Daftar Task">
      <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </button>
  </div>
  <form id="task-form" action="<?= base_url('dashboard/task/update/' . esc($task['id'])) ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT"> <!-- Spoofing method -->
    <input type="hidden" name="activity_id" value="<?= esc($ActivityId) ?>">
    <div id="paper-container" class="w-full max-w-6xl grid grid-flow-row place-items-center gap-6 transition-all">
      <div class="paper p-6 relative bg-white shadow-lg rounded-2xl transition-all duration-300 transform scale-100">
        <button id="clear-btn" type="button"
          class="absolute top-3 right-3 w-8 h-8 bg-transparent hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 transition-all duration-200 hover:scale-110"
          title="Hapus semua catatan di editor">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
        </button>
        <input type="text" name="titles[]" placeholder="Judul Task" value="<?= old('titles.0', esc($task['title'] ?? '')) ?>"  class="w-full text-2xl font-semibold border-none outline-none bg-transparent mb-4 placeholder-gray-400" />
        <div id="editorjs" class="editor-container mb-6"></div>

        <div class="flex flex-col sm:flex-row gap-4 mb-6 flex-wrap items-center justify-start">
          <span>Prioritas :</span>
      <!-- Prioritas -->
      <button type="button" id="priority-btn" class="flex items-center gap-2 p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-200 transform hover:scale-105 min-w-0 flex-1 sm:flex-none">
        <svg id="priority-icon" class="w-6 h-6 flex-shrink-0 leading-none translate-y-[1px]" fill="currentColor" viewBox="0 0 24 24"><path d="M4 21V3h16l-2 5 2 5H4z"/></svg>
        <input type="hidden" id="priority-value" name="priority" value="low">
        <span id="priority-text" class="text-gray-700 text-sm sm:text-base leading-none">Prioritas</span>
      </button>
    </div>      
        <!-- Modal untuk Prioritas -->
        <div id="priority-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full relative">
            <button id="close-priority-modal" type="button" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 transition-colors duration-150" title="Tutup">
              &#x2716;
            </button>
            <h3 class="text-lg font-semibold mb-4 text-center">Pilih Prioritas</h3>
            <div class="flex gap-4 justify-center">
              <button type="button" class="priority-option flex flex-col items-center p-4 rounded-lg bg-red-100 hover:bg-red-200" data-value="high">
                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 21V3h16l-2 5 2 5H4z"/>
                </svg>
                <span class="text-sm mt-2">Tinggi</span>
              </button>
              <button type="button" class="priority-option flex flex-col items-center p-4 rounded-lg bg-yellow-100 hover:bg-yellow-200" data-value="medium">
                <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 21V3h16l-2 5 2 5H4z"/>
                </svg>
                <span class="text-sm mt-2">Sedang</span>
              </button>
              <button type="button" class="priority-option flex flex-col items-center p-4 rounded-lg bg-green-100 hover:bg-green-200" data-value="low">
                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 21V3h16l-2 5 2 5H4z"/>
                </svg>
                <span class="text-sm mt-2">Rendah</span>
              </button>
            </div>
          </div>
        </div>
        <!-- Hidden input untuk form -->
        <input type="hidden" name="contents[]" data-original='<?= esc($task['description'] ?? '{}', 'attr') ?>' class="editor-content-input">
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
  document.getElementById('close-priority-modal').addEventListener('click', () => {
    document.getElementById('priority-modal').classList.add('hidden');
  });

  function initEditor(holderId, data = null) {
    const editor = new EditorJS({
      holder: holderId,
      tools: {
        header: Header,
        list: EditorjsList,
        paragraph: Paragraph,
        checklist: { class: Checklist, inlineToolbar: true }
      },
      placeholder: "Tulis catatan di sini...",
      data: data ? JSON.parse(data) : {
            time: Date.now(),
            blocks: [
                { type: "checklist", data: { items: [{ text: "aktivitas 1", checked: true }] } }
            ]
        }
    });
    editors.push(editor);
  }

 const existingDesc = <?= json_encode($task['description'] ?? null) ?>;
initEditor('editorjs', existingDesc);

</script>
<script>
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
    window.location.href = "<?= base_url('dashboard/activity') ?>";
  });

  // 📨 Saat form disubmit
document.getElementById('create-btn').addEventListener('click', async (e) => {
  e.preventDefault();
  const form = document.getElementById('task-form');
  const contentInputs = document.querySelectorAll('.editor-content-input');

  for (let i = 0; i < editors.length; i++) {
    try {
      const output = await editors[i].save();
      if (output && output.blocks && output.blocks.length > 0) {
        contentInputs[i].value = JSON.stringify(output);
      } else {
        // fallback ke value lama kalau tidak ada perubahan
        const existingValue = contentInputs[i].getAttribute("data-original") || "{}";
        contentInputs[i].value = existingValue;
      }
    } catch (err) {
      console.warn(`Editor ${i} gagal disimpan, pakai data lama`);
      const existingValue = contentInputs[i].getAttribute("data-original") || "{}";
      contentInputs[i].value = existingValue;
    }
  }

  form.submit();
});
</script>
<script>

// Modal Prioritas
document.getElementById('priority-btn').addEventListener('click', () => {
  document.getElementById('priority-modal').classList.remove('hidden');
});

document.getElementById('close-priority-modal').addEventListener('click', () => {
  document.getElementById('priority-modal').classList.add('hidden');
});

// Default state saat pertama kali halaman dimuat
window.addEventListener('DOMContentLoaded', () => {
  const currentPriority = "<?= esc($task['priority'] ?? 'low') ?>";
  const text = currentPriority === 'high' ? 'Tinggi' : currentPriority === 'medium' ? 'Sedang' : 'Rendah';
  const color = currentPriority === 'high' ? 'red' : currentPriority === 'medium' ? 'gold' : 'green';

  document.getElementById('priority-value').value = currentPriority;
  document.getElementById('priority-text').textContent = text;
  document.getElementById('priority-icon').style.color = color;
});

// Saat user memilih salah satu prioritas
document.querySelectorAll('.priority-option').forEach(btn => {
  btn.addEventListener('click', () => {
    const value = btn.dataset.value;
    const text = value === 'high' ? 'Tinggi' : value === 'medium' ? 'Sedang' : 'Rendah';
    const color = value === 'high' ? 'red' : value === 'medium' ? 'gold' : 'green';
    
    document.getElementById('priority-text').textContent = text;
    document.getElementById('priority-icon').style.color = color;
    document.getElementById('priority-value').value = value;
    document.getElementById('priority-modal').classList.add('hidden');
  });
});

</script>
<?= $this->endSection() ?>
