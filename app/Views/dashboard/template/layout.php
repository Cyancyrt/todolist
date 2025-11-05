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
<body class="bg-blue-50 font-sans">
    
    <?= $this->include('dashboard/template/header'); ?>
    <div class="flex min-h-screen">

     <?= $this->include('dashboard/template/sidebar'); ?>
     
     <?= $this->renderSection('content'); ?>
     
    </div>
    <?= $this->include('dashboard/template/footer'); ?>
    <?= $this->renderSection('scripts') ?>
    
    <script src="<?= base_url('assets/js/sidebar.js') ?>"></script>
    <script src="<?= base_url('assets/js/clock.js') ?>"></script>
    <script src="<?= base_url('assets/js/dashboard.js') ?>" type="module"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"></script>
    <script src="<?=base_url('assets/js/firebase/index.js')?>" type="module"></script>
    <script src="<?= base_url('assets/php/firebase-config.js.php') ?>" ></script>



</body>
</html>
