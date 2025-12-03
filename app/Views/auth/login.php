<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Aplikasi Aktivitas Rutin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        /* Style tambahan untuk posisi icon mata */
        .password-container { position: relative; }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280; /* Gray-500 */
        }
        .toggle-password:hover { color: #3b82f6; /* Blue-500 */ }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-md p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <i class="fas fa-home text-4xl text-blue-500 mb-4"></i>
            <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang Kembali!</h2>
            <p class="text-gray-600 text-sm">Lanjutkan rutinitas Anda dan lihat perkembangan mingguan Anda.</p>
            <?php if (session()->get('isLoggedIn')): ?>
                <p class="text-green-600 text-sm mt-2">Halo, <?php echo session()->get('name'); ?>! Anda sudah login.</p>
            <?php endif; ?>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <?php echo session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('/auth', ['class' => 'space-y-4']); ?>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <!-- Menggunakan savedEmail (dari cookie) jika ada, jika tidak gunakan set_value (input lama) -->
                <input type="email" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       id="email" 
                       name="email" 
                       value="<?php echo esc($savedEmail ?? set_value('email')); ?>" 
                       required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="password-container mt-1">
                    <input type="password" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-10" 
                           id="password" 
                           name="password" 
                           required>
                    <!-- Ikon Mata -->
                    <i class="fas fa-eye toggle-password" id="togglePasswordBtn"></i>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <!-- Checkbox Remember Me -->
                    <input type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                           id="remember" 
                           name="remember" 
                           value="1" 
                           <?php echo !empty($savedEmail) ? 'checked' : ''; ?>>
                    <label for="remember" class="ml-2 block text-sm text-gray-900 cursor-pointer">Ingat Saya</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Lupa password?</a>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                Masuk
            </button>
        <?php echo form_close(); ?>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">Belum punya akun? <a href="<?php echo base_url('/register'); ?>" class="text-blue-600 hover:text-blue-500 font-medium">Daftar di sini</a></p>
        </div>
    </div>

    <!-- Script Javascript untuk Show/Hide Password -->
    <script>
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', function () {
            // Toggle tipe atribut antara password dan text
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle ikon mata (slash vs normal)
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>