// Simple interactive calendar JS
const monthYear = document.getElementById("monthYear");
const calendarDays = document.getElementById("calendarDays");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");

let currentDate = new Date();

let holidays = [];

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

async function renderCalendar(date) {
  await fetchHolidays(); // pastikan data libur sudah diambil
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

  for (let i = firstDay; i > 0; i--) {
    const day = document.createElement("div");
    day.className = "calendar-day p-2 text-gray-400";
    day.textContent = prevLastDate - i + 1;
    calendarDays.appendChild(day);
  }

  for (let i = 1; i <= lastDate; i++) {
    const day = document.createElement("div");
    day.className = "calendar-day p-2 cursor-pointer rounded";
    day.textContent = i;

    const dayOfWeek = new Date(year, month, i).getDay();
    if (dayOfWeek === 0 || isHoliday(year, month, i)) {
      day.classList.add("text-red-500", "font-semibold"); // Minggu / hari libur nasional
    }

    if (
      i === new Date().getDate() &&
      month === new Date().getMonth() &&
      year === new Date().getFullYear()
    ) {
      day.classList.add("selected");
    }

    day.addEventListener("click", () => {
      document
        .querySelectorAll(".calendar-day")
        .forEach((d) => d.classList.remove("selected"));
      day.classList.add("selected");
      alert(`Selected day: ${i}`);
    });
    calendarDays.appendChild(day);
  }
}

prevMonthBtn.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar(currentDate);
});

nextMonthBtn.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
});

renderCalendar(currentDate);

updateClock(); // Initial call
