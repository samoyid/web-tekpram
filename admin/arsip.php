<?php
$admin_title = "Arsip Dokumen & Laporan - Admin Tekpram";
require_once __DIR__ . '/admin_header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmtFind = $pdo->prepare("SELECT file_path FROM archives WHERE id = ?");
    $stmtFind->execute([$del_id]);
    $arc = $stmtFind->fetch();

    if ($arc) {
        $fullPath = __DIR__ . '/../uploads/arsip/' . $arc['file_path'];
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
        $stmtDel = $pdo->prepare("DELETE FROM archives WHERE id = ?");
        $stmtDel->execute([$del_id]);
        $message = "Berkas arsip berhasil dihapus.";
    }
}

// Handle Upload / Tambah Arsip
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'kegiatan');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal_arsip = $_POST['tanggal_arsip'] ?? date('Y-m-d');
    $nominal_kas = (int)($_POST['nominal_kas'] ?? 0);
    $is_public = 0; // Arsip khusus internal pengurus, tidak ditampilkan di publik

    if (empty($judul)) {
        $error = "Judul dokumen wajib diisi.";
    } else {
        $fileName = '';
        $fileOriginalName = '';
        $fileSizeStr = '0 KB';
        $fileExt = 'pdf';

        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            $uploadedTmp = $_FILES['file_dokumen']['tmp_name'];
            $fileOriginalName = $_FILES['file_dokumen']['name'];
            $fileSize = $_FILES['file_dokumen']['size'];
            $fileExt = strtolower(pathinfo($fileOriginalName, PATHINFO_EXTENSION));

            if ($fileSize > 1048576) {
                $fileSizeStr = round($fileSize / 1048576, 2) . ' MB';
            } else {
                $fileSizeStr = round($fileSize / 1024, 1) . ' KB';
            }

            $uploadDir = __DIR__ . '/../uploads/arsip/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileOriginalName);
            $destination = $uploadDir . $safeName;

            if (move_uploaded_file($uploadedTmp, $destination)) {
                $fileName = $safeName;
            } else {
                $error = "Gagal memindahkan file ke folder uploads/arsip.";
            }
        } else {
            $fileName = 'catatan_' . time() . '.txt';
            $fileOriginalName = $judul . '.txt';
            $fileSizeStr = 'Catatan';
            $fileExt = 'txt';
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("INSERT INTO archives (judul, kategori, file_path, nama_file_asli, ukuran_file, tipe_file, keterangan, nominal_kas, tanggal_arsip, is_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$judul, $kategori, $fileName, $fileOriginalName, $fileSizeStr, $fileExt, $keterangan, $nominal_kas, $tanggal_arsip, $is_public]);
            $message = "Dokumen '{$judul}' berhasil disimpan di folder uploads/arsip/";
        }
    }
}

$archives = $pdo->query("SELECT * FROM archives ORDER BY tanggal_arsip DESC")->fetchAll();
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-bold text-2xl text-stone-900">
                Arsip Laporan & Keuangan Pengurus
            </h1>
            <p class="text-xs text-stone-500 mt-0.5">
                Penyimpanan berkas LPJ kegiatan, program kerja, dan rekapitulasi kas divisi.
            </p>
        </div>
        <a href="#form-upload" class="inline-flex items-center gap-2 bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg transition self-start">
            <i class="fa-solid fa-upload"></i>
            <span>Upload Berkas Baru</span>
        </a>
    </div>

    <!-- Info Box Lokasi Penyimpanan File -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-950 space-y-1">
        <div class="font-bold flex items-center gap-1.5 text-amber-900">
            <i class="fa-solid fa-folder-open text-amber-700"></i>
            <span>Lokasi Penyimpanan File Fisik:</span>
        </div>
        <p class="leading-relaxed">
            Semua file yang kamu upload tersimpan otomatis di folder: <code class="bg-amber-100 px-1.5 py-0.5 rounded text-amber-900 font-mono font-bold">uploads/arsip/</code> di dalam direktori web ini.
            File dapat langsung kamu unduh atau buka melalui tombol <strong>"Buka / Unduh"</strong> pada tabel di bawah.
        </p>
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

    <!-- Form Upload Arsip -->
    <div id="form-upload" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <h3 class="font-bold text-sm text-stone-900 mb-1">Upload Berkas Laporan / Kas</h3>
        <p class="text-xs text-stone-500 mb-5">Format file yang didukung: PDF, Word (DOCX), Excel (XLSX), atau ZIP.</p>

        <form method="POST" action="arsip.php" enctype="multipart/form-data" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Judul Dokumen / Laporan:</label>
                    <input type="text" name="judul" required placeholder="Contoh: LPJ Kemah Bantara 2026, Rekap Kas Triwulan I" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Kategori Dokumen:</label>
                    <select name="kategori" id="kategori-arsip" onchange="toggleKasInput()" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                        <option value="kegiatan">Laporan Kegiatan (LPJ)</option>
                        <option value="proker">Program Kerja (Proker)</option>
                        <option value="keuangan">Laporan Keuangan / Kas</option>
                        <option value="notulensi">Notulensi Rapat</option>
                        <option value="inventaris">Berita Acara Inventaris</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Pilih File dari Komputer:</label>
                    <input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" class="w-full text-xs p-2 rounded-lg border border-stone-300 bg-stone-50">
                    <span class="text-[10px] text-stone-400 mt-1 block">Maksimal 25 MB</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Tanggal Dokumen:</label>
                    <input type="date" name="tanggal_arsip" value="<?= date('Y-m-d') ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div id="wrapper-kas" style="display: none;">
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nominal Kas (Rp):</label>
                    <input type="number" name="nominal_kas" value="0" step="1000" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 font-bold text-emerald-800">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Keterangan / Catatan Tambahan:</label>
                <textarea name="keterangan" rows="2" placeholder="Catatan hasil kegiatan atau rincian transaksi..." class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2.5 px-5 rounded-lg transition">
                    Simpan & Upload Berkas
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Berkas -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <h3 class="font-bold text-sm text-stone-900 mb-3">Daftar Berkas Tersimpan (<?= count($archives) ?> Dokumen)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-700 font-semibold uppercase tracking-wider text-[10px] border-b border-stone-200">
                    <tr>
                        <th class="p-3">Judul Laporan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">File / Ukuran</th>
                        <th class="p-3">Kas</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <?php if (empty($archives)): ?>
                        <tr>
                            <td colspan="6" class="p-6 text-center text-stone-400">Belum ada berkas laporan yang diunggah.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($archives as $a): 
                            $filePath = __DIR__ . '/../uploads/arsip/' . $a['file_path'];
                            $fileExists = file_exists($filePath) && is_file($filePath);
                            $downloadUrl = "../uploads/arsip/" . rawurlencode($a['file_path']);
                        ?>
                            <tr class="hover:bg-stone-50 transition">
                                <td class="p-3 font-semibold text-stone-900">
                                    <div><?= htmlspecialchars($a['judul']) ?></div>
                                    <div class="text-[11px] text-stone-400 font-normal line-clamp-1"><?= htmlspecialchars($a['keterangan']) ?></div>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $a['kategori'] === 'keuangan' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-700' ?>">
                                        <?= htmlspecialchars($a['kategori']) ?>
                                    </span>
                                </td>
                                <td class="p-3 text-stone-500">
                                    <?= date('d/m/Y', strtotime($a['tanggal_arsip'])) ?>
                                </td>
                                <td class="p-3 text-stone-500 font-mono text-[11px]">
                                    <?= htmlspecialchars($a['nama_file_asli'] ?: $a['file_path']) ?>
                                    <span class="text-stone-400 font-sans block text-[10px]"><?= htmlspecialchars($a['ukuran_file']) ?></span>
                                </td>
                                <td class="p-3 font-bold text-emerald-700">
                                    <?= $a['nominal_kas'] > 0 ? format_rupiah($a['nominal_kas']) : '-' ?>
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <?php if ($fileExists): ?>
                                        <a href="<?= $downloadUrl ?>" target="_blank" download="<?= htmlspecialchars($a['nama_file_asli']) ?>" class="text-blue-700 hover:text-blue-900 font-bold underline">
                                            Buka / Unduh
                                        </a>
                                    <?php else: ?>
                                        <span class="text-stone-400 italic text-[11px]">Catatan data</span>
                                    <?php endif; ?>
                                    <a href="arsip.php?delete=<?= $a['id'] ?>" onclick="return confirm('Hapus berkas <?= addslashes($a['judul']) ?>?')" class="text-red-600 hover:text-red-800 font-bold underline">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleKasInput() {
        const cat = document.getElementById('kategori-arsip').value;
        const kas = document.getElementById('wrapper-kas');
        if (cat === 'keuangan') {
            kas.style.display = 'block';
        } else {
            kas.style.display = 'none';
        }
    }
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
