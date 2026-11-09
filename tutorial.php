<?php
require_once __DIR__ . '/config/database.php';
$page_title = "Tutorial Simpul & Sandi - Divisi Tekpram";
require_once __DIR__ . '/includes/header.php';

// Filter Kategori & Pencarian
$kategori_filter = isset($_GET['kategori']) ? clean_input($_GET['kategori']) : '';
$search_query = isset($_GET['q']) ? clean_input($_GET['q']) : '';

$sql = "SELECT * FROM tutorials WHERE 1=1";
$params = [];

if (!empty($kategori_filter)) {
    $sql .= " AND kategori = ?";
    $params[] = $kategori_filter;
}

if (!empty($search_query)) {
    $sql .= " AND (judul LIKE ? OR deskripsi LIKE ? OR fungsi LIKE ?)";
    $searchTerm = "%{$search_query}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$sql .= " ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tutorials = $stmt->fetchAll();
?>

<!-- Header Section -->
<div class="bg-scout-900 text-white py-10 border-b border-scout-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-400 mb-1">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-stone-200">Tutorial Simpul & Sandi</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="font-bold text-2xl sm:text-3xl text-white">
                    Bank Simpul & Materi Kepramukaan
                </h1>
                <p class="text-stone-300 text-xs sm:text-sm mt-1 max-w-2xl">
                    Kumpulan panduan simpul tali-temali, pionering, bendera semaphore, sandi morse, dan teknik lapangan.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-4 mb-6">
        <form method="GET" action="tutorial.php" class="flex flex-col md:flex-row gap-3 items-center justify-between">
            
            <!-- Category Chips -->
            <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                <a href="tutorial.php" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= empty($kategori_filter) ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                    Semua
                </a>
                <a href="tutorial.php?kategori=simpul" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter === 'simpul' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                    Simpul Dasar
                </a>
                <a href="tutorial.php?kategori=ikatan" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter === 'ikatan' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                    Ikatan Pionering
                </a>
                <a href="tutorial.php?kategori=semaphore" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter === 'semaphore' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                    Semaphore
                </a>
                <a href="tutorial.php?kategori=sandi" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter === 'sandi' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                    Sandi Pramuka
                </a>
            </div>

            <!-- Search Field -->
            <div class="relative w-full md:w-64">
                <?php if (!empty($kategori_filter)): ?>
                    <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori_filter) ?>">
                <?php endif; ?>
                <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="Cari nama materi..." class="w-full text-xs pl-8 pr-7 py-2 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-stone-400 text-xs"></i>
                <?php if (!empty($search_query)): ?>
                    <a href="tutorial.php<?= !empty($kategori_filter) ? '?kategori=' . urlencode($kategori_filter) : '' ?>" class="absolute right-2.5 top-2 text-stone-400 hover:text-stone-700 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Grid List Materi -->
    <?php if (empty($tutorials)): ?>
        <div class="bg-white rounded-xl border border-stone-200 text-center py-12 px-4">
            <h3 class="font-bold text-sm text-stone-800">Materi Tidak Ditemukan</h3>
            <p class="text-xs text-stone-500 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            <a href="tutorial.php" class="inline-block mt-3 bg-stone-900 text-white text-xs px-3 py-1.5 rounded-lg">
                Reset
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($tutorials as $item): ?>
                <div class="bg-white rounded-xl border border-stone-200 shadow-sm hover:shadow transition p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded <?= $item['tingkat_kesulitan'] === 'dasar' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' ?>">
                                <?= htmlspecialchars(ucfirst($item['tingkat_kesulitan'])) ?>
                            </span>
                            <span class="text-xs text-stone-400 capitalize">
                                <?= htmlspecialchars($item['kategori']) ?>
                            </span>
                        </div>

                        <h3 class="font-bold text-base text-stone-900 mb-2">
                            <?= htmlspecialchars($item['judul']) ?>
                        </h3>

                        <p class="text-xs text-stone-600 leading-relaxed mb-4 line-clamp-3">
                            <?= htmlspecialchars($item['deskripsi']) ?>
                        </p>

                        <div class="bg-stone-50 p-2.5 rounded-lg border border-stone-200 mb-4">
                            <div class="text-[11px] font-semibold text-stone-700 mb-0.5">Fungsi Lapangan:</div>
                            <p class="text-xs text-stone-600 line-clamp-2">
                                <?= htmlspecialchars($item['fungsi']) ?>
                            </p>
                        </div>
                    </div>

                    <a href="tutorial-detail.php?id=<?= $item['id'] ?>" class="text-xs font-bold text-amber-700 hover:text-amber-800 inline-flex items-center gap-1">
                        <span>Baca Panduan Lengkap</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
