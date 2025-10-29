// Toggle tampilkan / sembunyikan sub-task list
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
  // Pasang event listener ke semua tombol toggle subtask
  document.querySelectorAll(".toggle-subtask-btn").forEach((btn) => {
    btn.addEventListener("click", () => toggleSubTasks(btn));
  });
});
document.addEventListener("DOMContentLoaded", () => {
  // Delegated event listener biar bisa tangkap elemen baru juga
  document.addEventListener("click", (e) => {
    const subTask = e.target.closest(".sub-task");
    if (!subTask) return;

    const name = subTask.dataset.name;
    const desc = JSON.parse(subTask.dataset.desc);
    const deadline = subTask.dataset.deadline;
    const status = subTask.dataset.status;

    openSubModal(subTask, name, desc, deadline, status);
  });
});
// Buka modal dengan data
function openSubModal(taskElement, name, desc, deadline, status) {
  const modal = document.getElementById("subActivityModal");
  modal.classList.remove("hidden");
  modal.querySelector("div").classList.replace("scale-95", "scale-100");
  document.getElementById("subName").textContent = name;
  document.getElementById("subDeadline").textContent = deadline;
  document.getElementById("subStatus").textContent = status;

  const statusSpan = document.getElementById("subStatus");
  statusSpan.className =
    "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium";
  if (status === "Completed")
    statusSpan.classList.add("bg-green-100", "text-green-800");
  else if (status === "In Progress")
    statusSpan.classList.add("bg-blue-100", "text-blue-800");
  else statusSpan.classList.add("bg-yellow-100", "text-yellow-800");

  // Reset div sebelum inisialisasi editor baru
  const holder = document.getElementById("editorjs-checklist");
  holder.innerHTML = "";

  // 🧩 Simpan ke variabel
  const editor = new EditorJS({
    holder: "editorjs-checklist",
    tools: {
      checklist: { class: Checklist, inlineToolbar: true },
    },
    defaultBlock: "checklist",
    data: desc,
    onChange: async () => {
      const data = await editor.save();
      if (data.blocks.length > 1) {
        const first = data.blocks[0];
        editor.render({ blocks: [first] });
      }
    },
    onReady: () => {
      // Allow checkbox toggling, tapi cegah edit teks
      const checkboxes = holder.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach((c) => (c.disabled = false));

      // Prevent Enter key (tidak bisa buat line baru)
      holder.addEventListener("keydown", (e) => {
        if (e.key === "Enter") e.preventDefault();
      });
    },
  });

  modal.dataset.taskElement = taskElement;
}

// Update status task
function updateTaskStatus(taskElement, newStatus) {
  const badge = taskElement.querySelector(".status-badge");
  badge.textContent = newStatus;
  badge.className =
    "status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium";

  if (newStatus === "Completed") {
    badge.classList.add("bg-green-100", "text-green-800");
    badge.innerHTML = `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 
      01-1.414 0l-4-4a1 1 0 011.414-1.414L8 
      12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Completed`;
  } else {
    badge.classList.add("bg-yellow-100", "text-yellow-800");
    badge.innerHTML = `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 
      3s8.268 2.943 9.542 7c-1.274 4.057-5.064 
      7-9.542 7S1.732 14.057.458 10zM14 
      10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Pending`;
  }
}

function closeModal() {
  const modal = document.getElementById("subActivityModal");
  modal.querySelector("div").classList.add("scale-95");
  setTimeout(() => modal.classList.add("hidden"), 300);
}

document.addEventListener("DOMContentLoaded", () => {
  document
    .querySelector(".close-sub-modal")
    ?.addEventListener("click", closeModal);
  document.getElementById("markCompletedBtn")?.addEventListener("click", () => {
    const modal = document.getElementById("subActivityModal");
    const taskElement = modal.dataset.taskElement;
    if (taskElement) updateTaskStatus(taskElement, "Completed");
    closeModal();
  });
  document.getElementById("markPendingBtn")?.addEventListener("click", () => {
    const modal = document.getElementById("subActivityModal");
    const taskElement = modal.dataset.taskElement;
    if (taskElement) updateTaskStatus(taskElement, "Pending");
    closeModal();
  });
});
