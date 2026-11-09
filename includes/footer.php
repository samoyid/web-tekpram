    </main>

    <!-- Floating WhatsApp Button (Clean & Modern) -->
    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $wa_admin) ?>?text=<?= urlencode('Halo Admin Tekpram, salam pramuka. Saya ingin bertanya ketersediaan sewa perlengkapan sanggar.') ?>" 
       target="_blank" 
       class="fixed bottom-6 right-6 z-40 bg-emerald-600 hover:bg-emerald-700 text-white p-3.5 rounded-full shadow-lg flex items-center justify-center transition duration-200 hover:scale-105 border border-emerald-500"
       title="Chat Admin WhatsApp">
        <i class="fa-brands fa-whatsapp text-2xl"></i>
    </a>

    <!-- Footer Pramuka -->
    <footer class="bg-scout-900 text-stone-300 border-t-2 border-scout-800 mt-16">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Kolom 1: Profil Sanggar -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="assets/images/logo.png" alt="Logo Ambalan Bisma Srikandi" class="w-10 h-10 object-contain">
                        <div>
                            <span class="font-bold text-sm text-white block">Ambalan Bisma - Srikandi</span>
                            <span class="text-[11px] text-stone-400">Gudep 04.001 - 04.002 SMKN 1 Probolinggo</span>
                        </div>
                    </div>
                    <p class="text-xs text-stone-400 leading-relaxed">
                        Divisi Teknik Kepramukaan (Tekpram). Layanan peminjaman perlengkapan perkemahan, tongkat, tenda, dan pustaka materi simpul tali-temali untuk regu dan ambalan.
                    </p>
                    <div class="pt-1">
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $wa_admin) ?>" target="_blank" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp: <?= htmlspecialchars($wa_admin) ?>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Menu Navigasi -->
                <div class="space-y-3">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Navigasi</h5>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li>
                            <a href="index.php" class="hover:text-white transition">Beranda Utama</a>
                        </li>
                        <li>
                            <a href="sewa.php" class="hover:text-white transition">Katalog & Sewa Inventaris</a>
                        </li>
                        <li>
                            <a href="tutorial.php" class="hover:text-white transition">Tutorial Simpul & Sandi</a>
                        </li>
                        <li>
                            <a href="sewa.php#syarat" class="hover:text-white transition">Syarat & Prosedur Sewa</a>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Area Khusus Pengurus -->
                <div class="space-y-3">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Internal Pengurus</h5>
                    <p class="text-xs text-stone-400 leading-relaxed">
                        Pengarsipan dokumen laporan kegiatan, keuangan kas, dan manajemen stok alat hanya dapat diakses oleh pengurus divisi.
                    </p>
                    <div class="pt-1">
                        <a href="admin/login.php" class="inline-flex items-center gap-2 text-xs font-semibold text-amber-300 hover:text-white transition">
                            <i class="fa-solid fa-arrow-right-to-bracket text-[11px]"></i>
                            <span>Masuk ke Panel Pengurus</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="border-t border-scout-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-2">
                <p>&copy; <?= date('Y') ?> Divisi Teknik Kepramukaan (Tekpram). Hak cipta dilindungi.</p>
                <div class="flex items-center space-x-3">
                    <span>Gerakan Pramuka</span>
                    <span>•</span>
                    <a href="sewa.php" class="hover:text-stone-300 transition">Sewa Alat</a>
                    <span>•</span>
                    <a href="tutorial.php" class="hover:text-stone-300 transition">Tutorial</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
