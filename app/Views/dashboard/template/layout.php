<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Routine Manager Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/task/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/task/create.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/calendar/style.css') ?>">

    <style>
        /* Custom styles for calendar interactivity */
        .calendar-day {
            transition: background-color 0.2s;
        }
        .calendar-day:hover {
            background-color: #E3F2FD;
        }
        .calendar-day.selected {
            background-color: #C8E6C9;
            color: #2E7D32;
        }

        /* Sidebar hover animation */
        aside {
            transition: width 0.4s ease-in-out; /* Smooth animation, not too fast (0.4s) for readability */
        }
        aside:hover {
            width: 16rem; /* 256px, expanded for text visibility */
        }
        .sidebar-item {
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
            pointer-events: none;
        }
        aside:hover .sidebar-item {
            opacity: 1;
            pointer-events: auto;
        }

        /* Profile dropdown */
        .dropdown {
            position: relative;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
            min-width: 150px;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body class="bg-blue-50 font-sans">
    
    <?= $this->include('dashboard/template/header'); ?>
    <div class="flex min-h-screen">

     <?= $this->include('dashboard/template/sidebar'); ?>
     
     <?= $this->renderSection('content'); ?>
     
    </div>
    <?= $this->include('dashboard/template/footer'); ?>
    <?= $this->renderSection('scripts') ?>
    <script src="<?= base_url('assets/js/clock.js') ?>"></script>


</body>
</html>
