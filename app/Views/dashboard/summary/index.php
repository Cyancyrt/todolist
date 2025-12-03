<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 p-4 sm:p-6 min-h-screen overflow-auto">

  <!-- Title -->
  <div class="mb-6 text-center sm:text-left">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Activity Summary</h1>
    <p class="text-sm sm:text-base text-gray-600">Track your schedule effectiveness across periods and categories.</p>
  </div>

  <!-- Filter -->
  <form method="get" class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
    <div class="flex space-x-4">
      <select name="period" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
        <option value="day"   <?= $period === 'day' ? 'selected' : '' ?>>Today</option>
        <option value="week"  <?= $period === 'week' ? 'selected' : '' ?>>This Week</option>
        <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>This Month</option>
        <option value="year"  <?= $period === 'year' ? 'selected' : '' ?>>This Year</option>
      </select>

      <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
        <option value="all"      <?= $type === 'all' ? 'selected' : '' ?>>All</option>
        <option value="personal" <?= $type === 'personal' ? 'selected' : '' ?>>Personal</option>
        <option value="social"   <?= $type === 'social' ? 'selected' : '' ?>>Social</option>
      </select>

      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
        Filter
      </button>
    </div>
  </form>

  <!-- Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded-lg shadow">
      <p class="text-sm text-gray-500">
        Total Registered Tasks
        <span class="text-red-500 text-xs">(Combined: Activities + Tasks)</span>
      </p>
      <p class="text-2xl font-bold"><?= esc($totalTasks) ?></p>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
      <p class="text-sm text-gray-500">Completed</p>
      <p class="text-2xl font-bold text-green-600"><?= esc($completed) ?></p>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
      <p class="text-sm text-gray-500">Missed</p>
      <p class="text-2xl font-bold text-red-600"><?= esc($missed) ?></p>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
      <p class="text-sm text-gray-500">Pending</p>
      <p class="text-2xl font-bold text-yellow-600"><?= esc($pending) ?></p>
    </div>
  </div>

  <!-- Chart -->
  <div class="bg-white p-4 rounded-lg shadow mb-8">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">Activity Effectiveness Chart</h2>
    <canvas id="chart" height="120"></canvas>
  </div>

  <!-- Quick Insights -->
  <div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">Quick Insights</h2>
    <ul class="space-y-2 text-sm text-gray-700">
      <li>
        You've completed <strong><?= esc($insightCompleted) ?>%</strong> of your tasks.
      </li>
      <li><?= esc($insightMissed) ?> tasks were missed this period.</li>
      <li><?= esc($insightPending) ?> pending activities remaining.</li>
      <li>
        Overall effectiveness:
        <strong class="<?= $insightOverall === 'Good' ? 'text-green-600' : ($insightOverall === 'Needs Improvement' ? 'text-yellow-600' : 'text-red-600') ?>">
          <?= esc($insightOverall) ?>
        </strong>
      </li>
    </ul>
  </div>

</main>

<script>
  const ctx = document.getElementById('chart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Completed', 'Missed', 'Pending'],
      datasets: [{
        label: 'Activity Summary',
        data: [<?= $completed ?>, <?= $missed ?>, <?= $pending ?>],
        backgroundColor: ['#22c55e', '#ef4444', '#f59e0b']
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>

<?= $this->endSection() ?>
