import "./activity/selectionMode.js";
import "./activity/subTaskModal.js";
import "./activity/subTaskAction.js";
import "./activity/utils.js";
document.addEventListener("DOMContentLoaded", () => {
  // Klik ikon action untuk toggle menu
  document.querySelectorAll(".action-icon").forEach((icon) => {
    icon.addEventListener("click", (e) => {
      e.stopPropagation(); // mencegah event bubbling
      const menu = icon.querySelector(".mini-action-menu");

      // Tutup semua menu lain dulu
      document.querySelectorAll(".mini-action-menu").forEach((m) => {
        if (m !== menu) m.classList.add("hidden");
      });

      // Toggle menu aktif
      menu.classList.toggle("hidden");
    });
  });

  // Klik di luar area menu → tutup semua
  document.addEventListener("click", () => {
    document.querySelectorAll(".mini-action-menu").forEach((menu) => {
      menu.classList.add("hidden");
    });
  });
});
document.addEventListener("DOMContentLoaded", () => {
  // === FILTER TAB ===
  const tabs = document.querySelectorAll(".tab");
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Hapus kelas 'active' dari semua tab
      tabs.forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");

      // Ambil filter value dari data-attribute atau innerText
      const filter = tab.textContent.trim().toLowerCase();
      filterTasks(filter);
    });
  });

  // === SORT DROPDOWN ===
  const sortSelect = document.getElementById("sortSelect");
  if (sortSelect) {
    sortSelect.addEventListener("change", () => {
      const sortValue = sortSelect.value;
      sortTasks(sortValue);
    });
  }
});

// === Dummy function untuk demo (ganti dengan logika kamu) ===
function filterTasks(filter) {
  console.log("Filtering by:", filter);
  // Implementasikan logika filter sesuai kebutuhan kamu
}

function sortTasks(criteria) {
  console.log("Sorting by:", criteria);
  // Implementasikan logika sorting sesuai kebutuhan kamu
}
