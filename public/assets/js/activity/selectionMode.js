document.addEventListener("DOMContentLoaded", () => {
  const selectModeBtn = document.getElementById("selectModeBtn");
  const selectAllBtn = document.getElementById("selectAllBtn");
  const cancelSelectBtn = document.getElementById("cancelSelectBtn");
  const deleteSelectedBtn = document.getElementById("deleteSelectedBtn");
  const createBtn = document.getElementById("createBtn");
  const viewAllBtn = document.getElementById("viewAllBtn");
  const itemActions = document.querySelectorAll(".item-actions");

  const checkboxes = () => [...document.querySelectorAll(".task-checkbox")];
  let isSelectMode = false;
  let TaskSelectedIds = [];

  /** ---------------- TOAST ---------------- */
  function showToast(message, type = "info", duration = 3000) {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    const colors = {
      success: "bg-green-500 text-white",
      error: "bg-red-500 text-white",
      info: "bg-blue-500 text-white",
      warning: "bg-yellow-400 text-black",
    };

    const toast = document.createElement("div");
    toast.className = `flex items-center justify-between p-4 rounded shadow-md ${colors[type]} opacity-0 transform translate-y-2 transition-all duration-300`;
    toast.innerHTML = `<span>${message}</span><button class="ml-4 font-bold" onclick="this.parentElement.remove()">Ã—</button>`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.classList.add("opacity-100", "translate-y-0");
      toast.classList.remove("translate-y-2");
    }, 10);

    setTimeout(() => {
      toast.classList.remove("opacity-100");
      toast.classList.add("opacity-0", "translate-y-2");
      setTimeout(() => toast.remove(), 300);
    }, duration);
  }

  /** ---------------- Mode Toggle ---------------- */
  selectModeBtn.addEventListener("click", () => {
    isSelectMode = true;
    toggleSelectUI(true);
  });

  cancelSelectBtn.addEventListener("click", () => {
    isSelectMode = false;
    resetSelection();
    toggleSelectUI(false);
  });

  /** ---------------- Select All ---------------- */
  selectAllBtn.addEventListener("click", () => {
    const shouldCheck = !checkboxes().every((cb) => cb.checked);
    checkboxes().forEach((cb) => (cb.checked = shouldCheck));
    updateDeleteState();
  });

  /** ---------------- Click Item Selection ---------------- */
  /** ---------------- Click Item Selection ---------------- */
  document.addEventListener("click", (e) => {
    // Pastikan sedang dalam mode pilih
    if (!isSelectMode) return;

    // Cari elemen pembungkus (row)
    const item = e.target.closest(".task-item");
    if (!item) return;

    // 1. Hindari klik tombol aksi (Edit, Delete, dll)
    if (
      e.target.closest(".action-icon") ||
      e.target.closest("button") || // Gunakan closest agar lebih aman untuk SVG didalam button
      e.target.tagName === "A" // Hindari link
    ) {
      return;
    }

    const checkbox = item.querySelector(".task-checkbox");

    // 2. PERBAIKAN BUG: Cek apakah yang diklik adalah checkbox itu sendiri?
    if (e.target === checkbox) {
      // Jika ya, biarkan browser melakukan checking/unchecking secara alami.
      // Kita hanya perlu update status tombol delete.
      updateDeleteState();
      return;
    }

    // 3. Jika yang diklik adalah area baris (bukan checkbox), lakukan manual toggle
    checkbox.checked = !checkbox.checked;
    updateDeleteState();
  });

  /** ---------------- Bulk Delete Modal ---------------- */
  const bulkDeleteModal = document.getElementById("bulkDeleteModal");
  const bulkModalContent = document.getElementById("bulkModalContent");
  const bulkDeleteMessage = document.getElementById("bulkDeleteMessage");
  const cancelBulkDelete = document.getElementById("cancelBulkDelete");
  const confirmBulkDelete = document.getElementById("confirmBulkDelete");

  deleteSelectedBtn.addEventListener("click", () => {
    TaskSelectedIds = checkboxes()
      .filter((cb) => cb.checked)
      .map((cb) => cb.closest(".task-item").dataset.id);
    if (!TaskSelectedIds.length) {
      showToast("Tidak ada aktivitas yang dipilih.", "error");
      return;
    }

    bulkDeleteMessage.textContent = `Apakah Anda yakin ingin menghapus ${TaskSelectedIds.length} aktivitas terpilih?`;
    bulkDeleteModal.classList.remove("hidden");
    setTimeout(() => {
      bulkModalContent.classList.remove("opacity-0", "scale-95");
      bulkModalContent.classList.add("opacity-100", "scale-100");
    }, 10);
  });

  cancelBulkDelete.addEventListener("click", () => {
    bulkModalContent.classList.add("opacity-0", "scale-95");
    setTimeout(() => bulkDeleteModal.classList.add("hidden"), 200);
  });

  confirmBulkDelete.addEventListener("click", async () => {
    confirmBulkDelete.disabled = true;
    confirmBulkDelete.textContent = "Deleting...";

    try {
      const res = await fetch(BULK_DELETE_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ids: TaskSelectedIds }),
      });
      const result = await res.json();
      if (result.status === "success") {
        showToast(result.message ?? "Aktivitas berhasil dihapus.", "success");
        setTimeout(() => {
          window.location.reload();
        }, 300);
        resetSelection();
        toggleSelectUI(false);
      } else {
        showToast(result.message ?? "Gagal menghapus.", "error");
      }
    } catch (err) {
      console.error(err);
      showToast("Terjadi error saat menghapus.", "error");
    }

    confirmBulkDelete.disabled = false;
    confirmBulkDelete.textContent = "Delete";
    bulkModalContent.classList.add("opacity-0", "scale-95");
    setTimeout(() => bulkDeleteModal.classList.add("hidden"), 200);
  });

  /** ---------------- Helper Functions ---------------- */
  function toggleSelectUI(active) {
    checkboxes().forEach((cb) => {
      cb.classList.toggle("visible-checkbox", active);
      cb.classList.toggle("invisible-checkbox", !active);
    });

    selectAllBtn.classList.toggle("hidden", !active);
    cancelSelectBtn.classList.toggle("hidden", !active);
    deleteSelectedBtn.classList.toggle("hidden", !active);
    createBtn.classList.toggle("hidden", active);
    viewAllBtn.classList.toggle("hidden", active);
    itemActions.forEach((el) => el.classList.toggle("hidden", active));
  }

  function resetSelection() {
    checkboxes().forEach((cb) => (cb.checked = false));
    deleteSelectedBtn.disabled = true;
  }

  function updateDeleteState() {
    deleteSelectedBtn.disabled = !checkboxes().some((cb) => cb.checked);
  }
});
