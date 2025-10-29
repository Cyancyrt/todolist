<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content'); ?>

<!-- Main Content -->
<main class="flex-1 p-4 sm:p-6 bg-gray-50 min-h-full overflow-auto"> <!-- flex-1 untuk isi ruang, overflow-auto untuk scroll -->
    <!-- Header Ringkas -->
    <div class="mb-6 text-center sm:text-left">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Activity Summary</h1>
        <p class="text-sm sm:text-base text-gray-600">Track your schedule effectiveness: completed, missed, postponed, or extended tasks and routines for personal and social activities.</p>
    </div>

    <!-- Filter Dinamis -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div class="flex space-x-4">
            <select id="periodFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Filter by period">
                <option value="day">Today</option>
                <option value="week">This Week</option>
                <option value="month" selected>This Month</option>
                <option value="year">This Year</option>
            </select>
            <select id="typeFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Filter by type">
                <option value="all" selected>All</option>
                <option value="personal">Personal</option>
                <option value="social">Social</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button id="lightModeToggle" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm transition-colors duration-200" title="Toggle light mode (hide chart)">Light Mode</button>
            <button id="exportBtn" class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm transition-colors duration-200" title="Export as PDF">Export PDF</button>
            <button id="printBtn" onclick="window.print()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition-colors duration-200" title="Print page">Print</button>
        </div>
    </div>

    <!-- Kartu Statistik Utama (Overview Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                    <p class="text-xl font-bold text-gray-800" id="totalTasks">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-xl font-bold text-gray-800" id="completed">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-full">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Missed</p>
                    <p class="text-xl font-bold text-gray-800" id="missed">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-full">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-xl font-bold text-gray-800" id="pending">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Efektivitas Aktivitas (Chart Section) -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-8" id="chartSection">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Activity Effectiveness Chart</h2>
        <canvas id="effectivenessChart" width="400" height="200"></canvas>
    </div>

    <!-- Daftar Insight Cepat (Textual Insight Section) -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Insights</h2>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></span>
                <span>You've completed <strong id="insightCompleted">0%</strong> of your personal tasks this month – keep it up!</span>
            </li>
            <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-red-500 rounded-full mt-2 mr-3"></span>
                <span><strong id="insightMissed">0</strong> social routines were missed; consider rescheduling.</span>
            </li>
            <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3"></span>
                <span>You have <strong id="insightPending">0</strong> pending activities – prioritize them!</span>
            </li>
            <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></span>
                <span>Overall, your effectiveness is <strong id="insightOverall">Good</strong> based on current data.</span>
            </li>
        </ul>
    </div>
</main>

<!-- Script untuk Interaktivitas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Placeholder data (ganti dengan data PHP dinamis)
        const summaryData = {
            day: { total: 10, completed: 7, missed: 2, pending: 1 },
            week: { total: 50, completed: 35, missed: 10, pending: 5 },
            month: { total: 200, completed: 150, missed: 30, pending: 20 },
            year: { total: 2400, completed: 1800, missed: 400, pending: 200 }
        };

        const periodFilter = document.getElementById('periodFilter');
        const typeFilter = document.getElementById('typeFilter');
        const lightModeToggle = document.getElementById('lightModeToggle');
        const chartSection = document.getElementById('chartSection');
        const ctx = document.getElementById('effectivenessChart').getContext('2d');

        let chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Completed', 'Missed', 'Pending'],
                datasets: [{
                    label: 'Activities',
                    data: [150, 30, 20],
                    backgroundColor: ['rgba(34, 197, 94, 0.6)', 'rgba(239, 68, 68, 0.6)', 'rgba(245, 158, 11, 0.6)'],
                    borderColor: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 1)', 'rgba(245, 158, 11, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Fungsi update data berdasarkan filter
        function updateSummary(period, type) {
            const data = summaryData[period];
            document.getElementById('totalTasks').textContent = data.total;
            document.getElementById('completed').textContent = data.completed;
            document.getElementById('missed').textContent = data.missed;
            document.getElementById('pending').textContent = data.pending;

            // Update chart
            chart.data.datasets[0].data = [data.completed, data.missed, data.pending];
            chart.update();

            // Update insights (placeholder logic)
            document.getElementById('insightCompleted').textContent = Math.round((data.completed / data.total) * 100) + '%';
            document.getElementById('insightMissed').textContent = data.missed;
            document.getElementById('insightPending').textContent = data.pending;
            document.getElementById('insightOverall').textContent = data.completed > data.missed ? 'Good' : 'Needs Improvement';
        }

        // Event listeners
        periodFilter.addEventListener('change', () => updateSummary(periodFilter.value, typeFilter.value));
        typeFilter.addEventListener('change', () => updateSummary(periodFilter.value, typeFilter.value));
        lightModeToggle.addEventListener('click', () => {
            chartSection.classList.toggle('hidden');
            lightModeToggle.textContent = chartSection.classList.contains('hidden') ? 'Show Chart' : 'Light Mode';
        });

        // Export PDF (gunakan library seperti jsPDF jika perlu)
        document.getElementById('exportBtn').addEventListener('click', () => {
            alert('Export to PDF functionality would be implemented here (e.g., using jsPDF).');
        });

        // Initial load
        updateSummary('month', 'all');
    });
</script>

<?= $this->endSection() ?>
