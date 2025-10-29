<!-- Item 1: Klik untuk edit, ikon delete dan tombol cek kalendar -->
<li class="task-item group relative flex justify-between items-center py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 cursor-pointer" data-id="1">
    <div class="flex items-start space-x-3">
        <input type="checkbox" class="task-checkbox invisible-checkbox mt-1" />
        <!-- Ikon Expand/Collapse (tetap ada untuk expand list utama) -->
        <?php if (!empty($task['tasks'])): ?>
        <button class="expand-btn text-gray-500 hover:text-blue-600 mt-1 p-1 rounded-full hover:bg-blue-50 transition-all duration-200 toggle-subtask-btn" title="Expand sub-activities">
            <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <?php endif; ?>
        <div class="flex-1">
            <h4 class="text-lg font-semibold text-gray-800"><?= esc($task['name']) ?></h4>
            <p class="text-sm text-gray-600">
                <p class="text-sm text-gray-600">
                    <strong>Time:</strong> 
                    <?= !empty($task['schedule_date']) 
                        ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow-sm hover:bg-blue-200 transition-colors duration-200">
                            <svg class="w-3.5 h-3.5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>' . esc((new DateTime($task['schedule_date']))->format('M j, Y \a\t g:i A')) . 
                        '</span>'
                        : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 shadow-sm">Unknown</span>'
                    ?>

                    <strong>Status:</strong> 
                    <?= !empty($task['status']) ? getStatusBadge($task['status']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>'; ?> |
                    <strong>Recurrence:</strong> 
                    <?= !empty($task['recurrence']) ? getRecurrenceBadge($task['recurrence']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">None</span>'; ?>
                </p>
            </p>
            <!-- Sub-Activities List (Tanpa progress bar, langsung clickable untuk modal) -->
            <ul class="sub-tasks mt-3 ml-6 space-y-3 hidden transition-all duration-300 ease-in-out max-h-0 overflow-hidden">
            <?php if (!empty($task['tasks'])) :
                foreach ($task['tasks'] as $t) :?>
                <li
                    class="sub-task cursor-pointer group border border-gray-200 rounded-lg p-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-green-50 hover:shadow-md transition-all duration-200"
                    data-name="<?= esc($t['title']) ?>"
                    data-desc="<?= esc($t['description']) ?>"
                    data-deadline="<?= esc((new DateTime($t['due_time']))->format('M j, Y \a\t g:i A')) ?>"
                    data-status="<?= esc($t['status']) ?>"
                    >
                   <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-800"><?= esc($t['title']) ?></span>
                        <?= !empty($t['status']) ? getStatusBadge($t['status']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>'; ?>
                        <?= !empty($t['priority']) ? getPriorityBadge($t['priority']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">None</span>'; ?>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>


    <div class="flex items-center space-x-2 item-actions">
        <!-- Main action icon -->
        <div class="action-icon relative" title="Actions">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 cursor-pointer">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>

            <!-- Mini-action menu (hidden default) -->
            <div class="mini-action-menu absolute right-0 top-full mt-2 w-36 bg-white border rounded-lg shadow-lg hidden z-10">
                <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="event.stopPropagation(); window.location.href='<?= base_url('dashboard/activity/edit/'. $task['id']) ?>'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit Activity
                </button>
                <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="event.stopPropagation(); alert('Checking Calendar!')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Calendar
                </button>
                <a href="<?= base_url('dashboard/task/create/' . $task['id']) ?>" 
                class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Task
                </a>
            </div>
        </div>

        <form action="<?= base_url('dashboard/activity/delete/' . $task['id']) ?>" method="POST" class="inline">
            <?= csrf_field() ?>
            <button type="submit" onclick="return confirm('Apakah kamu yakin ingin menghapus aktivitas ini?')" 
                    class="w-5 h-5 text-red-500 hover:text-red-700 p-0 m-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </form>

    </div>
</li>