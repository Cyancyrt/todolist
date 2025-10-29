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
document.addEventListener("DOMContentLoaded", () => {
  const tabsContainer = document.querySelector("#activityTabs");
  if (!tabsContainer) return;

  const tabs = tabsContainer.querySelectorAll(".activity-tab");
  const activityItems = document.querySelectorAll(".activity-item");

  const defaultType = tabsContainer.dataset.defaultType || "personal";

  function filterActivities(type) {
    activityItems.forEach((item) => {
      const itemType = item.getAttribute("data-type");
      if (itemType === type || type === "all") {
        item.classList.remove("hidden");
      } else {
        item.classList.add("hidden");
      }
    });
  }

  const activeTab = tabsContainer.querySelector(
    `.activity-tab[data-type="${defaultType}"]`
  );
  if (activeTab) {
    activeTab.classList.add("active");
    activeTab.setAttribute("aria-selected", "true");
    filterActivities(defaultType);
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => {
        t.classList.remove("active");
        t.setAttribute("aria-selected", "false");
      });

      this.classList.add("active");
      this.setAttribute("aria-selected", "true");

      const type = this.getAttribute("data-type");
      filterActivities(type);
    });
  });
});
