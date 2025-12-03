function toggleSubTasks(btn) {
  const subTasks = btn.closest(".task-item").querySelector(".sub-tasks");
  const icon = btn.querySelector("svg");
  if (subTasks.classList.contains("hidden")) {
    subTasks.classList.remove("hidden");
    subTasks.style.maxHeight = subTasks.scrollHeight + "px";
    icon.style.transform = "rotate(180deg)";
  } else {
    subTasks.style.maxHeight = "0px";
    setTimeout(() => subTasks.classList.add("hidden"), 300);
    icon.style.transform = "rotate(0deg)";
  }
}
/**
 * Utility: Mengupdate URL Parameter dan Reload Halaman
 * Digunakan untuk sorting dan time filter
 */
const updateQueryParam = (key, value) => {
  const url = new URL(window.location.href);
  url.searchParams.set(key, value);
  // Opsional: Reset pagination jika filter berubah
  // url.searchParams.delete('page');
  window.location.href = url.toString();
};

/**
 * Fitur 2: Activity Tabs (Client-Side Filtering)
 * Filter elemen yang sudah ada di DOM tanpa reload
 */
const initActivityTabs = () => {
  const tabsContainer = document.querySelector("#activityTabs");
  if (!tabsContainer) return;

  const tabs = tabsContainer.querySelectorAll(".activity-tab");
  const items = document.querySelectorAll(".activity-item");
  const defaultType = tabsContainer.dataset.defaultType || "personal";

  // Helper untuk update UI list item
  const renderList = (type) => {
    items.forEach((item) => {
      const itemType = item.getAttribute("data-type");
      const shouldShow = type === "all" || itemType === type;
      item.classList.toggle("hidden", !shouldShow);
    });
  };

  // Helper untuk update state tombol tab
  const setActiveTab = (selectedTab) => {
    tabs.forEach((t) => {
      const isActive = t === selectedTab;
      t.classList.toggle("active", isActive);
      t.setAttribute("aria-selected", isActive);
    });
  };

  // Set initial state
  const initialTab = tabsContainer.querySelector(
    `.activity-tab[data-type="${defaultType}"]`
  );
  if (initialTab) {
    setActiveTab(initialTab);
    renderList(defaultType);
  }

  // Event Listeners
  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      setActiveTab(this);
      renderList(this.getAttribute("data-type"));
    });
  });
};

/**
 * Fitur 3: Server Filters (Time & Sort)
 * Update URL Query Params
 */
const initServerFilters = () => {
  // Time Filters (Buttons)
  const filterButtons = document.querySelectorAll(
    "#timeFilters button, #timeFilters .tab"
  );
  filterButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Pastikan ada atribut data-filter di HTML
      const filterValue = this.getAttribute("data-filter");
      if (filterValue) updateQueryParam("filter", filterValue);
    });
  });

  // Sort Dropdown
  const sortSelect = document.getElementById("sortSelect");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      updateQueryParam("sort", this.value);
    });
  }
};

// --- Main Initialization ---
document.addEventListener("DOMContentLoaded", () => {
  initActivityTabs();
  initServerFilters();
});
