<?php

if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status)
    {
        $map = [
            'upcoming' => [
                'label' => 'Upcoming',
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-800',
                'icon' => '<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>'
            ],
            'done' => [
                'label' => 'Completed',
                'bg' => 'bg-green-100',
                'text' => 'text-green-800',
                'icon' => '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>'
            ],
            'missed' => [
                'label' => 'Missed',
                'bg' => 'bg-red-100',
                'text' => 'text-red-800',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
            ],
        ];

        $cfg = $map[$status] ?? $map['upcoming'];

        return "
        <span class='status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {$cfg['bg']} {$cfg['text']}'>
            <svg class='w-3 h-3 mr-1' fill='currentColor' viewBox='0 0 20 20'>
                {$cfg['icon']}
            </svg>
            {$cfg['label']}
        </span>
        ";
    }
}

if (!function_exists('getPriorityBadge')) {
    function getPriorityBadge($priority)
    {
        $map = [
            'low' => [
                'label' => 'Low',
                'bg' => 'bg-green-100',
                'text' => 'text-green-800',
                'icon' => '<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>'
            ],
            'medium' => [
                'label' => 'Medium',
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'icon' => '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>'
            ],
            'high' => [
                'label' => 'High',
                'bg' => 'bg-red-100',
                'text' => 'text-red-800',
                'icon' => '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>'
            ],
        ];

        $cfg = $map[$priority] ?? $map['medium'];

        return "
        <span class='inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {$cfg['bg']} {$cfg['text']}'>
            <svg class='w-3 h-3 mr-1' fill='currentColor' viewBox='0 0 20 20'>
                {$cfg['icon']}
            </svg>
            {$cfg['label']}
        </span>
        ";
    }
    if (!function_exists('getRecurrenceBadge')) {
        function getRecurrenceBadge($recurrence)
        {
            switch ($recurrence) {
                case 'daily':
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 11V5l5 3-5 3z"/>
                                </svg>
                                Daily
                            </span>';
                case 'weekly':
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4h12v12H4z"/>
                                </svg>
                                Weekly
                            </span>';
                case 'monthly':
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a2 2 0 00-2 2v2h12V4a2 2 0 00-2-2H6zM4 8h12v10H4V8z"/>
                                </svg>
                                Monthly
                            </span>';
                default:
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 4v12m8-6H2"/>
                                </svg>
                                None
                            </span>';
            }
        }
    }

}
