<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>
<main class="flex-1 p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen overflow-x-hidden">

  <!-- Header -->
  <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto justify-center sm:justify-start">
      <button id="prevMonth" class="p-2 rounded-full bg-white shadow hover:bg-blue-50 transition">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <h2 id="monthYear" class="text-xl sm:text-3xl font-bold text-gray-800 tracking-tight text-center sm:text-left flex-1">
        October 2023
      </h2>
      <button id="nextMonth" class="p-2 rounded-full bg-white shadow hover:bg-blue-50 transition">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

    <div class="flex w-full sm:w-auto justify-center sm:justify-end gap-2">
      <button id="monthlyView" class="px-4 py-2 rounded-lg bg-blue-600 text-white shadow hover:bg-blue-700 transition text-sm sm:text-base">Monthly</button>
      <button id="weeklyView" class="px-4 py-2 rounded-lg bg-white text-gray-700 shadow hover:bg-gray-100 transition text-sm sm:text-base">Weekly</button>
    </div>
  </div>

  <!-- Legend -->
  <div class="flex flex-wrap justify-center gap-3 sm:gap-6 mb-6">
    <div class="flex items-center gap-2">
      <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
        <rect x="4" y="16" width="3" height="4" rx="1"></rect>
        <rect x="10" y="12" width="3" height="8" rx="1"></rect>
        <rect x="16" y="8" width="3" height="12" rx="1"></rect>
      </svg>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Low</span>
    </div>
    <div class="flex items-center gap-2">
      <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
        <rect x="4" y="16" width="3" height="4" rx="1"></rect>
        <rect x="10" y="10" width="3" height="10" rx="1"></rect>
        <rect x="16" y="6" width="3" height="14" rx="1"></rect>
      </svg>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Medium</span>
    </div>
    <div class="flex items-center gap-2">
      <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
        <rect x="4" y="18" width="3" height="2" rx="1"></rect>
        <rect x="10" y="12" width="3" height="8" rx="1"></rect>
        <rect x="16" y="6" width="3" height="14" rx="1"></rect>
      </svg>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">High</span>
    </div>
  </div>

  <!-- Status Legend -->
  <div class="flex flex-wrap justify-center gap-3 sm:gap-6 mb-6">
    <div class="flex items-center gap-2">
      <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Soon</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Upcoming</span>
    </div>
    <div class="flex items-center gap-2">
      <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Done</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Completed</span>
    </div>
    <div class="flex items-center gap-2">
      <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">Late</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Overdue</span>
    </div>
  </div>

  <!-- Calendar -->
  <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg p-4 sm:p-6 border border-white/40 mx-auto max-w-screen-lg">
    <!-- Days of Week -->
    <div class="calendar-wrapper w-full overflow-x-auto">
        <div class="min-w-[700px]">
            <!-- Header hari -->
            <div class="grid grid-cols-7 text-center font-semibold text-gray-600 mb-3 text-xs sm:text-base">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
            </div>

            <!-- Kotak tanggal -->
            <div id="calendarDays" class="grid grid-cols-7 gap-2 text-sm"></div>
        </div>
        </div>
    <!-- Dates -->
    <div id="calendarDays" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2 sm:gap-3 auto-rows-fr"></div>
  </div>
</main>

<script>
  const monthYear = document.getElementById('monthYear');
  const calendarDays = document.getElementById('calendarDays');
  const prevMonthBtn = document.getElementById('prevMonth');
  const nextMonthBtn = document.getElementById('nextMonth');
  let currentDate = new Date();

  function generateDummyTasks(year, month) {
    const tasks = {};
    const days = new Date(year, month + 1, 0).getDate();
    const now = new Date();
    for (let i = 1; i <= days; i++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
      const numTasks = Math.floor(Math.random() * 3);
      if (numTasks) {
        tasks[dateStr] = [];
        for (let j = 0; j < numTasks; j++) {
          const priority = ['low', 'medium', 'high'][Math.floor(Math.random() * 3)];
          const status = ['upcoming','completed','overdue'][Math.floor(Math.random() * 3)];
          tasks[dateStr].push({ name:`Task ${j+1}`, priority, time:'08:00', status });
        }
      }
    }
    return tasks;
  }

  let tasks = generateDummyTasks(currentDate.getFullYear(), currentDate.getMonth());

  function renderCalendar(date) {
    const y = date.getFullYear();
    const m = date.getMonth();
    monthYear.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const first = new Date(y, m, 1).getDay();
    const last = new Date(y, m + 1, 0).getDate();
    calendarDays.innerHTML = '';

    for (let i = 0; i < first; i++) {
      const blank = document.createElement('div');
      blank.className = 'calendar-day opacity-50';
      calendarDays.appendChild(blank);
    }

    for (let i = 1; i <= last; i++) {
      const dateStr = `${y}-${String(m + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
      const d = document.createElement('div');
      d.className = 'calendar-day';
      d.innerHTML = `<div class="date-number">${i}</div>`;

      const today = new Date();
      if (i === today.getDate() && m === today.getMonth() && y === today.getFullYear()) d.classList.add('today');

      if (tasks[dateStr]) {
        tasks[dateStr].forEach(t => {
          const el = document.createElement('div');
          el.className = `task-card ${t.priority} ${t.status}`;
          const badge = t.status === 'completed' ? 'Done' : t.status === 'overdue' ? 'Late' : 'Soon';
          el.innerHTML = `
            <span>${t.name}</span>
            <div class="flex items-center">
              <div class="priority-icon">
                ${t.priority === 'high' ? `<div style="height:5px"></div><div style="height:9px"></div><div style="height:13px"></div>` :
                  t.priority === 'medium' ? `<div style="height:7px"></div><div style="height:11px"></div>` :
                  `<div style="height:8px"></div>`}
              </div>
              <span class="status-badge ${t.status}">${badge}</span>
            </div>`;
          d.appendChild(el);
        });
      }
      calendarDays.appendChild(d);
    }
  }

  prevMonthBtn.onclick = () => { currentDate.setMonth(currentDate.getMonth() - 1); tasks = generateDummyTasks(currentDate.getFullYear(), currentDate.getMonth()); renderCalendar(currentDate); };
  nextMonthBtn.onclick = () => { currentDate.setMonth(currentDate.getMonth() + 1); tasks = generateDummyTasks(currentDate.getFullYear(), currentDate.getMonth()); renderCalendar(currentDate); };
  renderCalendar(currentDate);
</script>
<?= $this->endSection() ?>
