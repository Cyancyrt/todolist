function formatDeadline(deadlineStr) {
  if (!deadlineStr) return "Tidak ada batas waktu";

  const deadline = new Date(deadlineStr);
  if (isNaN(deadline)) return "Format waktu tidak valid";

  const now = new Date();

  // Daftar nama hari dan bulan dalam bahasa Indonesia
  const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  const months = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];
  const dayName = days[deadline.getDay()];
  const day = deadline.getDate();
  const month = months[deadline.getMonth()];
  const year = deadline.getFullYear();

  const hours = String(deadline.getHours()).padStart(2, "0");
  const minutes = String(deadline.getMinutes()).padStart(2, "0");

  const formatted = `${dayName}, ${day} ${month} ${year} pukul ${hours}:${minutes} WIB`;

  // Tandai jika sudah lewat
  if (deadline < now) {
    return `⚠️ <span class="text-red-600 font-semibold">Terlambat!</span> (${formatted})`;
  }

  // Jika masih jauh dari sekarang
  return `📅 ${formatted}`;
}

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

    const id = subTask.dataset.id;

    openSubModal(subTask, id);
  });
});

// Buka modal dengan data
async function openSubModal(taskElement, id) {
  const modal = document.getElementById("subActivityModal");
  const holder = document.getElementById("editorjs-checklist");
  const form = document.getElementById("statusForm");
  const btn = document.getElementById("statusBtn");
  const statusSpan = document.getElementById("subStatus");

  holder.innerHTML = ""; // reset editor container

  // --- Fetch data terbaru ---
  try {
    const res = await fetch(`task/get-subtask/${id}`);
    const json = await res.json();

    if (!json.success) throw new Error(json.error || "Failed to load data");
    const data = json.data;

    // --- Tampilkan modal ---
    modal.classList.remove("hidden");
    modal.querySelector("div").classList.replace("scale-95", "scale-100");

    // --- Isi konten modal ---
    document.getElementById("subName").textContent = data.name;
    document.getElementById("subDeadline").innerHTML = formatDeadline(
      data.deadline
    );
    document.getElementById("subStatus").textContent = data.status;
    const editBtn = document.getElementById("editSubtaskBtn");
    if (editBtn) {
      editBtn.href = `${base_url}/dashboard/task/edit/${id}`;
    }

    // --- Update status tombol ---
    const normStatus = (data.status || "").toLowerCase();
    if (normStatus === "done" || normStatus === "missed") {
      btn.textContent = "Sudah Terlewat (Missed)";
      btn.className =
        "cursor-default pointer-events-none bg-gray-100 text-gray-600 px-4 py-3 rounded-lg w-full text-center border border-gray-300 font-medium";
      form.action = "";
    } else {
      form.action = `task/complete/${data.id}`;
      btn.textContent = "Mark as Completed";
      btn.className =
        "bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg w-full font-medium";
    }

    // --- Update warna status indicator ---
    statusSpan.className =
      "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium";
    if (normStatus === "completed" || normStatus === "done")
      statusSpan.classList.add("bg-green-100", "text-green-800");
    else if (normStatus === "in progress")
      statusSpan.classList.add("bg-blue-100", "text-blue-800");
    else statusSpan.classList.add("bg-yellow-100", "text-yellow-800");

    // --- Inisialisasi EditorJS dengan data dari server ---
    let saveTimer;
    const editor = new EditorJS({
      holder: "editorjs-checklist",
      tools: { checklist: { class: Checklist, inlineToolbar: true } },
      defaultBlock: "checklist",
      data: data.description,
      async onChange() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(async () => {
          try {
            console.log("Auto-saving checklist...");
            const payload = await editor.save();
            const res = await fetch(`task/update-checklist/${id}`, {
              method: "PUT",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "<?= csrf_hash() ?>",
              },
              body: JSON.stringify({ checklist: payload }),
            });

            if (!res.ok) throw new Error("Failed to save");
          } catch (err) {
            console.error("Error auto-saving checklist:", err);
          }
        }, 600);
      },
      onReady: () => {
        holder
          .querySelectorAll('input[type="checkbox"]')
          .forEach((c) => (c.disabled = false));
        holder.addEventListener("keydown", (e) => {
          if (e.key === "Enter") e.preventDefault();
        });
      },
    });

    modal._editorInstance = editor;
    modal.dataset.taskElement = taskElement;
  } catch (err) {
    console.error("Failed to open modal:", err);
    alert("Terjadi kesalahan saat memuat subtask.");
  }
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

  // HANCURKAN EditorJS instance agar bisa dibuat ulang
  if (modal._editorInstance) {
    try {
      modal._editorInstance.destroy();
    } catch (e) {
      console.warn("Failed to destroy editor:", e);
    }
    modal._editorInstance = null;
  }

  // Bersihkan container editor (penting!)
  const holder = document.getElementById("editorjs-checklist");
  holder.innerHTML = "";
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

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("deleteModal");
  const modalBox = document.getElementById("deleteModalBox");
  const cancelBtn = document.getElementById("cancelDelete");
  const deleteForm = document.getElementById("deleteForm");

  // Semua tombol delete
  const deleteButtons = document.querySelectorAll(".delete-activity-btn");

  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const url = btn.getAttribute("data-url");

      // Set form action
      deleteForm.action = url;

      // Tampilkan modal
      modal.classList.remove("hidden");

      // Animasi masuk
      setTimeout(() => {
        modalBox.classList.remove("scale-95", "opacity-0");
        modalBox.classList.add("scale-100", "opacity-100");
      }, 10);
    });
  });

  // Tutup modal
  cancelBtn.addEventListener("click", () => {
    closeModal();
  });

  function closeModal() {
    modalBox.classList.add("scale-95", "opacity-0");
    modalBox.classList.remove("scale-100", "opacity-100");

    setTimeout(() => {
      modal.classList.add("hidden");
    }, 200);
  }

  // Klik di luar modal menutup modal
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });
});
