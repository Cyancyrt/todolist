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
            <p class="text-sm text-gray-500">Gotong royong scheduled</p>
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
                <ul class="space-y-2">
                    <?php foreach ($tasks as $task): ?>
                        <li class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <div>
                                <span class="font-medium"><?= esc($task['title']) ?></span>
                                <?php if (!empty($task['due_time'])): ?>
                                    <span class="text-sm text-gray-600"> - <?= date('H:i A', strtotime($task['due_time'])) ?></span>
                                <?php endif; ?>
                              <?php if (!empty($task['description_text'])): ?>
                                <div class="text-sm text-gray-500 mt-1">
                                    <?= esc($task['description_text']) ?>
                                </div>
                            <?php endif; ?>
                            </div>
                            <form action="<?= base_url('tasks/complete/'.$task['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-sm hover:bg-green-600">
                                    Done
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No tasks yet for today.</p>
            <?php endif; ?>

            <a href="<?= base_url('tasks/create') ?>" class="mt-4 block text-center bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
                Add New Task
            </a>
        </div>

        <!-- Notes and Activities -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Notes & Activities</h3>

            <?php if (!empty($notes)): ?>
                <div class="space-y-4">
                    <?php foreach ($notes as $note): ?>
                        <div class="p-3 bg-gray-50 rounded">
                            <h4 class="font-medium"><?= esc($note['title']) ?></h4>
                            <p class="text-sm text-gray-600">
                                <?= nl2br(esc($note['description'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No notes available.</p>
            <?php endif; ?>

            <a href="<?= base_url('notes/create') ?>" class="mt-4 block text-center bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
                Add Note
            </a>
        </div>
    </div>
</main>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    const tasks = <?= $calendarTasks ?? '[]' ?>;
</script>
<script src="<?= base_url('assets/js/calendar.js') ?>"></script>
<?php $this->endSection(); ?>
