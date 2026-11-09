<?php
$admin_title = "Kelola Inventaris & Paket - Admin Tekpram";
require_once __DIR__ . '/admin_header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->execute([$del_id]);
    $message = "Barang inventaris berhasil dihapus dari sistem.";
}

// Handle Form Submit (Tambah / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'satuan');
    $jenis = trim($_POST['jenis'] ?? 'umum');
    $harga = (int)($_POST['harga'] ?? 0);
    $satuan_durasi = trim($_POST['satuan_durasi'] ?? 'perhari');
    $durasi_hari = (int)($_POST['durasi_hari'] ?? 1);
    $stok = (int)($_POST['stok'] ?? 10);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $rincian_paket = trim($_POST['rincian_paket'] ?? '');

    if (empty($nama_barang) || $harga <= 0) {
        $error = "Nama barang dan harga sewa wajib diisi dengan benar.";
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE inventory SET nama_barang = ?, kategori = ?, jenis = ?, harga = ?, satuan_durasi = ?, durasi_hari = ?, stok = ?, deskripsi = ?, rincian_paket = ? WHERE id = ?");
            $stmt->execute([$nama_barang, $kategori, $jenis, $harga, $satuan_durasi, $durasi_hari, $stok, $deskripsi, $rincian_paket, $id]);
            $message = "Data inventaris '{$nama_barang}' berhasil diperbarui.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO inventory (nama_barang, kategori, jenis, harga, satuan_durasi, durasi_hari, stok, deskripsi, rincian_paket) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama_barang, $kategori, $jenis, $harga, $satuan_durasi, $durasi_hari, $stok, $deskripsi, $rincian_paket]);
            $message = "Barang baru '{$nama_barang}' berhasil ditambahkan ke katalog.";
        }
    }
}

// Jika mode edit
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ? LIMIT 1");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

// Ambil semua barang
$items = $pdo->query("SELECT * FROM inventory ORDER BY kategori DESC, harga DESC")->fetchAll();
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-bold text-2xl text-stone-900">
                Kelola Inventaris & Paket Sanggar
            </h1>
            <p class="text-xs text-stone-500 mt-0.5">
                Atur tarif sewa satuan, paket kemah, dan stok barang sanggar.
            </p>
        </div>
        <a href="#form-tambah" class="inline-flex items-center gap-2 bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg transition self-start">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Barang Baru</span>
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
            <?= $edit_item ? 'Edit Inventaris: ' . htmlspecialchars($edit_item['nama_barang']) : 'Tambah Barang / Paket Baru' ?>
        </h3>
        <p class="text-xs text-stone-500 mb-5">
            <?= $edit_item ? 'Perbarui informasi harga atau stok barang.' : 'Masukkan data perlengkapan sanggar yang disewakan.' ?>
        </p>

        <form method="POST" action="inventaris.php" class="space-y-3.5">
            <?php if ($edit_item): ?>
                <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Barang / Paket:</label>
                    <input type="text" name="nama_barang" required value="<?= htmlspecialchars($edit_item['nama_barang'] ?? '') ?>" placeholder="Contoh: Tenda Prisma, Ring Kacu Pramuka, Paket Kemah Hemat" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Kategori:</label>
                    <select name="kategori" id="kategori-select" onchange="togglePaketField()" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                        <option value="satuan" <?= ($edit_item['kategori'] ?? '') === 'satuan' ? 'selected' : '' ?>>Barang Satuan (Per Hari / Per PCS)</option>
                        <option value="paket" <?= ($edit_item['kategori'] ?? '') === 'paket' ? 'selected' : '' ?>>Paket Hemat (Bundling)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Harga Sewa (Rp):</label>
                    <input type="number" name="harga" required min="500" step="500" value="<?= $edit_item['harga'] ?? 5000 ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 font-bold text-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Satuan Tarif:</label>
                    <select name="satuan_durasi" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                        <option value="perhari" <?= ($edit_item['satuan_durasi'] ?? '') === 'perhari' ? 'selected' : '' ?>>Per Hari</option>
                        <option value="pcs" <?= ($edit_item['satuan_durasi'] ?? '') === 'pcs' ? 'selected' : '' ?>>Per PCS (Tanpa kali hari)</option>
                        <option value="paket_3hari" <?= ($edit_item['satuan_durasi'] ?? '') === 'paket_3hari' ? 'selected' : '' ?>>Paket 3 Hari</option>
                        <option value="paket_2hari" <?= ($edit_item['satuan_durasi'] ?? '') === 'paket_2hari' ? 'selected' : '' ?>>Paket 2 Hari</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Durasi Hari:</label>
                    <input type="number" name="durasi_hari" min="1" max="30" value="<?= $edit_item['durasi_hari'] ?? 1 ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Jumlah Stok:</label>
                    <input type="number" name="stok" min="0" value="<?= $edit_item['stok'] ?? 10 ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Kelompok Jenis:</label>
                    <select name="jenis" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                        <option value="tenda" <?= ($edit_item['jenis'] ?? '') === 'tenda' ? 'selected' : '' ?>>Tenda & Pasak</option>
                        <option value="tali_tongkat" <?= ($edit_item['jenis'] ?? '') === 'tali_tongkat' ? 'selected' : '' ?>>Tongkat, Tali & Pionering</option>
                        <option value="aksesoris" <?= ($edit_item['jenis'] ?? '') === 'aksesoris' ? 'selected' : '' ?>>Aksesoris (Semaphore/Ring Kacu)</option>
                        <option value="paket" <?= ($edit_item['jenis'] ?? '') === 'paket' ? 'selected' : '' ?>>Paket Hemat</option>
                        <option value="umum" <?= ($edit_item['jenis'] ?? '') === 'umum' ? 'selected' : '' ?>>Lainnya / Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Deskripsi Singkat:</label>
                    <input type="text" name="deskripsi" value="<?= htmlspecialchars($edit_item['deskripsi'] ?? '') ?>" placeholder="Bahan, kelengkapan, dsb..." class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>
            </div>

            <div id="rincian-paket-wrapper">
                <label class="block text-xs font-semibold text-stone-700 mb-1">Rincian Isi Paket (khusus paket hemat):</label>
                <textarea name="rincian_paket" rows="3" placeholder="1. Tenda Prisma (1 unit)&#10;2. Pasak Kayu (10 pcs)&#10;3. Tali 10m (2 pcs)" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800"><?= htmlspecialchars($edit_item['rincian_paket'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2.5 px-5 rounded-lg transition">
                    <?= $edit_item ? 'Simpan Perubahan' : 'Tambah ke Katalog' ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="inventaris.php" class="text-xs font-semibold text-stone-500 hover:text-stone-800 px-3 py-2">Batal Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Inventaris Lengkap -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <h3 class="font-bold text-sm text-stone-900 mb-3">Seluruh Inventaris Sanggar (<?= count($items) ?> Item)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-700 font-semibold uppercase text-[10px] border-b border-stone-200">
                    <tr>
                        <th class="p-3">Nama Barang / Paket</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Tarif</th>
                        <th class="p-3">Satuan</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <?php foreach ($items as $it): ?>
                        <tr class="hover:bg-stone-50 transition">
                            <td class="p-3 font-semibold text-stone-900">
                                <div><?= htmlspecialchars($it['nama_barang']) ?></div>
                                <div class="text-[11px] text-stone-400 font-normal line-clamp-1"><?= htmlspecialchars($it['deskripsi']) ?></div>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $it['kategori'] === 'paket' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-700' ?>">
                                    <?= htmlspecialchars($it['kategori']) ?>
                                </span>
                            </td>
                            <td class="p-3 font-bold text-stone-900">
                                <?= format_rupiah($it['harga']) ?>
                            </td>
                            <td class="p-3 text-stone-500">
                                <?= htmlspecialchars($it['satuan_durasi']) ?>
                            </td>
                            <td class="p-3 font-semibold text-stone-800">
                                <?= $it['stok'] ?> unit
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <a href="inventaris.php?edit=<?= $it['id'] ?>#form-tambah" class="text-amber-700 hover:text-amber-900 font-semibold underline">Edit</a>
                                <a href="inventaris.php?delete=<?= $it['id'] ?>" onclick="return confirm('Hapus barang <?= addslashes($it['nama_barang']) ?>?')" class="text-red-600 hover:text-red-800 font-semibold underline">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function togglePaketField() {
        const cat = document.getElementById('kategori-select').value;
        const wrapper = document.getElementById('rincian-paket-wrapper');
        if (cat === 'paket') {
            wrapper.style.display = 'block';
        }
    }
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
