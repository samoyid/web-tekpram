<?php
$admin_title = "Pengaturan Sanggar & WhatsApp - Admin Tekpram";
require_once __DIR__ . '/admin_header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wa = trim($_POST['whatsapp_number'] ?? '');
    $sanggar = trim($_POST['sanggar_name'] ?? '');
    $gudep = trim($_POST['gudep_name'] ?? '');
    $alamat = trim($_POST['alamat_sanggar'] ?? '');
    $syarat = trim($_POST['syarat_sewa'] ?? '');

    $settings_to_update = [
        'whatsapp_number' => $wa,
        'sanggar_name' => $sanggar,
        'gudep_name' => $gudep,
        'alamat_sanggar' => $alamat,
        'syarat_sewa' => $syarat
    ];

    foreach ($settings_to_update as $key => $val) {
        $check = $pdo->prepare("SELECT id FROM settings WHERE setting_key = ?");
        $check->execute([$key]);
        if ($check->fetch()) {
            $up = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $up->execute([$val, $key]);
        } else {
            $ins = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $ins->execute([$key, $val]);
        }
    }

    $message = "Pengaturan sanggar & nomor WhatsApp berhasil diperbarui.";
}

$wa_current = get_setting($pdo, 'whatsapp_number', '085851635670');
$sanggar_current = get_setting($pdo, 'sanggar_name', 'Divisi Teknik Kepramukaan (Tekpram)');
$gudep_current = get_setting($pdo, 'gudep_name', 'Sanggar Bakti Gerakan Pramuka');
$alamat_current = get_setting($pdo, 'alamat_sanggar', 'Kompleks Sanggar Pramuka');
$syarat_current = get_setting($pdo, 'syarat_sewa', '');
?>

<div class="space-y-6 max-w-3xl">
    <div>
        <h1 class="font-bold text-2xl text-stone-900">
            Pengaturan Sanggar & WhatsApp
        </h1>
        <p class="text-xs text-stone-500 mt-0.5">
            Atur nomor kontak tujuan pemesanan sewa dan profil sanggar.
        </p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-check text-sm"></i>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <form method="POST" action="pengaturan.php" class="space-y-4">
            
            <div class="bg-stone-50 border border-stone-200 rounded-xl p-4 space-y-1.5">
                <label class="block text-xs font-bold text-stone-800">
                    Nomor WhatsApp Tujuan Pemesanan Sewa:
                </label>
                <p class="text-[11px] text-stone-500">
                    Pemesanan dari halaman sewa publik akan langsung diarahkan ke nomor ini.
                </p>
                <div class="pt-1">
                    <input type="text" name="whatsapp_number" required value="<?= htmlspecialchars($wa_current) ?>" placeholder="Contoh: 085851635670" class="w-full sm:w-72 text-sm font-bold text-stone-900 p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 bg-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Divisi / Sanggar:</label>
                    <input type="text" name="sanggar_name" value="<?= htmlspecialchars($sanggar_current) ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Gugus Depan / Pangkalan:</label>
                    <input type="text" name="gudep_name" value="<?= htmlspecialchars($gudep_current) ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Alamat Sanggar:</label>
                <input type="text" name="alamat_sanggar" value="<?= htmlspecialchars($alamat_current) ?>" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Syarat & Ketentuan Sewa (Tampil di halaman sewa publik):</label>
                <textarea name="syarat_sewa" rows="4" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800 font-mono leading-relaxed"><?= htmlspecialchars($syarat_current) ?></textarea>
            </div>

            <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2.5 px-5 rounded-lg transition">
                    Simpan Pengaturan
                </button>
                <a href="../sewa.php" target="_blank" class="text-xs font-semibold text-amber-700 hover:underline">
                    Lihat Halaman Sewa &rarr;
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
