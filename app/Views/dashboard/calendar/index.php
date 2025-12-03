<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>

<div class="flex flex-col h-full w-full max-w-full">

  <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-start">
      <button id="prevMonth" class="p-2 rounded-full bg-white shadow hover:bg-blue-50 text-gray-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      
      <h2 id="monthYear" class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight text-center">
        Loading...
      </h2>
      
      <button id="nextMonth" class="p-2 rounded-full bg-white shadow hover:bg-blue-50 text-gray-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>
    </div>

    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-blue-600 text-white shadow text-sm font-medium">Monthly</button>
      <button class="px-4 py-2 rounded-lg bg-gray-200 text-gray-400 shadow text-sm cursor-not-allowed" disabled>Weekly</button>
    </div>
  </div>

  <div class="flex flex-wrap justify-center sm:justify-start gap-3 sm:gap-6 mb-6 px-2">
    <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
      <span class="flex gap-0.5"><div class="w-1 h-3 bg-green-500 rounded-sm"></div><div class="w-1 h-2 bg-green-500/30 rounded-sm"></div></span>
      <span>Low</span>
    </div>
    <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
      <span class="flex gap-0.5"><div class="w-1 h-3 bg-yellow-500 rounded-sm"></div><div class="w-1 h-3 bg-yellow-500 rounded-sm"></div></span>
      <span>Med</span>
    </div>
    <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
      <span class="flex gap-0.5"><div class="w-1 h-3 bg-red-500 rounded-sm"></div><div class="w-1 h-3 bg-red-500 rounded-sm"></div><div class="w-1 h-3 bg-red-500 rounded-sm"></div></span>
      <span>High</span>
    </div>
    
    <div class="w-px h-4 bg-gray-300 mx-2 hidden sm:block"></div>

    <div class="flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-blue-500"></span>
      <span class="text-xs sm:text-sm text-gray-600">Task</span>
    </div>
    <div class="flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-green-500"></span>
      <span class="text-xs sm:text-sm text-gray-600">Activity</span>
    </div>
  </div>

  <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/50 w-full overflow-hidden">
    
    <div class="hidden md:grid grid-cols-7 text-center py-3 bg-gray-50 border-b border-gray-200 font-semibold text-gray-600 text-sm">
      <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
    </div>

    <div id="calendarDays" class="grid grid-cols-1 md:grid-cols-7 gap-[1px] bg-gray-200 border-b border-gray-200">
      </div>
  </div>

</div>

<div id="detailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex justify-center items-center z-[9999] transition-opacity duration-300 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl transform scale-95 transition-transform duration-200 animate-fade-in relative overflow-hidden">
      <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex justify-between items-center">
        <h2 id="modalDate" class="text-lg font-bold"></h2>
        <button id="closeModal" class="bg-white/20 hover:bg-white/30 rounded-full p-1 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      
      <div id="modalContent" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto"></div>
    </div>
</div>

<script>
const monthYear = document.getElementById('monthYear');
const calendarDays = document.getElementById('calendarDays');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');

let currentDate = new Date();
let tasks = {};
let activities = {};

// --- FETCHING DATA ---
async function fetchAll(year, month) {
    monthYear.innerHTML = '<span class="animate-pulse">Loading...</span>';
    
    try {
        const mStr = String(month + 1).padStart(2,'0');
        // Pastikan URL endpoint sesuai dengan routing CI4 Anda
        const [resTasks, resActs] = await Promise.all([
            fetch(`<?= base_url('dashboard/calendar/fetch') ?>?year=${year}&month=${mStr}`),
            fetch(`<?= base_url('dashboard/calendar/fetch-activities') ?>?year=${year}&month=${mStr}`)
        ]);

        if (resTasks.ok) tasks = await resTasks.json();
        if (resActs.ok) activities = await resActs.json();
        
    } catch (err) {
        console.error("Error fetching data:", err);
    } finally {
        renderCalendar(currentDate);
    }
}

// --- HELPER: SVG ICONS GENERATOR ---

// 1. Priority Bar Chart SVG
function getPrioritySVG(priority) {
    const p = (priority || 'low').toLowerCase();
    
    // Warna
    const color = p === 'high' ? 'text-red-500' : (p === 'medium' ? 'text-yellow-500' : 'text-green-500');
    
    // Tinggi Batang (Bar Height) untuk efek diagram
    // Bar 1 (Kiri), Bar 2 (Tengah), Bar 3 (Kanan)
    // Low: Pendek semua / Cuma 1
    // Med: Sedang / 2
    // High: Tinggi semua / 3
    
    let h1, h2, h3;
    if (p === 'high') { h1=14; h2=14; h3=14; }
    else if (p === 'medium') { h1=6; h2=10; h3=14; } // Tangga naik
    else { h1=4; h2=4; h3=4; } // Rata bawah

    // SVG Bar Chart Icon
    return `
        <svg class="w-4 h-4 ${color}" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <rect x="4" y="${20-h1}" width="4" height="${h1}" rx="1" opacity="${p==='low'?'0.5':'1'}"></rect>
            <rect x="10" y="${20-h2}" width="4" height="${h2}" rx="1" opacity="${p==='low'?'0.5':'1'}"></rect>
            <rect x="16" y="${20-h3}" width="4" height="${h3}" rx="1"></rect>
        </svg>
    `;
}

// 2. Activity Type Icons
function getTypeSVG(type) {
    const t = (type || 'personal').toLowerCase();
    
    if (t === 'social') {
        // Icon Users / Group (Social)
        return `
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        `;
    } else {
        // Icon User Single (Personal)
        return `
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        `;
    }
}

// --- RENDER CALENDAR ---
function renderCalendar(date) {
    const y = date.getFullYear();
    const m = date.getMonth();
    
    monthYear.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const firstDay = new Date(y, m, 1).getDay(); 
    const lastDate = new Date(y, m + 1, 0).getDate();

    calendarDays.innerHTML = '';

    // Blank Days (Desktop Only)
    for (let i = 0; i < firstDay; i++) {
        const blank = document.createElement('div');
        blank.className = 'bg-gray-50/50 hidden md:block min-h-[100px]'; 
        calendarDays.appendChild(blank);
    }

    // Date Cells
    for (let i = 1; i <= lastDate; i++) {
        const dateStr = `${y}-${String(m + 1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;
        const dayDiv = document.createElement('div');
        
        const isToday = (i === new Date().getDate() && m === new Date().getMonth() && y === new Date().getFullYear());
        
        dayDiv.className = `
            bg-white group relative p-3 cursor-pointer transition-all duration-200 hover:bg-blue-50
            flex flex-row md:flex-col items-center md:items-start justify-between md:justify-start gap-3
            min-h-[70px] md:min-h-[120px]
            ${isToday ? 'bg-blue-50 ring-inset ring-2 ring-blue-400 z-10' : ''}
        `;

        const dateNumClass = isToday 
            ? "bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold shadow-md"
            : "text-gray-700 font-semibold text-lg md:text-base";
            
        const dayName = new Date(y, m, i).toLocaleDateString('en-US', { weekday: 'short' });
        
        dayDiv.innerHTML = `
            <div class="flex flex-col md:flex-row items-center gap-1">
                <span class="${dateNumClass}">${i}</span>
                <span class="md:hidden text-xs text-gray-400 font-medium uppercase tracking-wide">${dayName}</span>
            </div>
        `;

        const contentDiv = document.createElement('div');
        contentDiv.className = "flex-1 flex flex-row md:flex-col gap-1.5 md:gap-1 justify-end md:justify-start flex-wrap w-full";

        // Render Tasks Dot/Pill
        if (tasks[dateStr] && tasks[dateStr].length > 0) {
            const count = tasks[dateStr].length;
            const first = tasks[dateStr][0];
            const el = document.createElement('div');
            el.className = "bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium truncate max-w-full text-center md:text-left shadow-sm border border-blue-200";
            el.innerHTML = `<span class="md:hidden">${count} Task${count>1?'s':''}</span><span class="hidden md:inline">${first.name} ${count > 1 ? `+${count-1}` : ''}</span>`;
            contentDiv.appendChild(el);
        }

        // Render Activities Dot/Pill
        if (activities[dateStr] && activities[dateStr].length > 0) {
            const count = activities[dateStr].length;
            const first = activities[dateStr][0];
            const el = document.createElement('div');
            el.className = "bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium truncate max-w-full text-center md:text-left shadow-sm border border-green-200";
            el.innerHTML = `<span class="md:hidden">${count} Actv</span><span class="hidden md:inline">${first.name} ${count > 1 ? `+${count-1}` : ''}</span>`;
            contentDiv.appendChild(el);
        }

        dayDiv.appendChild(contentDiv);
        dayDiv.onclick = () => openModal(dateStr);
        calendarDays.appendChild(dayDiv);
    }
}

// --- MODAL LOGIC (UPDATED WITH ICONS) ---
function openModal(dateStr) {
    const modal = document.getElementById('detailModal');
    const modalDate = document.getElementById('modalDate');
    const modalContent = document.getElementById('modalContent');

    const dateObj = new Date(dateStr);
    modalDate.textContent = dateObj.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    modalContent.innerHTML = '';

    let isEmpty = true;

    // Helper: Buat Kartu di dalam Modal
    const createCard = (item, type) => {
        const colorClass = type === 'task' ? 'bg-blue-50 border-blue-200' : 'bg-green-50 border-green-200';
        const badgeColor = type === 'task' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
        const status = item.status || 'Upcoming';
        
        let metaInfo = '';

        // LOGIKA ICON
        if (type === 'task') {
            // Tampilkan Priority Bar Chart
            const priorityIcon = getPrioritySVG(item.priority);
            metaInfo = `
                <div class="flex items-center gap-1.5 mt-1" title="Priority: ${item.priority}">
                    ${priorityIcon}
                    <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">
                        Priority: ${item.priority || 'Low'}
                    </span>
                </div>
            `;
        } else {
            // Tampilkan Activity Type Icon
            const typeIcon = getTypeSVG(item.type);
            metaInfo = `
                <div class="flex items-center gap-1.5 mt-1" title="Type: ${item.type}">
                    ${typeIcon}
                    <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">
                        ${item.type || 'Personal'}
                    </span>
                </div>
            `;
        }
        
        return `
            <div class="p-3 border rounded-lg ${colorClass} flex justify-between items-start transition hover:shadow-md">
                <div class="flex-1 min-w-0 pr-2">
                    <h4 class="font-semibold text-gray-800 truncate" title="${item.name}">${item.name}</h4>
                    ${metaInfo}
                </div>
                <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase ${badgeColor} border border-white shadow-sm">
                    ${status}
                </span>
            </div>
        `;
    };

    // Populate Tasks
    if (tasks[dateStr] && tasks[dateStr].length > 0) {
        modalContent.innerHTML += `<div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2"><div class="h-px bg-gray-200 flex-1"></div>TASKS<div class="h-px bg-gray-200 flex-1"></div></div>`;
        const container = document.createElement('div');
        container.className = "space-y-2 mb-4";
        tasks[dateStr].forEach(t => container.innerHTML += createCard(t, 'task'));
        modalContent.appendChild(container);
        isEmpty = false;
    }

    // Populate Activities
    if (activities[dateStr] && activities[dateStr].length > 0) {
        modalContent.innerHTML += `<div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2"><div class="h-px bg-gray-200 flex-1"></div>ACTIVITIES<div class="h-px bg-gray-200 flex-1"></div></div>`;
        const container = document.createElement('div');
        container.className = "space-y-2";
        activities[dateStr].forEach(a => container.innerHTML += createCard(a, 'activity'));
        modalContent.appendChild(container);
        isEmpty = false;
    }

    if (isEmpty) {
        modalContent.innerHTML = `
            <div class="text-center py-10 flex flex-col items-center justify-center text-gray-400">
                <div class="bg-gray-50 p-4 rounded-full mb-3">
                    <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-sm font-medium">Tidak ada jadwal.</p>
                <p class="text-xs mt-1">Ketuk tombol tambah untuk mulai.</p>
            </div>
        `;
    }

    modal.classList.remove('hidden');
}

// Navigation & Close Logic
document.getElementById('closeModal').onclick = () => {
    document.getElementById('detailModal').classList.add('hidden');
};

prevMonthBtn.onclick = () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    fetchAll(currentDate.getFullYear(), currentDate.getMonth());
};
nextMonthBtn.onclick = () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    fetchAll(currentDate.getFullYear(), currentDate.getMonth());
};

// Start
fetchAll(currentDate.getFullYear(), currentDate.getMonth());
</script>
<?= $this->endSection() ?>