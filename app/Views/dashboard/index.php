<?= $this->extend('dashboard/template/layout') ?>

<?= $this->section('content') ?>

<main class="flex-1 p-6">
    <h2 class="text-xl font-semibold mb-6">Dashboard Overview</h2>
    
    <!-- Summary Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Today's Summary</h3>
            <p class="text-2xl font-bold text-green-600">
                <?= esc($completedToday) ?> Tasks Completed
            </p>
            <p class="text-sm text-gray-500">Out of <?= esc($plannedToday) ?> planned</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Weekly Routine</h3>
            <p class="text-2xl font-bold text-blue-600">
                <?= esc($socialWeek) ?> Social Activities
            </p>
            <p class="text-sm text-gray-500"><?= $activityDummy ?? 'Nothing yet to be ' ?> scheduled</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Automation Alerts</h3>
            <p class="text-2xl font-bold text-orange-600">
                <?= esc($automationPending) ?> Pending
            </p>
            <p class="text-sm text-gray-500">Notifications set</p>
        </div>
    </div>

    <!-- Interactive Calendar Section -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-center mb-4">
            <button id="prevMonth" class="bg-blue-100 p-2 rounded hover:bg-blue-200">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h3 id="monthYear" class="text-xl font-semibold text-gray-700"></h3>
            <button id="nextMonth" class="bg-blue-100 p-2 rounded hover:bg-blue-200">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-7 gap-2 text-center">
            <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day): ?>
                <div class="font-medium text-gray-600"><?= esc($day) ?></div>
            <?php endforeach; ?>
        </div>
        <div id="calendarDays" class="grid grid-cols-7 gap-2 mt-2"></div>
    </div>

    <!-- Tasks and Notes Section -->
 <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Real-Time To-Do List -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Real-Time Tasks</h3>
        <?php if (!empty($tasks)): ?>
            <div class="max-h-64 overflow-y-auto"> <!-- Tambahkan wrapper dengan tinggi maksimal dan scroll -->
                <ul class="space-y-2">
                    <?php foreach ($tasks as $task): ?>
                        <li class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <div>
                                <span class="font-medium"><?= esc($task['title']) ?></span>
                               <?php if (!empty($task['due_time'])): ?>
                                    <span class="text-sm text-gray-600">
                                        - <?= date('d M Y, H:i A', strtotime($task['due_time'])) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($task['description_text'])): ?>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <?= esc($task['description_text']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form action="<?= base_url('dashboard/task/complete/'.$task['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="PUT">
                                <?php if ($task['status'] === 'done'): ?>
                                    <button 
                                        type="button" 
                                        class="bg-red-500 text-white px-2 py-1 rounded text-sm opacity-60 cursor-not-allowed"
                                        disabled
                                    >
                                        Completed
                                    </button>
                                <?php else: ?>
                                    <button 
                                        type="submit" 
                                        class="bg-green-500 text-white px-2 py-1 rounded text-sm hover:bg-green-600"
                                    >
                                        Done
                                    </button>
                                <?php endif; ?>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">No tasks yet for today.</p>
        <?php endif; ?>
    </div>

    <!-- Notes and Activities -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Notes & Activities</h3>

        <?php if (!empty($notes)): ?>
            <div class="max-h-64 overflow-y-auto"> <!-- Tambahkan wrapper dengan tinggi maksimal dan scroll -->
                <div class="space-y-4">
                    <?php foreach ($notes as $note): ?>
                        <div class="p-3 bg-gray-50 rounded">
                            <h4 class="font-medium"><?= esc($note['title']) ?></h4>
                            <p class="text-sm text-gray-600">
                                <?= nl2br(esc($note['content'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">No notes available.</p>
        <?php endif; ?>

        <a href="<?= base_url('dashboard/notes/create') ?>" class="mt-4 block text-center bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
            Add Note
        </a>
    </div>
</div>

</main>
<div id="calendarModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCalendarModal()"></div>

  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        
        <div class="bg-blue-600 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg font-semibold leading-6 text-white" id="modalDateTitle">Detail Tanggal</h3>
          <button type="button" class="text-blue-100 hover:text-white focus:outline-none" onclick="closeCalendarModal()">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-4 py-5 sm:p-6">
          <div id="modalContent" class="space-y-4">
            <p class="text-gray-500 text-center">Memuat data...</p>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeCalendarModal()">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div><div id="calendarModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCalendarModal()"></div>

  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        
        <div class="bg-blue-600 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg font-semibold leading-6 text-white" id="modalDateTitle">Detail Tanggal</h3>
          <button type="button" class="text-blue-100 hover:text-white focus:outline-none" onclick="closeCalendarModal()">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-4 py-5 sm:p-6">
          <div id="modalContent" class="space-y-4">
            <p class="text-gray-500 text-center">Memuat data...</p>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeCalendarModal()">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    // Pastikan data ini valid JSON
    const calendarEvents = <?= !empty($calendarTasks) ? $calendarTasks : '[]' ?>;
</script>
<script src="<?= base_url('assets/js/calendar.js') ?>"></script>
<?php $this->endSection(); ?>
