function editSubTask() {
  const taskName = document.getElementById("subName").textContent;
  window.location.href = `<?= base_url('dashboard/task/edit') ?>?name=${encodeURIComponent(
    taskName
  )}`;
}

document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("editSubTaskBtn")
    ?.addEventListener("click", editSubTask);

  document.querySelectorAll(".sub-task").forEach((task) => {
    task.addEventListener("click", (e) => {
      const detail = task.querySelector(".sub-detail");
      if (!detail) return;

      document.querySelectorAll(".sub-detail").forEach((d) => {
        if (d !== detail) d.classList.add("hidden");
      });

      detail.classList.toggle("hidden");
    });
  });

  document.querySelectorAll(".open-sub-modal").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const modal = document.getElementById("subActivityModal");
      modal.classList.remove("hidden");

      // Dummy data (bisa diganti AJAX)
      document.getElementById("subName").textContent =
        "Kumpulkan sampah organik";
      document.getElementById("subDesc").textContent =
        "Pisahkan sampah organik dari dapur dan taman.";
      document.getElementById("subStart").textContent = "07:30 AM";
      document.getElementById("subDeadline").textContent = "07:45 AM";
      document.getElementById("subStatus").textContent = "Pending";
      document.getElementById("subProgress").textContent = "40%";
    });
  });
});
