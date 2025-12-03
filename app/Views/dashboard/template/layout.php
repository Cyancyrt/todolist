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
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard/style.css') ?>">
</head>
<body class="bg-blue-50 font-sans min-h-screen flex">

    <?= $this->include('dashboard/template/sidebar'); ?>

    <div class="flex flex-col flex-1 min-h-screen relative" id="page-content">
        
        <?= $this->include('dashboard/template/header'); ?>

        <main class="p-4 flex-1 overflow-y-auto">
            <?= $this->renderSection('content'); ?>
        </main>

        <?= $this->include('dashboard/template/footer'); ?>
    </div>


    <script src="<?=base_url('assets/js/firebase/index.js')?>" type="module"></script>
    <?= $this->renderSection('scripts') ?>
    <script>let base_url = <?= json_encode(rtrim(base_url(), '/')); ?>;</script>
    <script src="<?= base_url('assets/js/clock.js') ?>"></script>
    <script src="<?= base_url('assets/js/dashboard.js') ?>" type="module"></script>
    <script src="<?= base_url('assets/js/header.js') ?>" type="module"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"></script>
    <script src="<?= base_url('assets/php/firebase-config.js.php') ?>" ></script>

</body>
</html>