<!-- Item 1: Klik untuk edit, ikon delete dan tombol cek kalendar -->
<li class="task-item group relative flex justify-between items-center py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 cursor-pointer" data-id="1">
    <div class="flex items-start space-x-3 flex-1 min-w-0">
        <div class="task-item" data-id="<?= $task['id'] ?>">
            <input type="checkbox" class="task-checkbox invisible-checkbox mt-1" value="<?= $task['id'] ?>">
        </div>

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
                    <?= !empty($task['next_run_at']) 
                        ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow-sm hover:bg-blue-200 transition-colors duration-200">
                            <svg class="w-3.5 h-3.5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>' . esc((new DateTime($task['next_run_at']))->format('M j, Y \a\t g:i A')) . 
                        '</span>'
                        : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 shadow-sm">Overdue</span>'
                    ?>

                    <strong>Status:</strong> 
                    <?= !empty($task['status']) ? getStatusBadge($task['status']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 max-w-full truncate">Unknown</span>'; ?> |
                    <strong>Recurrence:</strong> 
                    <?= !empty($task['recurrence']) ? getRecurrenceBadge($task['recurrence']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 max-w-full truncate">None</span>'; ?>
                </p>
            </p>
            <!-- Sub-Activities List (Tanpa progress bar, langsung clickable untuk modal) -->
            <ul class="sub-tasks mt-3 ml-6 space-y-3 hidden transition-all duration-300 ease-in-out max-h-0 overflow-hidden">
            <?php if (!empty($task['tasks'])) :
                foreach ($task['tasks'] as $t) :?>
                <li
                    class="sub-task cursor-pointer group border border-gray-200 rounded-lg p-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-green-50 hover:shadow-md transition-all duration-200 z-20 "
                    data-id="<?= esc($t['id'])?>"
                    >
                    <div class="flex justify-between items-center flex-wrap gap-2 min-w-0">
                        <span class="text-sm font-medium text-gray-800"><?= esc($t['title']) ?></span>
                        <?= !empty($t['status']) ? getStatusBadge($t['status']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 max-w-full truncate">Unknown</span>'; ?>
                        <?= !empty($t['priority']) ? getPriorityBadge($t['priority']) : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 max-w-full truncate">None</span>'; ?>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>


    <div class="flex items-center space-x-3 item-actions flex-shrink-0">
        <!-- Add Sub Task -->
        <a href="<?= base_url('dashboard/task/create/' . $task['id']) ?>"
        class="group/add relative w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
        
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"></path>
            </svg>

            <span class="pointer-events-none absolute bottom-full mb-2 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-white opacity-0 transition-opacity group-hover/add:opacity-100 
            text-[10px] md:text-xs lg:text-sm z-50">
                Add Sub Task
                <span class="absolute left-1/2 top-full -translate-x-1/2 border-4 border-transparent border-t-gray-800"></span>
            </span>
        </a>

        <!-- Menu Icon -->
        <div class="action-icon relative group/menu" 
            data-menu="#action-menu-<?= $task['id'] ?>">
            
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 cursor-pointer">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>

            <span class="pointer-events-none absolute bottom-full mb-2 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-white opacity-0 transition-opacity group-hover/menu:opacity-100 
            text-[10px] md:text-xs lg:text-sm z-50">
                More Actions
                <span class="absolute left-1/2 top-full -translate-x-1/2 border-4 border-transparent border-t-gray-800"></span>
            </span>

            <!-- Mini Action Menu -->
            <div id="action-menu-<?= $task['id'] ?>"
                class="mini-action-menu absolute right-0 top-full mt-2 w-40 bg-white border rounded-lg shadow-lg hidden z-[9999]">
                <!-- Edit Activity -->
                <button
                    title="Edit This Activity"
                    class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    onclick="event.stopPropagation(); window.location.href='<?= base_url('dashboard/activity/edit/' . $task['id']) ?>'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit Activity
                </button>

                <!-- Calendar -->
                <button
                    title="View in Calendar"
                    class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    onclick="event.stopPropagation(); window.location.href='<?= base_url('dashboard/calendar'); ?>'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"></path>
                    </svg>
                    Calendar
                </button>

                <!-- Divider -->
                <div class="border-t my-1"></div>
                   <button type="button"
                            class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 delete-activity-btn"
                            data-id="<?= $task['id'] ?>"
                            data-url="<?= base_url('dashboard/activity/delete/' . $task['id']) ?>">
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Activity
                    </button>
                </div>
        </div>
    </div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal"
     class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 flex items-center justify-center">
    
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 transform scale-95 opacity-0 transition-all duration-200"
         id="deleteModalBox">

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
        <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus aktivitas ini?</p>

        <form id="deleteForm" method="POST">
            <?= csrf_field() ?>
            <div class="flex justify-end space-x-3">
                <button type="button"
                        id="cancelDelete"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                    Hapus
                </button>
            </div>
        </form>

    </div>
</div>

</li>
