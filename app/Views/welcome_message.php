<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeMaster - Kelola Waktu & Aktivitas Anda dengan Efisien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 text-gray-900">
<?php $request = service('request'); ?>

<?php if ($request->getGet('logout')): ?>
    <div id="logoutAlert" class="p-3 bg-green-100 text-green-700 rounded-lg">
        Anda telah berhasil logout.
    </div>
<?php endif; ?>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-50 via-indigo-100 to-purple-50 py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Background Pattern for Variety -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="#4F46E5" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                Kelola Waktu & Aktivitas Anda dengan <span class="text-blue-600">Efisien</span>
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                RoutinePath: Rencanakan, Lacak, Analisis. Tingkatkan produktivitas Anda hari ini!
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-8">
                <a href="#features" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    Jelajahi Fitur
                </a>
                <a href="<?= base_url('register') ?>" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200">
                    Mulai Sekarang
                </a>
            </div>
            <!-- Upgraded SVG Ilustrasi Interaktif with More Realism and Animation -->
            <div x-data="{ show: false, animate: false }" class="mt-12 flex justify-center">
                <div class="relative">
                    <svg class="w-64 h-64 sm:w-80 sm:h-80" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg" x-init="setTimeout(() => animate = true, 500)">
                        <!-- Background Glow for Realism -->
                        <defs>
                            <filter id="glow">
                                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                <feMerge> 
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        <!-- Enhanced Calendar with More Details -->
                        <rect x="20" y="40" width="260" height="200" rx="15" fill="#F3F4F6" stroke="#4F46E5" stroke-width="3" filter="url(#glow)"/>
                        <text x="150" y="65" text-anchor="middle" font-size="16" fill="#374151" font-weight="bold">Kalender</text>
                        <!-- Days with Colors and Icons (7 days: Mon to Sun, centered) -->
                        <rect x="40" y="80" width="25" height="25" fill="#10B981" rx="5"/><text x="52.5" y="95" text-anchor="middle" font-size="10" fill="white">M</text>
                        <rect x="70" y="80" width="25" height="25" fill="#F59E0B" rx="5"/><text x="82.5" y="95" text-anchor="middle" font-size="10" fill="white">T</text>
                        <rect x="100" y="80" width="25" height="25" fill="#EF4444" rx="5"/><text x="112.5" y="95" text-anchor="middle" font-size="10" fill="white">W</text>
                        <rect x="130" y="80" width="25" height="25" fill="#8B5CF6" rx="5"/><text x="142.5" y="95" text-anchor="middle" font-size="10" fill="white">T</text>
                        <rect x="160" y="80" width="25" height="25" fill="#06B6D4" rx="5"/><text x="172.5" y="95" text-anchor="middle" font-size="10" fill="white">F</text>
                        <rect x="190" y="80" width="25" height="25" fill="#EC4899" rx="5"/><text x="202.5" y="95" text-anchor="middle" font-size="10" fill="white">S</text>
                        <rect x="220" y="80" width="25" height="25" fill="#14B8A6" rx="5"/><text x="232.5" y="95" text-anchor="middle" font-size="10" fill="white">S</text>
                        <!-- Clock Icon for Time Management -->
                        <circle cx="150" cy="130" r="20" fill="#4F46E5" filter="url(#glow)"/>
                        <path d="M150 115 L150 130 L160 140" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <text x="150" y="135" text-anchor="middle" font-size="10" fill="white"></text>
                        <!-- Activity Icons with Subtle Animation -->
                        <circle cx="220" cy="130" r="20" fill="#10B981" filter="url(#glow)" :class="{ 'animate-pulse': animate }"/>
                        <text x="220" y="135" text-anchor="middle" font-size="12" fill="white">✓</text>
                        <circle cx="80" cy="160" r="20" fill="#F59E0B" filter="url(#glow)" :class="{ 'animate-pulse': animate }"/>
                        <text x="80" y="165" text-anchor="middle" font-size="12" fill="white">!</text>
                        <!-- Connecting Lines for Flow -->
                        <line x1="150" y1="150" x2="220" y2="150" stroke="#4F46E5" stroke-width="2" stroke-dasharray="5,5"/>
                        <line x1="150" y1="150" x2="80" y2="180" stroke="#4F46E5" stroke-width="2" stroke-dasharray="5,5"/>
                    </svg>
                    <button @click="show = !show" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-2 rounded-full text-sm hover:bg-blue-700 transition shadow-lg">
                        Klik untuk Detail
                    </button>
                    <div x-show="show" x-transition class="absolute top-0 left-0 w-full h-full bg-white bg-opacity-95 rounded-lg p-4 flex items-center justify-center shadow-xl">
                        <p class="text-sm text-gray-700 text-center">Visualisasikan jadwal Anda dengan kalender interaktif, penanda aktivitas, ikon waktu, dan animasi alur. Geser untuk navigasi dan lihat detail tugas!</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Features Section dengan Accordion -->
    <section id="features" class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-100 to-gray-200">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-12">
                Fitur Unggulan – Klik untuk Jelajahi!
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Fitur 1: Aktivitas -->
                <div x-data="{ open: false }" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Aktivitas Pintar</h3>
                        </div>
                        <button @click="open = !open" class="text-blue-600 hover:text-blue-800">
                            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-4">Buat aktivitas satu kali atau rutin dengan prioritas dan tracking status.</p>
                    <div x-show="open" x-transition class="text-sm text-gray-500 space-y-1">
                        <p>• Aktivitas satu kali atau berulang</p>
                        <p>• Prioritas tinggi/sedang/rendah</p>
                        <p>• Catatan task dengan mark done</p>
                        <p>• Reset otomatis harian/mingguan</p>
                    </div>
                </div>

                <!-- Fitur 2: Kalender Interaktif -->
                <div x-data="{ open: false }" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Kalender Interaktif</h3>
                        </div>
                        <button @click="open = !open" class="text-green-600 hover:text-green-800">
                            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-4">Lihat jadwal dalam kalender dengan penanda aktivitas.</p>
                    <div x-show="open" x-transition class="text-sm text-gray-500 space-y-1">
                        <p>• Tampilan kalender bulanan/mingguan</p>
                        <p>• Penanda warna untuk prioritas</p>
                        <p>• Klik untuk detail dan edit</p>
                        <p>• Hindari konflik jadwal</p>
                    </div>
                </div>

                <!-- Fitur 3: Note -->
                <div x-data="{ open: false }" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Catatan Rich Text</h3>
                        </div>
                        <button @click="open = !open" class="text-purple-600 hover:text-purple-800">
                            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-4">Buat catatan dengan format teks kaya dan hubungkan aktivitas.</p>
                    <div x-show="open" x-transition class="text-sm text-gray-500 space-y-1">
                        <p>• Format bold, italic, list, dll.</p>
                        <p>• Hubungkan dengan aktivitas</p>
                        <p>• Untuk ide atau renungan</p>
                        <p>• Akses kapan saja</p>
                    </div>
                </div>

                <!-- Fitur 4: Summary -->
                <div x-data="{ open: false }" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Ringkasan Komprehensif</h3>
                        </div>
                        <button @click="open = !open" class="text-orange-600 hover:text-orange-800">
                            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-4">Lihat ringkasan aktivitas dengan filter periode.</p>
                    <div x-show="open" x-transition class="text-sm text-gray-500 space-y-1">
                        <p>• Ringkasan done, late, upcoming</p>
                        <p>• Filter bulan, tahun, dll.</p>
                        <p>• Analisis performa</p>
                        <p>• Identifikasi pola</p>
                    </div>
                </div>

                <!-- Fitur 5: Insight -->
                <div x-data="{ open: false }" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Insight Pintar</h3>
                        </div>
                        <button @click="open = !open" class="text-teal-600 hover:text-teal-800">
                            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-4">Dapatkan analisis untuk introspeksi dan perbaikan.</p>
                    <div x-show="open" x-transition class="text-sm text-gray-500 space-y-1">
                        <p>• Analisis penyelesaian & tren</p>
                        <p>• Saran actionable</p>
                        <p>• Data-driven insights</p>
                        <p>• Tingkatkan produktivitas</p>
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-200 col-span-1 md:col-span-2 lg:col-span-3">
                    <h3 class="text-xl font-semibold mb-4">Siap Tingkatkan Produktivitas Anda?</h3>
                    <p class="mb-6">Bergabunglah dengan ribuan pengguna yang telah mengubah cara mereka mengelola waktu. Mulai gratis hari ini!</p>
                    <a href="<?= base_url('register') ?>" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-gray-400">&copy; 2025 RoutinePath. Semua hak cipta dilindungi. Dibuat untuk produktivitas Anda.</p>
        </div>
    </footer>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Hilangkan parameter logout=1 dari URL
    if (window.location.search.includes("logout=1")) {
        const newURL = window.location.origin + window.location.pathname;
        history.replaceState({}, document.title, newURL);
    }

    // Auto-hide alert after 3s
    setTimeout(() => {
        const alert = document.getElementById("logoutAlert");
        if (alert) alert.style.display = "none";
    }, 3000);
});

</script>
</body>
</html>
