<?php
$admin_title = "Dashboard Pengurus - Tekpram";
require_once __DIR__ . '/admin_header.php';

// Ambil Statistik
$countInv = $pdo->query("SELECT COUNT(*) as cnt FROM inventory WHERE kategori = 'satuan'")->fetch()['cnt'];
$countPaket = $pdo->query("SELECT COUNT(*) as cnt FROM inventory WHERE kategori = 'paket'")->fetch()['cnt'];
$countTut = $pdo->query("SELECT COUNT(*) as cnt FROM tutorials")->fetch()['cnt'];
$countArc = $pdo->query("SELECT COUNT(*) as cnt FROM archives")->fetch()['cnt'];
$totalKas = $pdo->query("SELECT SUM(nominal_kas) as total FROM archives WHERE kategori = 'keuangan'")->fetch()['total'] ?? 0;

$wa_admin = get_setting($pdo, 'whatsapp_number', '085851635670');

// Ambil 5 barang inventaris terbaru
$stmtLatestInv = $pdo->query("SELECT * FROM inventory ORDER BY id DESC LIMIT 5");
$latest_inv = $stmtLatestInv->fetchAll();
?>

<div class="space-y-6">
    
    <!-- Top Welcome Banner -->
    <div class="bg-scout-900 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 border border-scout-800">
        <div class="space-y-1.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-scout-800 text-stone-300 text-xs font-semibold">
                Divisi Teknik Kepramukaan
            </span>
            <h1 class="font-bold text-2xl sm:text-3xl text-white">
                Selamat Datang, Anggota Tekpram
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 max-w-xl leading-relaxed">
                Kelola data inventaris sewa sanggar, arsipkan laporan kegiatan & kas, serta perbarui bank simpul kepramukaan.
            </p>
        </div>

        <div class="bg-scout-800 p-4 rounded-xl border border-scout-700 flex items-center gap-3">
            <i class="fa-brands fa-whatsapp text-emerald-400 text-2xl"></i>
            <div>
                <div class="text-[10px] text-stone-400 uppercase font-semibold">Tujuan Pesanan WA:</div>
                <div class="font-bold text-white text-sm"><?= htmlspecialchars($wa_admin) ?></div>
                <a href="pengaturan.php" class="text-[11px] text-amber-300 hover:underline">Ubah Nomor Kontak</a>
            </div>
        </div>
    </div>

    <!-- 4 Cards Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-xl p-4 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-stone-500 font-medium">Barang Satuan</span>
                <div class="font-bold text-xl text-stone-900 mt-0.5"><?= $countInv ?> Alat</div>
                <a href="inventaris.php" class="text-[11px] text-amber-700 font-semibold hover:underline">Kelola barang &rarr;</a>
            </div>
            <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-tent"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-stone-500 font-medium">Paket Hemat</span>
                <div class="font-bold text-xl text-stone-900 mt-0.5"><?= $countPaket ?> Paket</div>
                <a href="inventaris.php#paket" class="text-[11px] text-amber-700 font-semibold hover:underline">Kelola paket &rarr;</a>
            </div>
            <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-stone-500 font-medium">Bank Simpul</span>
                <div class="font-bold text-xl text-stone-900 mt-0.5"><?= $countTut ?> Materi</div>
                <a href="materi.php" class="text-[11px] text-amber-700 font-semibold hover:underline">Buka pustaka &rarr;</a>
            </div>
            <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-knot"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-stone-500 font-medium">Saldo Kas Sanggar</span>
                <div class="font-bold text-base text-emerald-700 mt-0.5"><?= format_rupiah($totalKas) ?></div>
                <a href="arsip.php" class="text-[11px] text-amber-700 font-semibold hover:underline"><?= $countArc ?> Berkas arsip &rarr;</a>
            </div>
            <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

    </div>

    <!-- Tabel Terkini & Aksi Cepat -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-8 bg-white rounded-2xl border border-stone-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3 border-b border-stone-100 pb-2">
                <h3 class="font-bold text-sm text-stone-900">Daftar Inventaris Terkini</h3>
                <a href="inventaris.php" class="text-xs font-semibold text-amber-700 hover:underline">Semua Alat</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-stone-600">
                    <thead class="bg-stone-50 text-stone-700 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="p-2.5">Nama Barang</th>
                            <th class="p-2.5">Kategori</th>
                            <th class="p-2.5">Tarif</th>
                            <th class="p-2.5">Stok</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach ($latest_inv as $item): ?>
                            <tr class="hover:bg-stone-50 transition">
                                <td class="p-2.5 font-semibold text-stone-900">
                                    <?= htmlspecialchars($item['nama_barang']) ?>
                                </td>
                                <td class="p-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $item['kategori'] === 'paket' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-700' ?>">
                                        <?= htmlspecialchars($item['kategori']) ?>
                                    </span>
                                </td>
                                <td class="p-2.5 font-bold text-stone-800">
                                    <?= format_rupiah($item['harga']) ?>
                                </td>
                                <td class="p-2.5 font-semibold"><?= $item['stok'] ?> unit</td>
                                <td class="p-2.5 text-right">
                                    <a href="inventaris.php?edit=<?= $item['id'] ?>" class="text-amber-700 font-semibold hover:underline">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-3">
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5 space-y-3">
                <h3 class="font-bold text-sm text-stone-900 mb-2">Pintasan Pengurus</h3>
                
                <a href="inventaris.php#form-tambah" class="flex items-center gap-3 p-3 rounded-xl bg-stone-50 hover:bg-stone-100 border border-stone-200 text-stone-800 transition">
                    <i class="fa-solid fa-plus text-amber-700 text-sm"></i>
                    <span class="text-xs font-semibold">Tambah Barang / Paket</span>
                </a>

                <a href="materi.php#form-tambah" class="flex items-center gap-3 p-3 rounded-xl bg-stone-50 hover:bg-stone-100 border border-stone-200 text-stone-800 transition">
                    <i class="fa-solid fa-knot text-amber-700 text-sm"></i>
                    <span class="text-xs font-semibold">Tambah Panduan Simpul</span>
                </a>

                <a href="arsip.php#form-upload" class="flex items-center gap-3 p-3 rounded-xl bg-stone-50 hover:bg-stone-100 border border-stone-200 text-stone-800 transition">
                    <i class="fa-solid fa-upload text-amber-700 text-sm"></i>
                    <span class="text-xs font-semibold">Upload Laporan / Kas</span>
                </a>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
