<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Aplikasi Aktivitas Rutin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #333;
        }
        .register-card {
            animation: fadeInUp 0.8s ease-out;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-group {
            animation: slideIn 0.6s ease-out forwards;
            opacity: 0;
        }
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.4s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        .form-group:nth-child(4) { animation-delay: 0.8s; }
        .form-group:nth-child(5) { animation-delay: 1s; }
        @keyframes slideIn {
            to { opacity: 1; transform: translateY(0); }
        }
        .progress-bar-custom {
            background: linear-gradient(90deg, #007bff, #28a745);
            transition: width 0.3s ease; /* Lebih cepat untuk responsivitas */
        }
        .icon-check {
            animation: bounceIn 0.5s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .typewriter {
            overflow: hidden;
            border-right: 2px solid #007bff;
            white-space: nowrap;
            animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #007bff; }
        }
        .btn-submit:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        .spinner {
            display: none;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card register-card p-4" style="max-width: 450px; width: 100%;">
            <div class="text-center mb-4">
                <h2 class="h4 text-dark typewriter">Bergabunglah, Catat Aktivitas Anda!</h2>
                <p class="text-muted small">Seperti menyelesaikan tugas harian, langkah ini akan membawa Anda ke rutinitas yang lebih baik.</p>
            </div>
            <!-- Progress Indicator Unik -->
            <div class="mb-3">
                <label class="form-label small">Progress Pendaftaran</label>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar progress-bar-custom" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted" id="progress-text">Langkah 0 dari 4: Mulai isi data</small>
            </div>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger animate__animated animate__fadeIn"><?php echo session()->getFlashdata('error'); ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success animate__animated animate__fadeIn"><?php echo session()->getFlashdata('success'); ?></div>
            <?php endif; ?>
            <form action="<?= site_url('/register') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?> <!-- CSRF untuk CI4 -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nama Lengkap <i class="fas fa-user-check text-success icon-check" id="name-check" style="display: none;"></i></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Nama harus diisi.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email <i class="fas fa-envelope text-success icon-check" id="email-check" style="display: none;"></i></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Email harus diisi dan valid.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password <i class="fas fa-lock text-success icon-check" id="password-check" style="display: none;"></i></label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    <div class="invalid-feedback">Password minimal 6 karakter.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="confirmPassword" class="form-label">Konfirmasi Password <i class="fas fa-lock text-success icon-check" id="confirm-check" style="display: none;"></i></label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    <div class="invalid-feedback">Konfirmasi password tidak cocok.</div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label small" for="terms">Saya setuju dengan <a href="#" class="text-primary">Syarat & Ketentuan</a>.</label>
                    <div class="invalid-feedback">Anda harus menyetujui syarat.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-submit" id="submit-btn">
                    <span id="btn-text">Daftar Sekarang</span>
                    <i class="fas fa-spinner spinner" id="spinner"></i>
                </button>
            </form>
            <div class="text-center mt-3">
                <p class="small text-muted">Sudah punya akun? <a href="<?= base_url('/login') ?>" class="text-primary">Masuk di sini</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi dan animasi sederhana
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.needs-validation');
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]'); // Hanya field utama (4 field)
            const progressBar = document.querySelector('.progress-bar');
            const progressText = document.getElementById('progress-text');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('spinner');

            function updateProgress() {
                const filledCount = Array.from(inputs).filter(inp => inp.value.trim() !== '').length;
                const progressPercent = (filledCount * 25); // 25% per field
                progressBar.style.width = progressPercent + '%';
                progressBar.setAttribute('aria-valuenow', progressPercent);
                progressText.textContent = `Langkah ${filledCount} dari 4: ${filledCount === 4 ? 'Siap daftar!' : 'Isi data'}`;
            }

            // Update progress setiap input berubah
            inputs.forEach(input => {
                input.addEventListener('input', updateProgress);
            });

            // Ikon check saat valid (terpisah dari progress)
            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    const checkIcon = document.getElementById(input.name + '-check');
                    if (input.checkValidity()) {
                        checkIcon.style.display = 'inline';
                    } else {
                        checkIcon.style.display = 'none';
                    }
                });
            });

            // Animasi submit
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    btnText.style.display = 'none';
                    spinner.style.display = 'inline';
                    submitBtn.disabled = true; // Prevent double submit
                }
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>
