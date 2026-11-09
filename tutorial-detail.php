<?php
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM tutorials WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$tut = $stmt->fetch();

if (!$tut) {
    header("Location: tutorial.php");
    exit;
}

$page_title = $tut['judul'] . " - Bank Simpul Tekpram";
require_once __DIR__ . '/includes/header.php';

// Ambil materi terkait lainnya
$stmtRelated = $pdo->prepare("SELECT * FROM tutorials WHERE id != ? ORDER BY id ASC LIMIT 4");
$stmtRelated->execute([$id]);
$related = $stmtRelated->fetchAll();
?>

<!-- Breadcrumb -->
<div class="bg-stone-100 border-b border-stone-200 py-2.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-600">
            <a href="index.php" class="hover:text-stone-900 transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[8px] text-stone-400"></i>
            <a href="tutorial.php" class="hover:text-stone-900 transition">Tutorial Simpul</a>
            <i class="fa-solid fa-chevron-right text-[8px] text-stone-400"></i>
            <span class="text-stone-900 font-semibold truncate max-w-xs"><?= htmlspecialchars($tut['judul']) ?></span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KONTEN UTAMA (8 Kolom) -->
        <article class="lg:col-span-8 bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6">
            
            <div class="border-b border-stone-100 pb-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded <?= $tut['tingkat_kesulitan'] === 'dasar' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' ?>">
                        Tingkat <?= htmlspecialchars(ucfirst($tut['tingkat_kesulitan'])) ?>
                    </span>
                    <span class="text-xs text-stone-400 capitalize">
                        <?= htmlspecialchars($tut['kategori']) ?>
                    </span>
                </div>

                <h1 class="font-bold text-2xl sm:text-3xl text-stone-900">
                    <?= htmlspecialchars($tut['judul']) ?>
                </h1>

                <p class="text-sm text-stone-600 mt-2 leading-relaxed">
                    <?= htmlspecialchars($tut['deskripsi']) ?>
                </p>
            </div>

            <!-- Fungsi Lapangan -->
            <?php if (!empty($tut['fungsi'])): ?>
                <div class="bg-stone-50 rounded-xl p-4 border border-stone-200">
                    <div class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-1">
                        Fungsi & Aplikasi di Lapangan:
                    </div>
                    <p class="text-xs sm:text-sm text-stone-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($tut['fungsi'])) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Langkah-Langkah Pembuatan -->
            <div class="space-y-3">
                <h2 class="font-bold text-lg text-stone-900">
                    Langkah-Langkah Pembuatan
                </h2>

                <div class="bg-stone-50 rounded-xl p-5 border border-stone-200 text-stone-800 text-xs sm:text-sm leading-relaxed whitespace-pre-line space-y-2">
                    <?= htmlspecialchars($tut['langkah_langkah']) ?>
                </div>
            </div>

            <!-- Tips Lapangan -->
            <?php if (!empty($tut['tips'])): ?>
                <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-xs sm:text-sm text-amber-950">
                    <strong class="block mb-1 text-amber-900">Tips Dewan Tekpram:</strong>
                    <p class="leading-relaxed"><?= htmlspecialchars($tut['tips']) ?></p>
                </div>
            <?php endif; ?>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-between">
                <a href="tutorial.php" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
                    &larr; Kembali ke Daftar Materi
                </a>
                <button type="button" onclick="window.print()" class="text-xs text-stone-500 hover:text-stone-800 border border-stone-300 px-3 py-1.5 rounded-lg">
                    Cetak Panduan
                </button>
            </div>

        </article>

        <!-- SIDEBAR (4 Kolom) -->
        <aside class="lg:col-span-4 space-y-4">
            
            <div class="bg-scout-900 rounded-2xl p-5 text-white border border-scout-800">
                <h3 class="font-bold text-sm mb-1">Butuh Tali atau Tongkat?</h3>
                <p class="text-xs text-stone-300 mb-3 leading-relaxed">
                    Sewa perlengkapan latihan pionering dan simpul di sanggar tekpram mulai Rp 2.000 / hari.
                </p>
                <a href="sewa.php" class="inline-block w-full text-center bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs py-2 px-3 rounded-lg transition">
                    Cek Sewa Alat
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
                <h3 class="font-bold text-sm text-stone-900 mb-3">Materi Lainnya</h3>
                <div class="space-y-2.5">
                    <?php foreach ($related as $rel): ?>
                        <a href="tutorial-detail.php?id=<?= $rel['id'] ?>" class="block p-2.5 rounded-lg border border-stone-100 hover:bg-stone-50 transition">
                            <span class="text-[10px] font-semibold text-stone-400 uppercase"><?= htmlspecialchars($rel['kategori']) ?></span>
                            <h4 class="font-semibold text-xs text-stone-800 line-clamp-1">
                                <?= htmlspecialchars($rel['judul']) ?>
                            </h4>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </aside>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
