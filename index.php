<?php
require_once __DIR__ . '/config/database.php';
$page_title = "Beranda - Divisi Teknik Kepramukaan";
require_once __DIR__ . '/includes/header.php';

// Ambil paket hemat untuk ditampilkan di highlight beranda
$stmtPaket = $pdo->query("SELECT * FROM inventory WHERE kategori = 'paket' AND is_active = 1 ORDER BY harga DESC");
$paket_list = $stmtPaket->fetchAll();

// Ambil beberapa materi simpul unggulan
$stmtSimpul = $pdo->query("SELECT * FROM tutorials WHERE kategori IN ('simpul', 'ikatan', 'semaphore') ORDER BY id ASC LIMIT 3");
$simpul_list = $stmtSimpul->fetchAll();
?>

<!-- Hero Section Bersih & Modern -->
<section class="bg-scout-900 text-white py-16 sm:py-20 border-b border-scout-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Teks Hero -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-scout-800 border border-scout-700 text-stone-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Divisi Teknik Kepramukaan (Tekpram)
                </div>

                <h1 class="font-bold text-3xl sm:text-4xl lg:text-5xl tracking-tight leading-tight text-white">
                    Pusat Materi Simpul & <br class="hidden sm:inline">
                    <span class="text-amber-400">Peminjaman Alat Sanggar</span>
                </h1>

                <p class="text-stone-300 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Solusi praktis bagi anggota dan regu pramuka: pelajari kembali teknik simpul dan pionering yang terlupa, atau sewa perlengkapan tenda dan tongkat dengan estimasi harga transparan via WhatsApp.
                </p>

                <!-- Tombol Navigasi Cepat -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3 pt-2">
                    <a href="sewa.php" class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-sm transition">
                        <span>Cek Harga & Sewa Alat</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="tutorial.php" class="inline-flex items-center gap-2 bg-stone-800 hover:bg-stone-700 text-stone-200 font-bold text-sm px-6 py-3 rounded-xl border border-stone-700 transition">
                        <i class="fa-solid fa-book-open text-xs text-amber-400"></i>
                        <span>Buka Tutorial Simpul</span>
                    </a>
                </div>

                <!-- Info Singkat -->
                <div class="pt-6 border-t border-scout-800 flex flex-wrap justify-center lg:justify-start gap-8 text-xs text-stone-400">
                    <div>
                        <span class="text-white font-bold block text-base">Tarif Harian</span>
                        <span>Mulai Rp 1.000 / pcs</span>
                    </div>
                    <div>
                        <span class="text-white font-bold block text-base">3 Paket Hemat</span>
                        <span>Bundling siap kemah & latihan</span>
                    </div>
                    <div>
                        <span class="text-white font-bold block text-base">Pesan Langsung</span>
                        <span>Konfirmasi via WhatsApp</span>
                    </div>
                </div>
            </div>

            <!-- Card Pilihan Paket Cepat -->
            <div class="lg:col-span-5">
                <div class="bg-scout-800/90 rounded-2xl p-6 border border-scout-700 shadow-xl space-y-4">
                    <div class="flex items-center justify-between border-b border-scout-700 pb-3">
                        <h3 class="font-bold text-white text-sm">Paket Sewa Populer</h3>
                        <span class="text-emerald-400 text-xs font-semibold">Tersedia di Sanggar</span>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-scout-900/80 p-4 rounded-xl border border-scout-700">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-white text-sm">Paket Kemah Hemat</span>
                                <span class="text-amber-400 font-bold text-sm">Rp 90.000</span>
                            </div>
                            <p class="text-xs text-stone-400 mb-2">Tenda prisma, 10 pasak kayu, 2 tali 10m, 2 tongkat (3 hari).</p>
                            <a href="sewa.php?tambah_paket=11" class="text-xs font-semibold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1">
                                Pilih Paket Ini &rarr;
                            </a>
                        </div>

                        <div class="bg-scout-900/80 p-4 rounded-xl border border-scout-700">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-white text-sm">Paket Pionering Mini</span>
                                <span class="text-amber-400 font-bold text-sm">Rp 30.000</span>
                            </div>
                            <p class="text-xs text-stone-400 mb-2">7 tongkat pramuka, 5 tali pramuka 5 meter (2 hari).</p>
                            <a href="sewa.php?tambah_paket=12" class="text-xs font-semibold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1">
                                Pilih Paket Ini &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="sewa.php" class="block text-center w-full bg-scout-700 hover:bg-scout-600 text-stone-200 text-xs font-semibold py-2.5 rounded-lg transition border border-scout-600">
                            Lihat Semua 10 Barang Satuan &rarr;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3 Pilar Menu Utama -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Kartu 1: Sewa Alat -->
        <a href="sewa.php" class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-stone-200 transition group">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg mb-3 group-hover:bg-amber-100 transition">
                <i class="fa-solid fa-tent"></i>
            </div>
            <h3 class="font-bold text-base text-stone-900 group-hover:text-amber-700 transition">Sewa Inventaris Sanggar</h3>
            <p class="text-xs text-stone-600 mt-1 leading-relaxed">
                Tenda prisma, tenda dome, pasak, tongkat, tali, kacu, dan semaphore dengan tarif sewa harian.
            </p>
            <div class="mt-4 text-xs font-bold text-amber-700 flex items-center gap-1">
                <span>Cek Katalog & Hitung Biaya</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </div>
        </a>

        <!-- Kartu 2: Bank Simpul & Pionering -->
        <a href="tutorial.php" class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-stone-200 transition group">
            <div class="w-10 h-10 rounded-xl bg-stone-100 text-stone-800 flex items-center justify-center text-lg mb-3 group-hover:bg-stone-200 transition">
                <i class="fa-solid fa-knot"></i>
            </div>
            <h3 class="font-bold text-base text-stone-900 group-hover:text-stone-700 transition">Tutorial Simpul & Pionering</h3>
            <p class="text-xs text-stone-600 mt-1 leading-relaxed">
                Lupa cara membuat simpul pangkal, jangkar, atau ikatan palang? Panduan langkah demi langkah tersedia di sini.
            </p>
            <div class="mt-4 text-xs font-bold text-stone-800 flex items-center gap-1">
                <span>Buka Panduan Simpul</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </div>
        </a>

        <!-- Kartu 3: Sandi & Semaphore -->
        <a href="tutorial.php?kategori=semaphore" class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-stone-200 transition group">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg mb-3 group-hover:bg-emerald-100 transition">
                <i class="fa-solid fa-flag"></i>
            </div>
            <h3 class="font-bold text-base text-stone-900 group-hover:text-emerald-700 transition">Semaphore & Sandi</h3>
            <p class="text-xs text-stone-600 mt-1 leading-relaxed">
                Panduan praktis alfabet bendera semaphore, sandi morse peluit/senter, sandi rumput, dan sandi kotak.
            </p>
            <div class="mt-4 text-xs font-bold text-emerald-700 flex items-center gap-1">
                <span>Pelajari Isyarat Lapangan</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </div>
        </a>

    </div>
</section>

<!-- Pilihan Paket Sewa -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
        <div>
            <h2 class="font-bold text-xl sm:text-2xl text-stone-900">
                Pilihan Paket Hemat Sanggar
            </h2>
            <p class="text-xs sm:text-sm text-stone-600 mt-0.5">
                Bundling perlengkapan kemah dan latihan siap pakai dengan harga lebih ekonomis.
            </p>
        </div>
        <a href="sewa.php" class="text-xs font-bold text-amber-700 hover:text-amber-800">
            Lihat Semua Alat Satuan &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php foreach ($paket_list as $pkt): ?>
            <div class="bg-white rounded-2xl border border-stone-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[11px] font-semibold text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded border border-amber-200">
                            Durasi <?= $pkt['durasi_hari'] ?> Hari
                        </span>
                        <span class="text-xs text-stone-400 font-medium">Paket Bundling</span>
                    </div>

                    <h3 class="font-bold text-base text-stone-900 mb-1">
                        <?= htmlspecialchars($pkt['nama_barang']) ?>
                    </h3>

                    <div class="font-extrabold text-xl text-stone-900 mb-3">
                        <?= format_rupiah($pkt['harga']) ?>
                        <span class="text-xs font-normal text-stone-500">/ <?= $pkt['durasi_hari'] ?> hari</span>
                    </div>

                    <div class="border-t border-stone-100 pt-3 mb-5">
                        <p class="text-xs font-semibold text-stone-700 mb-2">Kelengkapan:</p>
                        <ul class="space-y-1.5 text-xs text-stone-600">
                            <?php 
                            $items_paket = explode("\n", $pkt['rincian_paket']);
                            foreach ($items_paket as $item_p): 
                                if (trim($item_p) === '') continue;
                            ?>
                                <li class="flex items-start gap-2">
                                    <i class="fa-solid fa-check text-emerald-600 text-xs mt-0.5"></i>
                                    <span><?= htmlspecialchars(trim($item_p)) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <a href="sewa.php?tambah_paket=<?= $pkt['id'] ?>" class="w-full text-center bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2.5 px-4 rounded-xl transition">
                    Pilih Paket Ini
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Tutorial Simpul Unggulan -->
<section class="bg-stone-100 py-14 border-t border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-stone-900">
                    Materi Simpul Paling Sering Digunakan
                </h2>
                <p class="text-xs sm:text-sm text-stone-600 mt-0.5">
                    Dasar keterampilan pionering dan tenda di lapangan.
                </p>
            </div>
            <a href="tutorial.php" class="text-xs font-bold text-amber-700 hover:text-amber-800">
                Buka Semua Panduan &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <?php foreach ($simpul_list as $simpul): ?>
                <div class="bg-white rounded-2xl border border-stone-200 p-6 flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-stone-100 text-stone-700">
                                Tingkat <?= htmlspecialchars(ucfirst($simpul['tingkat_kesulitan'])) ?>
                            </span>
                            <span class="text-xs text-stone-400 capitalize">
                                <?= htmlspecialchars($simpul['kategori']) ?>
                            </span>
                        </div>
                        <h3 class="font-bold text-base text-stone-900 mb-2">
                            <?= htmlspecialchars($simpul['judul']) ?>
                        </h3>
                        <p class="text-xs text-stone-600 leading-relaxed mb-4 line-clamp-3">
                            <?= htmlspecialchars($simpul['deskripsi']) ?>
                        </p>
                    </div>

                    <a href="tutorial-detail.php?id=<?= $simpul['id'] ?>" class="text-xs font-bold text-amber-700 hover:text-amber-800 inline-flex items-center gap-1">
                        <span>Baca Langkah Pembuatan</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kontak & Syarat Sewa -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-scout-900 rounded-2xl p-6 sm:p-10 text-white border border-scout-800 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <h3 class="font-bold text-xl sm:text-2xl text-white">
                Butuh Peminjaman Alat untuk Regu atau Gudep?
            </h3>
            <p class="text-xs sm:text-sm text-stone-300 max-w-xl leading-relaxed">
                Silakan pilih perlengkapan di halaman sewa, cek total biaya harian, dan ajukan langsung lewat WhatsApp admin sanggar.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $wa_admin) ?>" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-3 rounded-xl transition">
                <i class="fa-brands fa-whatsapp text-base"></i>
                <span>Hubungi: <?= htmlspecialchars($wa_admin) ?></span>
            </a>
            <a href="sewa.php" class="inline-flex items-center gap-2 bg-stone-800 hover:bg-stone-700 text-stone-200 font-bold text-xs px-5 py-3 rounded-xl border border-stone-700 transition">
                <span>Katalog Alat</span>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
