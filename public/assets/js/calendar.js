const monthYear = document.getElementById("monthYear");
const calendarDays = document.getElementById("calendarDays");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");

// Modal Elements
const calendarModal = document.getElementById("calendarModal");
const modalDateTitle = document.getElementById("modalDateTitle");
const modalContent = document.getElementById("modalContent");

let currentDate = new Date();
let holidays = [];

// Fungsi Buka/Tutup Modal
function openCalendarModal(dateStr, events) {
  // Set Judul Tanggal (Format: 25 Oktober 2023)
  const dateObj = new Date(dateStr);
  const options = { year: "numeric", month: "long", day: "numeric" };
  modalDateTitle.textContent = dateObj.toLocaleDateString("id-ID", options);

  // Reset Konten
  modalContent.innerHTML = "";

  if (events.length === 0) {
    modalContent.innerHTML = `
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Tidak ada jadwal pada tanggal ini.</p>
            </div>`;
  } else {
    // Render List Event
    const list = document.createElement("ul");
    list.className = "divide-y divide-gray-200";

    events.forEach((task) => {
      const item = document.createElement("li");
      item.className = "py-3 flex justify-between items-start";

      // Warna Badge Status
      let statusColor = "bg-blue-100 text-blue-800";
      if (task.status === "done") statusColor = "bg-green-100 text-green-800";
      if (task.status === "missed") statusColor = "bg-red-100 text-red-800";

      item.innerHTML = `
                <div>
                    <p class="text-sm font-medium text-gray-900">${
                      task.title
                    }</p>
                    <p class="text-xs text-gray-500 mt-1 truncate max-w-xs">
                        ${task.description_text || "Tidak ada deskripsi"}
                    </p>
                </div>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${statusColor}">
                    ${task.status}
                </span>
            `;
      list.appendChild(item);
    });
    modalContent.appendChild(list);
  }

  // Tampilkan Modal (Hapus class hidden)
  calendarModal.classList.remove("hidden");
}

window.closeCalendarModal = function () {
  calendarModal.classList.add("hidden");
};

// ... (Fetch Holidays & Helpers tetap sama) ...
async function fetchHolidays() {
  try {
    const res = await fetch("https://api-harilibur.vercel.app/api");
    holidays = await res.json();
  } catch (error) {
    console.error("Gagal memuat data hari libur:", error);
  }
}

function isHoliday(year, month, day) {
  const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
    day
  ).padStart(2, "0")}`;
  return holidays.some(
    (h) => h.holiday_date === dateStr && h.is_national_holiday === true
  );
}

function getEventsForDate(year, month, day) {
  if (typeof calendarEvents === "undefined") return [];
  const checkDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(
    day
  ).padStart(2, "0")}`;
  return calendarEvents.filter((task) => {
    const taskDate = task.due_time ? task.due_time.split(" ")[0] : "";
    return taskDate === checkDate;
  });
}

// ... (Fungsi renderCalendar diedit di bawah ini) ...

async function renderCalendar(date) {
  if (holidays.length === 0) await fetchHolidays();

  const year = date.getFullYear();
  const month = date.getMonth();
  monthYear.textContent = date.toLocaleDateString("en-US", {
    month: "long",
    year: "numeric",
  });

  const firstDay = new Date(year, month, 1).getDay();
  const lastDate = new Date(year, month + 1, 0).getDate();
  const prevLastDate = new Date(year, month, 0).getDate();

  calendarDays.innerHTML = "";

  // Render hari kosong
  for (let i = firstDay; i > 0; i--) {
    const day = document.createElement("div");
    day.className =
      "calendar-day p-2 text-gray-300 bg-gray-50 rounded-lg min-h-[80px]";
    day.textContent = prevLastDate - i + 1;
    calendarDays.appendChild(day);
  }

  // Render hari aktif
  for (let i = 1; i <= lastDate; i++) {
    const dayDiv = document.createElement("div");
    dayDiv.className =
      "calendar-day p-1 sm:p-2 cursor-pointer rounded-lg hover:bg-blue-50 transition relative min-h-[80px] sm:min-h-[100px] flex flex-col items-start justify-start border border-transparent hover:border-blue-200";

    // Angka Tanggal
    const dateNum = document.createElement("span");
    dateNum.textContent = i;
    dateNum.className = "text-sm font-medium z-10 mb-1 ml-1";
    dayDiv.appendChild(dateNum);

    const dayOfWeek = new Date(year, month, i).getDay();
    if (dayOfWeek === 0 || isHoliday(year, month, i)) {
      dateNum.classList.add("text-red-500");
    }

    if (
      i === new Date().getDate() &&
      month === new Date().getMonth() &&
      year === new Date().getFullYear()
    ) {
      dayDiv.classList.add("bg-blue-50", "border-blue-300");
      dateNum.classList.add(
        "text-blue-700",
        "font-bold",
        "bg-blue-200",
        "px-1.5",
        "rounded-full"
      );
    }

    // --- RENDER EVENT PILLS ---
    const events = getEventsForDate(year, month, i);

    if (events.length > 0) {
      const listContainer = document.createElement("div");
      listContainer.className = "w-full flex flex-col gap-1 mt-1";

      const maxEvents = 2;
      const displayEvents = events.slice(0, maxEvents);

      displayEvents.forEach((task) => {
        const eventDiv = document.createElement("div");

        // Default Style (Task - Biru)
        let bgClass = "bg-blue-100 text-blue-700 border-blue-200";

        // Style jika Activity (Hijau)
        if (task.type === "activity") {
          bgClass = "bg-green-100 text-green-700 border-green-200";
        }

        // Override warna jika status done/missed
        if (task.status === "done")
          bgClass = "bg-green-100 text-green-700 border-green-200"; // Done selalu hijau
        if (task.status === "missed")
          bgClass = "bg-red-100 text-red-700 border-red-200";

        eventDiv.className = `w-full px-1.5 py-0.5 rounded text-[10px] sm:text-xs font-medium border ${bgClass} truncate text-left shadow-sm`;
        eventDiv.textContent = task.title; // Controller sudah kirim 'title' untuk keduanya
        listContainer.appendChild(eventDiv);
      });

      if (events.length > maxEvents) {
        const more = document.createElement("div");
        more.className = "text-[10px] text-gray-500 pl-1 font-medium";
        more.textContent = `+ ${events.length - maxEvents} more`;
        listContainer.appendChild(more);
      }
      dayDiv.appendChild(listContainer);
    }

    // --- EVENT CLICK MEMBUKA MODAL ---
    dayDiv.addEventListener("click", () => {
      // Buka Modal dengan data events tanggal ini
      const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
        i
      ).padStart(2, "0")}`;
      openCalendarModal(dateStr, events);
    });

    calendarDays.appendChild(dayDiv);
  }
}

// Navigasi
prevMonthBtn.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar(currentDate);
});

nextMonthBtn.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
});

// Init
renderCalendar(currentDate);
