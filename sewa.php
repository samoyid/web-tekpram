<?php
require_once __DIR__ . '/config/database.php';
$page_title = "Katalog & Sewa Inventaris Sanggar - Tekpram";
require_once __DIR__ . '/includes/header.php';

// Ambil semua barang dari database
$stmt = $pdo->query("SELECT * FROM inventory WHERE is_active = 1 ORDER BY kategori DESC, harga DESC");
$inventory_items = $stmt->fetchAll();

$paket_items = array_filter($inventory_items, fn($i) => $i['kategori'] === 'paket');
$satuan_items = array_filter($inventory_items, fn($i) => $i['kategori'] === 'satuan');

$syarat_sewa = get_setting($pdo, 'syarat_sewa', '');
?>

<!-- Header Halaman -->
<div class="bg-scout-900 text-white py-10 border-b border-scout-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-stone-400 mb-1">
                    <a href="index.php" class="hover:text-white transition">Beranda</a>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    <span class="text-stone-200">Sewa Inventaris</span>
                </nav>
                <h1 class="font-bold text-2xl sm:text-3xl text-white">
                    Katalog & Sewa Inventaris Sanggar
                </h1>
                <p class="text-stone-300 text-xs sm:text-sm mt-1 max-w-xl">
                    Cek tarif perlengkapan tenda, tongkat, dan paket latihan, lalu pesan langsung lewat WhatsApp admin.
                </p>
            </div>
            
            <div class="bg-scout-800 border border-scout-700 rounded-xl px-4 py-3 flex items-center gap-3 self-start md:self-auto">
                <i class="fa-brands fa-whatsapp text-emerald-400 text-2xl"></i>
                <div>
                    <div class="text-[10px] text-stone-400 uppercase font-semibold">Admin Sewa:</div>
                    <div class="font-bold text-white text-sm"><?= htmlspecialchars($wa_admin) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Tarif Satuan / Hari (Clean & No Emoji) -->
<div class="bg-stone-200 border-b border-stone-300 px-4 py-2.5 text-xs text-stone-800">
    <div class="max-w-7xl mx-auto flex items-center gap-2">
        <span class="font-bold text-stone-900">Catatan Tarif:</span>
        <span>Semua harga barang di bawah adalah harga <strong>satuan / per pcs</strong> per hari pemakaian (kecuali paket hemat yang sudah bundling untuk durasi tertentu).</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: KATALOG (8 Kolom) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Filter Kategori Ringkas -->
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="filterItems('all')" class="filter-btn active px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-stone-900 text-white" data-target="all">
                    Semua Perlengkapan
                </button>
                <button type="button" onclick="filterItems('paket')" class="filter-btn px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-stone-700 border border-stone-300 hover:bg-stone-50" data-target="paket">
                    Paket Hemat
                </button>
                <button type="button" onclick="filterItems('tenda')" class="filter-btn px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-stone-700 border border-stone-300 hover:bg-stone-50" data-target="tenda">
                    Tenda & Pasak
                </button>
                <button type="button" onclick="filterItems('tali_tongkat')" class="filter-btn px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-stone-700 border border-stone-300 hover:bg-stone-50" data-target="tali_tongkat">
                    Tongkat & Tali
                </button>
                <button type="button" onclick="filterItems('aksesoris')" class="filter-btn px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-stone-700 border border-stone-300 hover:bg-stone-50" data-target="aksesoris">
                    Aksesoris (Semaphore/Kacu)
                </button>
            </div>

            <!-- Bagian 1: Paket Hemat -->
            <div id="section-paket" class="space-y-4">
                <div class="border-b border-stone-200 pb-2">
                    <h2 class="font-bold text-lg text-stone-900">Pilihan Paket Hemat</h2>
                    <p class="text-xs text-stone-500">Bundling perlengkapan kemah dan latihan siap pakai</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach ($paket_items as $pkt): ?>
                        <div class="item-card bg-white rounded-xl border border-stone-200 p-5 flex flex-col justify-between shadow-sm" data-category="paket">
                            <div>
                                <span class="bg-amber-50 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-200">
                                    Paket <?= $pkt['durasi_hari'] ?> Hari
                                </span>
                                
                                <h3 class="font-bold text-sm text-stone-900 mt-2 mb-1">
                                    <?= htmlspecialchars($pkt['nama_barang']) ?>
                                </h3>
                                
                                <div class="font-extrabold text-lg text-stone-900 mb-2">
                                    <?= format_rupiah($pkt['harga']) ?>
                                </div>

                                <div class="bg-stone-50 p-2.5 rounded-lg border border-stone-200 text-xs text-stone-600 mb-4">
                                    <div class="font-semibold text-stone-700 mb-1">Rincian:</div>
                                    <ul class="space-y-1 text-[11px]">
                                        <?php 
                                        $items_p = explode("\n", $pkt['rincian_paket']);
                                        foreach ($items_p as $ip): 
                                            if (trim($ip) === '') continue;
                                        ?>
                                            <li class="flex items-start gap-1.5">
                                                <i class="fa-solid fa-check text-emerald-600 text-[10px] mt-0.5"></i>
                                                <span><?= htmlspecialchars(trim($ip)) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <button type="button" 
                                        onclick="addToCart(<?= $pkt['id'] ?>, '<?= addslashes($pkt['nama_barang']) ?>', <?= $pkt['harga'] ?>, 'paket', <?= $pkt['durasi_hari'] ?>)"
                                        class="w-full bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2 px-3 rounded-lg transition">
                                    + Masukkan ke Hitungan
                                </button>
                                <button type="button" 
                                        onclick="orderDirectWA('<?= addslashes($pkt['nama_barang']) ?>', '<?= format_rupiah($pkt['harga']) ?> (Paket <?= $pkt['durasi_hari'] ?> Hari)')"
                                        class="w-full text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 font-semibold text-xs py-1.5 px-3 rounded-lg transition flex items-center justify-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp"></i> Chat WA Paket Ini
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bagian 2: Barang Satuan -->
            <div id="section-satuan" class="space-y-4">
                <div class="border-b border-stone-200 pb-2">
                    <h2 class="font-bold text-lg text-stone-900">Daftar Barang Satuan (Per Hari / Per PCS)</h2>
                    <p class="text-xs text-stone-500">Klik "+ Pilih" untuk menghitung total atau langsung chat WA barang terkait</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($satuan_items as $item): ?>
                        <div class="item-card bg-white rounded-xl border border-stone-200 p-4 shadow-sm flex flex-col justify-between" data-category="<?= htmlspecialchars($item['jenis']) ?>">
                            <div>
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-semibold text-stone-500 bg-stone-100 px-2 py-0.5 rounded">
                                        <?= $item['satuan_durasi'] === 'pcs' ? 'Per PCS' : 'Per Hari' ?>
                                    </span>
                                    <span class="text-xs font-bold text-stone-900">
                                        <?= format_rupiah($item['harga']) ?>
                                    </span>
                                </div>

                                <h3 class="font-bold text-sm text-stone-900 mb-1">
                                    <?= htmlspecialchars($item['nama_barang']) ?>
                                </h3>

                                <p class="text-xs text-stone-500 mb-3 leading-relaxed">
                                    <?= htmlspecialchars($item['deskripsi']) ?>
                                </p>
                            </div>

                            <div class="pt-2 border-t border-stone-100 flex items-center gap-2">
                                <button type="button" 
                                        onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes($item['nama_barang']) ?>', <?= $item['harga'] ?>, 'satuan', 1, '<?= $item['satuan_durasi'] ?>')"
                                        class="flex-1 bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-2 px-3 rounded-lg transition">
                                    + Pilih
                                </button>
                                <button type="button" 
                                        onclick="orderDirectWA('<?= addslashes($item['nama_barang']) ?>', '<?= format_rupiah($item['harga']) ?> <?= $item['satuan_durasi'] === 'pcs' ? '/pcs' : '/hari' ?>')"
                                        class="p-2 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs"
                                        title="Chat WA barang ini">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Syarat dan Ketentuan Sewa -->
            <div id="syarat" class="bg-stone-100 rounded-xl border border-stone-200 p-5 space-y-2">
                <div class="text-stone-900 font-bold text-xs uppercase tracking-wider">
                    Ketentuan Peminjaman Sanggar:
                </div>
                <div class="text-xs text-stone-600 leading-relaxed whitespace-pre-line bg-white p-3.5 rounded-lg border border-stone-200">
                    <?= htmlspecialchars($syarat_sewa) ?>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: RINGKASAN & ORDER WA (4 Kolom Sticky) -->
        <div class="lg:col-span-4 sticky top-24" id="kalkulator">
            <div class="bg-white rounded-2xl border border-stone-300 shadow-lg p-5">
                
                <div class="flex items-center justify-between border-b border-stone-200 pb-3 mb-4">
                    <div>
                        <h3 class="font-bold text-base text-stone-900">Perkiraan Biaya</h3>
                        <p class="text-[11px] text-stone-500">Hitung & pesan via WhatsApp</p>
                    </div>
                    <button type="button" onclick="clearCart()" class="text-xs text-stone-400 hover:text-red-600">
                        Reset
                    </button>
                </div>

                <!-- Durasi & Nama Penyewa -->
                <div class="space-y-3 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">
                            Durasi Sewa Barang Satuan:
                        </label>
                        <div class="flex items-center">
                            <button type="button" onclick="changeGlobalDuration(-1)" class="w-9 h-8 bg-stone-100 hover:bg-stone-200 rounded-l border border-stone-300 font-bold text-sm">-</button>
                            <input type="number" id="rental-days" value="1" min="1" max="30" onchange="updateCartTotals()" class="w-full h-8 text-center border-y border-stone-300 font-bold text-xs focus:outline-none">
                            <button type="button" onclick="changeGlobalDuration(1)" class="w-9 h-8 bg-stone-100 hover:bg-stone-200 rounded-r border border-stone-300 font-bold text-sm">+</button>
                            <span class="ml-2 text-xs text-stone-600 font-medium">Hari</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Nama / Asal Regu:</label>
                        <input type="text" id="rental-name" placeholder="Nama atau asal gudep (opsional)" class="w-full text-xs p-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                    </div>
                </div>

                <!-- Daftar Barang Terpilih -->
                <div class="mb-4">
                    <div class="text-[11px] font-bold text-stone-600 uppercase tracking-wider mb-2">Item Dipilih:</div>
                    <div id="cart-items-container" class="space-y-2 max-h-56 overflow-y-auto pr-1">
                        <div id="cart-empty-state" class="text-center py-6 text-stone-400 border border-dashed border-stone-200 rounded-lg text-xs">
                            Belum ada barang dipilih.<br>
                            <span class="text-[10px] text-stone-400">Klik "+ Pilih" pada daftar alat di samping.</span>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-stone-50 rounded-xl p-3.5 border border-stone-200 mb-4 space-y-1.5">
                    <div class="flex justify-between items-center text-xs text-stone-600">
                        <span>Durasi:</span>
                        <span id="duration-display" class="font-semibold text-stone-800">1 Hari</span>
                    </div>
                    <div class="border-t border-stone-200 pt-2 flex justify-between items-center">
                        <span class="font-bold text-sm text-stone-900">Total Estimasi:</span>
                        <span id="total-display" class="font-extrabold text-lg text-emerald-700">Rp 0</span>
                    </div>
                </div>

                <!-- Tombol Pesan WA & Opsi Salin Teks -->
                <div class="space-y-2">
                    <button type="button" 
                            onclick="checkoutWhatsApp()"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow transition flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span class="text-xs">Lanjut Chat WhatsApp</span>
                    </button>
                    <button type="button" 
                            onclick="copyOrderText()"
                            class="w-full bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold py-2 px-3 rounded-lg text-[11px] transition flex items-center justify-center gap-1.5 border border-stone-200">
                        <i class="fa-regular fa-copy"></i>
                        <span id="copy-btn-text">Salin Teks Rekap Pesanan</span>
                    </button>
                </div>

                <p class="text-[10px] text-center text-stone-400 mt-2">
                    Pesan otomatis dikirim ke nomor <strong><?= htmlspecialchars($wa_admin) ?></strong>
                </p>
            </div>
        </div>

    </div>
</div>

<script>
    let cart = [];
    const waNumber = "<?= preg_replace('/[^0-9]/', '', $wa_admin) ?>";

    document.addEventListener("DOMContentLoaded", () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tambahPaketId = urlParams.get('tambah_paket');
        if (tambahPaketId) {
            const found = <?= json_encode(array_values($paket_items)) ?>.find(p => p.id == tambahPaketId);
            if (found) {
                addToCart(found.id, found.nama_barang, found.harga, 'paket', found.durasi_hari);
            }
        }
    });

    function filterItems(category) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-stone-900', 'text-white', 'active');
            btn.classList.add('bg-white', 'text-stone-700');
            if (btn.getAttribute('data-target') === category) {
                btn.classList.remove('bg-white', 'text-stone-700');
                btn.classList.add('bg-stone-900', 'text-white', 'active');
            }
        });

        const cards = document.querySelectorAll('.item-card');
        const sectionPaket = document.getElementById('section-paket');
        const sectionSatuan = document.getElementById('section-satuan');

        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (category === 'all' || cardCat === category) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });

        if (category === 'paket') {
            sectionPaket.style.display = 'block';
            sectionSatuan.style.display = 'none';
        } else if (category === 'all') {
            sectionPaket.style.display = 'block';
            sectionSatuan.style.display = 'block';
        } else {
            sectionPaket.style.display = 'none';
            sectionSatuan.style.display = 'block';
        }
    }

    function addToCart(id, name, price, category, fixedDays = 1, unitType = 'perhari') {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                category: category,
                fixedDays: fixedDays,
                unitType: unitType,
                qty: 1
            });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        }
        renderCart();
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
        if (cart.length > 0) {
            cart = [];
            renderCart();
        }
    }

    function changeGlobalDuration(delta) {
        const input = document.getElementById('rental-days');
        let current = parseInt(input.value) || 1;
        current += delta;
        if (current < 1) current = 1;
        input.value = current;
        renderCart();
    }

    function updateCartTotals() {
        renderCart();
    }

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const globalDays = parseInt(document.getElementById('rental-days').value) || 1;

        if (cart.length === 0) {
            container.innerHTML = `
                <div id="cart-empty-state" class="text-center py-6 text-stone-400 border border-dashed border-stone-200 rounded-lg text-xs">
                    Belum ada barang dipilih.<br>
                    <span class="text-[10px] text-stone-400">Klik "+ Pilih" pada daftar alat di samping.</span>
                </div>
            `;
            document.getElementById('duration-display').innerText = `${globalDays} Hari`;
            document.getElementById('total-display').innerText = 'Rp 0';
            return;
        }

        let html = '';
        let grandTotal = 0;

        cart.forEach(item => {
            let itemTotal = 0;
            if (item.category === 'paket') {
                itemTotal = item.price * item.qty;
            } else {
                if (item.unitType === 'pcs') {
                    itemTotal = item.price * item.qty;
                } else {
                    itemTotal = item.price * item.qty * globalDays;
                }
            }

            grandTotal += itemTotal;

            html += `
                <div class="bg-stone-50 border border-stone-200 rounded-lg p-2 flex items-center justify-between text-xs">
                    <div class="flex-1 pr-2">
                        <div class="font-bold text-stone-800 line-clamp-1">${item.name}</div>
                        <div class="text-[10px] text-stone-500">${formatRupiah(itemTotal)}</div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="updateQty(${item.id}, -1)" class="w-5 h-5 rounded bg-stone-200 hover:bg-stone-300 font-bold text-stone-700 flex items-center justify-center text-xs">-</button>
                        <span class="w-4 text-center font-bold text-stone-800 text-xs">${item.qty}</span>
                        <button type="button" onclick="updateQty(${item.id}, 1)" class="w-5 h-5 rounded bg-stone-200 hover:bg-stone-300 font-bold text-stone-700 flex items-center justify-center text-xs">+</button>
                        <button type="button" onclick="removeFromCart(${item.id})" class="ml-1 text-stone-400 hover:text-red-600"><i class="fa-solid fa-xmark text-xs"></i></button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('duration-display').innerText = `${globalDays} Hari`;
        document.getElementById('total-display').innerText = formatRupiah(grandTotal);
    }

    // Direct WA Inquiry untuk single item
    function orderDirectWA(itemName, priceLabel) {
        const text = `Halo Admin Tekpram, salam pramuka.%0A%0ASaya ingin bertanya / sewa perlengkapan sanggar:%0A- Barang: ${encodeURIComponent(itemName)}%0A- Tarif: ${encodeURIComponent(priceLabel)}%0A%0AApakah stok tersedia? Terima kasih.`;
        window.open(`https://wa.me/${waNumber}?text=${text}`, '_blank');
    }

    function buildOrderPlainText() {
        if (cart.length === 0) return null;
        const name = document.getElementById('rental-name').value.trim() || 'Penyewa';
        const globalDays = parseInt(document.getElementById('rental-days').value) || 1;

        let txt = `*PENGAJUAN SEWA INVENTARIS TEKPRAM*\n\n`;
        txt += `Halo Admin Tekpram, salam pramuka.\nSaya ingin menyewa perlengkapan sanggar dengan rincian berikut:\n\n`;
        txt += `Peminjam: ${name}\n`;
        txt += `Durasi Sewa: ${globalDays} Hari\n\n`;
        txt += `Daftar Barang:\n`;

        let grandTotal = 0;
        cart.forEach((item, idx) => {
            let itemTotal = 0;
            if (item.category === 'paket') {
                itemTotal = item.price * item.qty;
            } else {
                if (item.unitType === 'pcs') {
                    itemTotal = item.price * item.qty;
                } else {
                    itemTotal = item.price * item.qty * globalDays;
                }
            }
            grandTotal += itemTotal;
            txt += `${idx + 1}. ${item.name} (${item.qty}x) = Rp ${itemTotal.toLocaleString('id-ID')}\n`;
        });

        txt += `----------------------------\n`;
        txt += `*Total Estimasi: Rp ${grandTotal.toLocaleString('id-ID')}*\n\n`;
        txt += `Apakah alat tersebut siap dipinjam? Terima kasih.`;
        return txt;
    }

    function copyOrderText() {
        const txt = buildOrderPlainText();
        if (!txt) {
            alert('Silakan pilih minimal 1 barang terlebih dahulu.');
            return;
        }

        navigator.clipboard.writeText(txt).then(() => {
            const btnText = document.getElementById('copy-btn-text');
            btnText.innerText = 'Berhasil Disalin!';
            setTimeout(() => {
                btnText.innerText = 'Salin Teks Rekap Pesanan';
            }, 2500);
        }).catch(() => {
            prompt('Salin teks rekap berikut secara manual:', txt);
        });
    }

    // Checkout Form dan Redirect ke WhatsApp (Tanpa Emoji)
    function checkoutWhatsApp() {
        const rawText = buildOrderPlainText();
        if (!rawText) {
            alert('Silakan pilih minimal 1 barang terlebih dahulu.');
            return;
        }

        const encoded = encodeURIComponent(rawText);
        const waUrl = `https://wa.me/${waNumber}?text=${encoded}`;
        window.open(waUrl, '_blank');
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
