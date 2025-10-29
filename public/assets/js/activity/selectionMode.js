document.addEventListener("DOMContentLoaded", () => {
  const selectModeBtn = document.getElementById("selectModeBtn");
  const selectAllBtn = document.getElementById("selectAllBtn");
  const cancelSelectBtn = document.getElementById("cancelSelectBtn");
  const checkboxes = document.querySelectorAll(".task-checkbox");
  const itemActions = document.querySelectorAll(".item-actions");
  const createBtn = document.getElementById("createBtn");
  const viewAllBtn = document.getElementById("viewAllBtn");
  const deleteSelectedBtn = document.getElementById("deleteSelectedBtn");
  let isSelectMode = false;

  // Toggle mode select
  selectModeBtn.addEventListener("click", () => {
    isSelectMode = !isSelectMode;
    checkboxes.forEach((cb) => {
      cb.classList.toggle("visible-checkbox", isSelectMode);
      cb.classList.toggle("invisible-checkbox", !isSelectMode);
    });

    selectAllBtn.classList.toggle("hidden", !isSelectMode);
    cancelSelectBtn.classList.toggle("hidden", !isSelectMode);
    createBtn.classList.toggle("hidden", isSelectMode);
    viewAllBtn.classList.toggle("hidden", isSelectMode);
    deleteSelectedBtn.classList.toggle("hidden", !isSelectMode);
    itemActions.forEach((action) =>
      action.classList.toggle("hidden", isSelectMode)
    );
  });

  // Batalkan mode select
  cancelSelectBtn.addEventListener("click", () => {
    isSelectMode = false;
    checkboxes.forEach((cb) => {
      cb.checked = false;
      cb.classList.add("invisible-checkbox");
      cb.classList.remove("visible-checkbox");
    });
    selectAllBtn.classList.add("hidden");
    cancelSelectBtn.classList.add("hidden");
    deleteSelectedBtn.classList.add("hidden");
    createBtn.classList.remove("hidden");
    viewAllBtn.classList.remove("hidden");
    itemActions.forEach((action) => action.classList.remove("hidden"));
  });

  // Pilih semua
  selectAllBtn.addEventListener("click", () => {
    const allChecked = [...checkboxes].every((cb) => cb.checked);
    checkboxes.forEach((cb) => (cb.checked = !allChecked));
  });

  // Hindari toggle ganda saat klik checkbox
  checkboxes.forEach((cb) =>
    cb.addEventListener("click", (e) => e.stopPropagation())
  );

  // Toggle checkbox saat klik task-item
  document.querySelectorAll(".task-item").forEach((item) => {
    item.addEventListener("click", (e) => {
      if (!isSelectMode) return;
      if (e.target.closest(".action-icon") || e.target.closest("svg")) return;
      const checkbox = item.querySelector(".task-checkbox");
      checkbox.checked = !checkbox.checked;
    });
  });

  // Hapus batch task
  deleteSelectedBtn.addEventListener("click", () => {
    const selectedTasks = [...checkboxes].filter((cb) => cb.checked);
    if (selectedTasks.length === 0) {
      alert("Pilih minimal satu aktivitas untuk dihapus!");
      return;
    }

    if (!confirm(`Hapus ${selectedTasks.length} aktivitas terpilih?`)) return;

    selectedTasks.forEach((cb) => cb.closest(".task-item").remove());

    // Reset mode
    isSelectMode = false;
    checkboxes.forEach((cb) => {
      cb.checked = false;
      cb.classList.add("hidden");
    });
    selectAllBtn.classList.add("hidden");
    cancelSelectBtn.classList.add("hidden");
    deleteSelectedBtn.classList.add("hidden");
    createBtn.classList.remove("hidden");
    viewAllBtn.classList.remove("hidden");
  });
});
