<?php
$admin_title = "Pustaka Simpul & Materi - Admin Tekpram";
require_once __DIR__ . '/admin_header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tutorials WHERE id = ?");
    $stmt->execute([$del_id]);
    $message = "Panduan materi berhasil dihapus dari pustaka.";
}

// Handle Form Submit (Tambah / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'simpul');
    $tingkat_kesulitan = trim($_POST['tingkat_kesulitan'] ?? 'dasar');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $fungsi = trim($_POST['fungsi'] ?? '');
    $langkah_langkah = trim($_POST['langkah_langkah'] ?? '');
    $tips = trim($_POST['tips'] ?? '');

    if (empty($judul) || empty($deskripsi) || empty($langkah_langkah)) {
        $error = "Judul materi, deskripsi, dan langkah-langkah wajib diisi.";
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE tutorials SET judul = ?, kategori = ?, tingkat_kesulitan = ?, deskripsi = ?, fungsi = ?, langkah_langkah = ?, tips = ? WHERE id = ?");
            $stmt->execute([$judul, $kategori, $tingkat_kesulitan, $deskripsi, $fungsi, $langkah_langkah, $tips, $id]);
            $message = "Materi '{$judul}' berhasil diperbarui.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO tutorials (judul, kategori, tingkat_kesulitan, deskripsi, fungsi, langkah_langkah, tips) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$judul, $kategori, $tingkat_kesulitan, $deskripsi, $fungsi, $langkah_langkah, $tips]);
            $message = "Materi baru '{$judul}' berhasil ditambahkan ke bank simpul.";
        }
    }
}

// Edit Item
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM tutorials WHERE id = ? LIMIT 1");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

$tutorials = $pdo->query("SELECT * FROM tutorials ORDER BY id ASC")->fetchAll();
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-bold text-2xl text-stone-900">
                Pustaka Simpul & Materi Kepramukaan
            </h1>
            <p class="text-xs text-stone-500 mt-0.5">
                Kelola panduan tutorial simpul, pionering, morse, dan teknik lapangan.
            </p>
        </div>
        <a href="#form-tambah" class="inline-flex items-center gap-2 bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg transition self-start">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Panduan Baru</span>
        </a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-check text-sm"></i>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs p-3.5 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Tambah / Edit -->
    <div id="form-tambah" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <h3 class="font-bold text-sm text-stone-900 mb-1">
            <?= $edit_item ? 'Edit Materi: ' . htmlspecialchars($edit_item['judul']) : 'Tambah Panduan Baru' ?>
        </h3>
        <p class="text-xs text-stone-500 mb-5">
            Tuliskan instruksi langkah demi langkah secara jelas.
        </p>

        <form method="POST" action="materi.php" class="space-y-3.5">
            <?php if ($edit_item): ?>
                <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Judul Materi / Simpul:</label>
                    <input type="text" name="judul" required value="<?= htmlspecialchars($edit_item['judul'] ?? '') ?>" placeholder="Contoh: Simpul Pangkal (Clove Hitch), Ikatan Palang" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Kategori:</label>
                        <select name="kategori" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                            <option value="simpul" <?= ($edit_item['kategori'] ?? '') === 'simpul' ? 'selected' : '' ?>>Simpul</option>
                            <option value="ikatan" <?= ($edit_item['kategori'] ?? '') === 'ikatan' ? 'selected' : '' ?>>Ikatan</option>
                            <option value="semaphore" <?= ($edit_item['kategori'] ?? '') === 'semaphore' ? 'selected' : '' ?>>Semaphore</option>
                            <option value="morse" <?= ($edit_item['kategori'] ?? '') === 'morse' ? 'selected' : '' ?>>Morse</option>
                            <option value="sandi" <?= ($edit_item['kategori'] ?? '') === 'sandi' ? 'selected' : '' ?>>Sandi</option>
                            <option value="tenda" <?= ($edit_item['kategori'] ?? '') === 'tenda' ? 'selected' : '' ?>>Tenda/Bivak</option>
                            <option value="kompas" <?= ($edit_item['kategori'] ?? '') === 'kompas' ? 'selected' : '' ?>>Kompas/Navigasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Kesulitan:</label>
                        <select name="tingkat_kesulitan" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                            <option value="dasar" <?= ($edit_item['tingkat_kesulitan'] ?? '') === 'dasar' ? 'selected' : '' ?>>Dasar</option>
                            <option value="menengah" <?= ($edit_item['tingkat_kesulitan'] ?? '') === 'menengah' ? 'selected' : '' ?>>Menengah</option>
                            <option value="mahir" <?= ($edit_item['tingkat_kesulitan'] ?? '') === 'mahir' ? 'selected' : '' ?>>Mahir</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Deskripsi Singkat:</label>
                <textarea name="deskripsi" rows="2" required placeholder="Penjelasan umum simpul atau materi..." class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800"><?= htmlspecialchars($edit_item['deskripsi'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Fungsi di Lapangan:</label>
                <textarea name="fungsi" rows="2" placeholder="Fungsi dalam pendirian tenda, pionering, dsb..." class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800"><?= htmlspecialchars($edit_item['fungsi'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Langkah-Langkah Pembuatan:</label>
                <textarea name="langkah_langkah" rows="4" required placeholder="1. Ambil tali...&#10;2. Silangkan ujung tali...&#10;3. Tarik kedua ujung..." class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800"><?= htmlspecialchars($edit_item['langkah_langkah'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Tips Praktis:</label>
                <input type="text" name="tips" value="<?= htmlspecialchars($edit_item['tips'] ?? '') ?>" placeholder="Tips agar ikatan kuat..." class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2.5 px-5 rounded-lg transition">
                    <?= $edit_item ? 'Simpan Perubahan' : 'Terbitkan Panduan' ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="materi.php" class="text-xs font-semibold text-stone-500 hover:text-stone-800 px-3 py-2">Batal Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Materi -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <h3 class="font-bold text-sm text-stone-900 mb-3">Daftar Materi di Bank Simpul (<?= count($tutorials) ?>)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-700 font-semibold uppercase text-[10px] border-b border-stone-200">
                    <tr>
                        <th class="p-3">Judul Materi</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Tingkat</th>
                        <th class="p-3">Fungsi Ringkas</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <?php foreach ($tutorials as $t): ?>
                        <tr class="hover:bg-stone-50 transition">
                            <td class="p-3 font-semibold text-stone-900">
                                <?= htmlspecialchars($t['judul']) ?>
                            </td>
                            <td class="p-3 capitalize font-medium text-stone-700">
                                <?= htmlspecialchars($t['kategori']) ?>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $t['tingkat_kesulitan'] === 'dasar' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' ?>">
                                    <?= htmlspecialchars($t['tingkat_kesulitan']) ?>
                                </span>
                            </td>
                            <td class="p-3 text-stone-500 line-clamp-1 max-w-xs">
                                <?= htmlspecialchars($t['fungsi']) ?>
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <a href="../tutorial-detail.php?id=<?= $t['id'] ?>" target="_blank" class="text-stone-500 hover:text-stone-700 underline font-semibold">Lihat</a>
                                <a href="materi.php?edit=<?= $t['id'] ?>#form-tambah" class="text-amber-700 hover:text-amber-900 font-semibold underline">Edit</a>
                                <a href="materi.php?delete=<?= $t['id'] ?>" onclick="return confirm('Hapus materi <?= addslashes($t['judul']) ?>?')" class="text-red-600 hover:text-red-800 font-semibold underline">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
