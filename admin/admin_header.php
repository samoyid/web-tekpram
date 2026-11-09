<?php
require_once __DIR__ . '/auth_check.php';
$current_page = basename($_SERVER['PHP_SELF']);
$admin_title = isset($admin_title) ? $admin_title . " - Admin Tekpram" : "Panel Pengurus Tekpram";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($admin_title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        scout: {
                            900: '#26170F',
                            800: '#3B2317',
                            700: '#523422',
                            600: '#6E452E'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F6F0; }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row text-stone-800">

    <!-- Sidebar Admin -->
    <aside class="w-full lg:w-60 bg-scout-900 text-white flex-shrink-0 flex flex-col justify-between border-r border-scout-800">
        <div>
            <!-- Brand -->
            <div class="p-4 border-b border-scout-800 flex items-center gap-3">
                <img src="../assets/images/logo.png" alt="Logo Ambalan" class="w-9 h-9 object-contain flex-shrink-0">
                <div>
                    <h2 class="font-bold text-sm text-white tracking-wide">TEKPRAM</h2>
                    <p class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold">Bisma - Srikandi</p>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="px-5 py-3.5 bg-scout-800/50 border-b border-scout-800">
                <div class="text-[10px] text-stone-400">Login sebagai:</div>
                <div class="font-bold text-xs text-white truncate"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></div>
                <span class="inline-block px-1.5 py-0.2 mt-1 bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[9px] font-bold rounded">
                    <?= ucfirst($_SESSION['role']) ?>
                </span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1 text-xs font-medium">
                <a href="dashboard.php" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition <?= $current_page === 'dashboard.php' ? 'bg-amber-600 text-white font-bold' : 'text-stone-300 hover:bg-scout-800 hover:text-white' ?>">
                    <i class="fa-solid fa-gauge-high text-xs w-4"></i>
                    <span>Dashboard</span>
                </a>

                <a href="inventaris.php" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition <?= $current_page === 'inventaris.php' ? 'bg-amber-600 text-white font-bold' : 'text-stone-300 hover:bg-scout-800 hover:text-white' ?>">
                    <i class="fa-solid fa-tent text-xs w-4"></i>
                    <span>Kelola Inventaris</span>
                </a>

                <a href="materi.php" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition <?= $current_page === 'materi.php' ? 'bg-amber-600 text-white font-bold' : 'text-stone-300 hover:bg-scout-800 hover:text-white' ?>">
                    <i class="fa-solid fa-knot text-xs w-4"></i>
                    <span>Bank Simpul</span>
                </a>

                <a href="arsip.php" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition <?= $current_page === 'arsip.php' ? 'bg-amber-600 text-white font-bold' : 'text-stone-300 hover:bg-scout-800 hover:text-white' ?>">
                    <i class="fa-solid fa-folder-open text-xs w-4"></i>
                    <span>Arsip Laporan & Kas</span>
                </a>

                <a href="pengaturan.php" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition <?= $current_page === 'pengaturan.php' ? 'bg-amber-600 text-white font-bold' : 'text-stone-300 hover:bg-scout-800 hover:text-white' ?>">
                    <i class="fa-solid fa-sliders text-xs w-4"></i>
                    <span>Pengaturan WA</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Links -->
        <div class="p-3 border-t border-scout-800 space-y-1">
            <a href="../index.php" target="_blank" class="flex items-center gap-2 text-xs text-stone-400 hover:text-white px-3 py-2 rounded-lg hover:bg-scout-800 transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                <span>Tampilan Publik</span>
            </a>
            <a href="logout.php" onclick="return confirm('Keluar dari panel pengurus?')" class="flex items-center gap-2 text-xs text-red-400 hover:text-red-300 px-3 py-2 rounded-lg hover:bg-red-950/40 transition">
                <i class="fa-solid fa-power-off text-xs"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Canvas -->
    <main class="flex-1 p-6 sm:p-8 max-w-6xl mx-auto w-full">
