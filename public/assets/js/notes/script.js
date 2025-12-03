document.addEventListener("DOMContentLoaded", () => {
  /* ================================
     TOAST FUNCTION (Boleh tetap sama)
     =================================*/
  function showToast(message, type = "info", duration = 3000) {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    // ... (kode toast sama seperti sebelumnya) ...
    // Jika perlu kode lengkap toast, gunakan yang lama.
    // Di sini disingkat agar fokus ke logika inti.
    const colors = {
      success: "bg-green-500 text-white",
      error: "bg-red-500 text-white",
    };
    const toast = document.createElement("div");
    toast.className = `flex items-center justify-between p-4 rounded shadow-md ${
      colors[type] || "bg-blue-500"
    } mb-2`;
    toast.innerHTML = `<span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), duration);
  }
  /* ================================
     SINGLE DELETE CONFIRMATION
     =================================*/
  const singleDeleteForms = document.querySelectorAll(".deleteFormNotes");
  const modalSingleDelete = document.getElementById("modalDeleteSingleNote");
  const btnCancelSingle = document.getElementById("btnCancelDeleteSingleNote");
  const btnConfirmSingle = document.getElementById(
    "btnConfirmDeleteSingleNote"
  );

  // Variabel untuk menyimpan form mana yang sedang ingin dihapus
  let formToDelete = null;

  // 1. Loop semua form delete dan cegah submit langsung
  singleDeleteForms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault(); // Stop proses submit
      formToDelete = form; // Simpan form ini ke variabel

      // Tampilkan Modal
      modalSingleDelete.classList.remove("hidden");
      const modalContent = modalSingleDelete.querySelector(".modal-content");
      if (modalContent) {
        setTimeout(() => {
          modalContent.classList.remove("opacity-0", "scale-95");
          modalContent.classList.add("opacity-100", "scale-100");
        }, 10);
      }
    });
  });

  // 2. Jika tombol Cancel di modal diklik
  if (btnCancelSingle) {
    btnCancelSingle.addEventListener("click", () => {
      closeSingleModal();
      formToDelete = null; // Reset
    });
  }

  // 3. Jika tombol Delete (Konfirmasi) di modal diklik
  if (btnConfirmSingle) {
    btnConfirmSingle.addEventListener("click", () => {
      if (formToDelete) {
        // Submit form yang tadi disimpan secara manual
        formToDelete.submit();
      }
    });
  }

  // Helper: Tutup modal
  function closeSingleModal() {
    const modalContent = modalSingleDelete.querySelector(".modal-content");
    if (modalContent) {
      modalContent.classList.add("opacity-0", "scale-95");
      modalContent.classList.remove("opacity-100", "scale-100");
    }
    setTimeout(() => {
      modalSingleDelete.classList.add("hidden");
    }, 200);
  }

  // Opsional: Tutup modal jika klik di luar area (backdrop)
  window.addEventListener("click", (e) => {
    if (e.target === modalSingleDelete) {
      closeSingleModal();
    }
  });
  /* ================================
     SELECT MODE + BULK DELETE (NOTES)
     =================================*/

  // GANTI ID DI SINI AGAR UNIK (TIDAK BENTROK DENGAN TASKS)
  const selectModeBtn = document.getElementById("btnSelectModeNotes");
  const selectAllBtn = document.getElementById("btnSelectAllNotes");
  const cancelSelectBtn = document.getElementById("btnCancelSelectNotes");
  const deleteSelectedBtn = document.getElementById("btnDeleteSelectedNotes");

  // Class selector aman tetap sama, karena class note-item beda dengan task-item
  const checkboxes = document.querySelectorAll(".note-checkbox");
  const noteItems = document.querySelectorAll(".note-item");

  // Bulk Delete Modal Elements (ID UNIK)
  const bulkDeleteModal = document.getElementById("modalBulkDeleteNotes");
  // Pastikan ID konten di dalam modal juga unik atau gunakan class
  const bulkModalContent = bulkDeleteModal
    ? bulkDeleteModal.querySelector(".modal-content")
    : null;
  const bulkDeleteMessage = document.getElementById("msgBulkDeleteNotes");
  const cancelBulkDelete = document.getElementById("btnCancelModalNotes");
  const confirmBulkDelete = document.getElementById("btnConfirmDeleteNotes");

  let selectMode = false;
  let selectedIds = [];

  // Safety check: jika elemen tidak ditemukan di halaman ini, hentikan script
  if (!selectModeBtn || !deleteSelectedBtn) return;

  // --- Event Listeners ---

  selectModeBtn.addEventListener("click", () => {
    selectMode = true;
    toggleSelectMode(true);
    deleteSelectedBtn.classList.remove("hidden");
  });

  cancelSelectBtn.addEventListener("click", () => {
    selectMode = false;
    toggleSelectMode(false);
    resetChecks();
    deleteSelectedBtn.classList.add("hidden");
  });

  selectAllBtn.addEventListener("click", () => {
    const allChecked = [...checkboxes].every((cb) => cb.checked);
    checkboxes.forEach((cb) => (cb.checked = !allChecked));
    updateDeleteButton();
  });

  checkboxes.forEach((cb) => cb.addEventListener("change", updateDeleteButton));

  noteItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      if (!selectMode) return;

      // 1. Mencegah trigger jika klik tombol lain di dalam note (Edit/Delete buttons)
      if (
        e.target.tagName === "BUTTON" ||
        e.target.closest("button") ||
        e.target.tagName === "A" ||
        e.target.closest("a")
      ) {
        return;
      }

      // Pastikan class .note-checkbox benar ada di dalam item
      const checkbox = item.querySelector(".note-checkbox");
      if (!checkbox) return;

      // 2. PERBAIKAN UTAMA: Cek apakah yang diklik adalah checkbox itu sendiri?
      if (e.target === checkbox) {
        // Jika ya, biarkan browser bekerja secara native.
        // Kita HANYA perlu update status tombol delete.
        updateDeleteButton();
        return;
      }

      // 3. Jika yang diklik adalah area kartu (bukan checkbox/tombol),
      // barulah kita lakukan toggle manual.
      e.preventDefault(); // Mencegah highlight teks/perilaku default div
      checkbox.checked = !checkbox.checked;
      updateDeleteButton();
    });
  });

  // --- Modal Logic ---

  deleteSelectedBtn.addEventListener("click", () => {
    // Pastikan kita hanya mengambil checkbox note, bukan task
    selectedIds = [...checkboxes]
      .filter((cb) => cb.checked)
      .map((cb) => cb.closest(".note-item").dataset.id);

    if (selectedIds.length === 0) {
      showToast("Tidak ada catatan yang dipilih.", "error");
      return;
    }

    if (bulkDeleteMessage)
      bulkDeleteMessage.textContent = `Hapus ${selectedIds.length} catatan terpilih?`;

    bulkDeleteModal.classList.remove("hidden");
    if (bulkModalContent) {
      setTimeout(() => {
        bulkModalContent.classList.remove("opacity-0", "scale-95");
        bulkModalContent.classList.add("opacity-100", "scale-100");
      }, 10);
    }
  });

  if (cancelBulkDelete) {
    cancelBulkDelete.addEventListener("click", closeModal);
  }

  function closeModal() {
    if (bulkModalContent) {
      bulkModalContent.classList.add("opacity-0", "scale-95");
    }
    setTimeout(() => bulkDeleteModal.classList.add("hidden"), 200);
  }

  // --- Confirm Delete Action ---

  if (confirmBulkDelete) {
    confirmBulkDelete.addEventListener("click", async () => {
      confirmBulkDelete.disabled = true;
      confirmBulkDelete.textContent = "Deleting...";

      try {
        // Pastikan URL API benar
        const targetUrl =
          typeof base_url !== "undefined"
            ? `${base_url}/dashboard/notes/bulk-delete`
            : "/dashboard/notes/bulk-delete";

        const res = await fetch(targetUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify({ ids: selectedIds }),
        });

        // Cek HTTP Status dulu
        if (!res.ok) throw new Error(`Server error: ${res.status}`);

        const result = await res.json();

        if (result.status === "success") {
          showToast(result.message ?? "Catatan berhasil dihapus.", "success");

          // Reload halaman setelah jeda singkat
          setTimeout(() => {
            window.location.reload();
          }, 500);
        } else {
          throw new Error(result.message ?? "Gagal menghapus catatan.");
        }
      } catch (err) {
        console.error(err);
        showToast(err.message || "Terjadi error saat menghapus.", "error");

        // Reset tombol HANYA jika terjadi error (karena jika sukses, halaman reload)
        confirmBulkDelete.disabled = false;
        confirmBulkDelete.textContent = "Delete";
        closeModal();
      }
      // Tidak perlu finally untuk reset tombol di sini karena jika sukses kita reload.
      // Jika gagal, blok catch sudah menangani reset.
    });
  }

  // --- Helpers ---

  function toggleSelectMode(active) {
    // 1. Tampilkan/Sembunyikan Checkbox (Logika Lama)
    checkboxes.forEach((cb) => {
      cb.classList.toggle("checkbox-visible", active);
      cb.classList.toggle("invisible-checkbox", !active);
    });

    // 2. Tampilkan/Sembunyikan Tombol Action (INI YANG HILANG SEBELUMNYA)
    // Pastikan variabel tombol sudah didefinisikan di bagian atas script

    // Tombol "Pilih" (Sembunyikan jika active)
    if (selectModeBtn) selectModeBtn.classList.toggle("hidden", active);

    // Tombol "Pilih Semua" (Tampilkan jika active)
    if (selectAllBtn) selectAllBtn.classList.toggle("hidden", !active);

    // Tombol "Batal" (Tampilkan jika active)
    if (cancelSelectBtn) cancelSelectBtn.classList.toggle("hidden", !active);

    // Tombol "Delete Bulk" (Tampilkan jika active)
    if (deleteSelectedBtn)
      deleteSelectedBtn.classList.toggle("hidden", !active);

    // OPSIONAL: Sembunyikan tombol Create & View All supaya tidak penuh
    const createBtn = document.getElementById("btnCreateNote");
    const viewAllBtn = document.getElementById("btnViewAllNotes");
    if (createBtn) createBtn.classList.toggle("hidden", active);
    if (viewAllBtn) viewAllBtn.classList.toggle("hidden", active);
  }

  function resetChecks() {
    checkboxes.forEach((cb) => (cb.checked = false));
    updateDeleteButton();
  }

  function updateDeleteButton() {
    const anyChecked = [...checkboxes].some((cb) => cb.checked);
    deleteSelectedBtn.disabled = !anyChecked;
  }
});
