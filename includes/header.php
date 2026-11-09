<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/database.php';
}

$page_title = isset($page_title) ? $page_title . " - Tekpram" : "Teknik Kepramukaan (Tekpram)";
$wa_admin = get_setting($pdo, 'whatsapp_number', '085851635670');
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        scout: {
                            900: '#26170F', // Earthy deep scout brown
                            800: '#3B2317',
                            700: '#523422',
                            600: '#6E452E',
                            500: '#8E5A3C',
                            300: '#CDB38B',
                            200: '#E2D4BE',
                            100: '#F2ECE1',
                            50: '#FAF7F2',
                            gold: '#C87A18',
                            goldlight: '#E29738'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F6F0;
            color: #2D231C;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-amber-100 selection:text-amber-900">

    <!-- Top Bar -->
    <div class="bg-scout-900 text-stone-300 text-xs py-2 px-4 border-b border-scout-800">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center space-x-3">
                <span class="font-semibold text-amber-300">
                    Satyaku Kudarmakan, Darmaku Kubaktikan
                </span>
                <span class="hidden md:inline text-stone-600">•</span>
                <span class="hidden md:inline text-stone-400">Divisi Teknik Kepramukaan</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $wa_admin) ?>" target="_blank" class="hover:text-white transition flex items-center gap-1.5 font-medium">
                    <i class="fa-brands fa-whatsapp text-emerald-400"></i> Sanggar: <?= htmlspecialchars($wa_admin) ?>
                </a>
                <span class="text-stone-700">|</span>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="admin/dashboard.php" class="bg-amber-600 hover:bg-amber-700 text-white px-2.5 py-0.5 rounded text-[11px] font-bold transition">
                        Panel Pengurus
                    </a>
                <?php else: ?>
                    <a href="admin/login.php" class="text-stone-400 hover:text-white transition flex items-center gap-1 text-[11px]">
                        <i class="fa-solid fa-lock text-[10px]"></i> Area Pengurus
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand (Ambalan Bisma Srikandi SMKN 1 Probolinggo) -->
                <a href="index.php" class="flex items-center gap-3 group">
                    <img src="assets/images/logo.png" alt="Logo Ambalan Bisma Srikandi" class="w-11 h-11 object-contain drop-shadow-sm group-hover:scale-105 transition-transform">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-base sm:text-lg tracking-tight text-stone-900">TEKPRAM BISMA-SRIKANDI</span>
                            <span class="bg-amber-100 text-amber-900 text-[10px] font-bold px-1.5 py-0.2 rounded">04.001-002</span>
                        </div>
                        <p class="text-[11px] text-stone-500 font-medium">SMK Negeri 1 Probolinggo</p>
                    </div>
                </a>

                <!-- Desktop Menu (Tanpa Arsip Dokumen Publik) -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="index.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'index.php' ? 'bg-stone-100 text-stone-900 font-bold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50' ?>">
                        Beranda
                    </a>
                    <a href="sewa.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition relative <?= $current_page == 'sewa.php' ? 'bg-amber-50 text-amber-900 font-bold border border-amber-200' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50' ?>">
                        Sewa Inventaris
                    </a>
                    <a href="tutorial.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= ($current_page == 'tutorial.php' || $current_page == 'tutorial-detail.php') ? 'bg-stone-100 text-stone-900 font-bold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50' ?>">
                        Tutorial Simpul & Sandi
                    </a>
                </nav>

                <!-- CTA Action Button -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="sewa.php" class="inline-flex items-center gap-2 bg-scout-800 hover:bg-scout-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                        <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                        <span>Pesan via WA</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center">
                    <button type="button" id="mobile-menu-toggle" class="p-2 rounded-lg text-stone-700 hover:bg-stone-100" aria-label="Toggle Menu">
                        <i class="fa-solid fa-bars text-xl" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown (Tanpa Arsip Publik) -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-stone-200 bg-white px-4 pt-3 pb-5 space-y-1.5 shadow-lg">
            <a href="index.php" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= $current_page == 'index.php' ? 'bg-stone-100 text-stone-900 font-bold' : 'text-stone-700 hover:bg-stone-50' ?>">
                Beranda
            </a>
            <a href="sewa.php" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= $current_page == 'sewa.php' ? 'bg-stone-100 text-stone-900 font-bold' : 'text-stone-700 hover:bg-stone-50' ?>">
                Sewa Inventaris Sanggar
            </a>
            <a href="tutorial.php" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= ($current_page == 'tutorial.php' || $current_page == 'tutorial-detail.php') ? 'bg-stone-100 text-stone-900 font-bold' : 'text-stone-700 hover:bg-stone-50' ?>">
                Tutorial Simpul & Sandi
            </a>
            <div class="pt-2 border-t border-stone-100">
                <a href="sewa.php" class="w-full flex items-center justify-center gap-2 bg-scout-800 text-white text-xs font-bold py-2.5 rounded-xl">
                    <i class="fa-brands fa-whatsapp text-emerald-400"></i> Cek Sewa & Chat WA
                </a>
            </div>
        </div>
    </header>

    <script>
        const menuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                if (mobileMenu.classList.contains('hidden')) {
                    menuIcon.classList.remove('fa-xmark');
                    menuIcon.classList.add('fa-bars');
                } else {
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-xmark');
                }
            });
        }
    </script>

    <main class="flex-grow">
