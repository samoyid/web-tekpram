<?php
/**
 * Konfigurasi Database & Inisialisasi Otomatis
 * Divisi Teknik Kepramukaan (Tekpram)
 */

if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    @session_start();
}

$db_host = '127.0.0.1';
$db_name = 'tekpram_db';
$db_user = 'root';
$db_pass = '';
$db_port = 3306;

$pdo = null;
$db_driver = 'mysql';

// Coba hubungkan ke MySQL terlebih dahulu
try {
    // Hubungkan tanpa db_name terlebih dahulu untuk memastikan database dibuat jika belum ada
    $dsn_server = "mysql:host={$db_host};port={$db_port};charset=utf8mb4";
    $pdo_server = new PDO($dsn_server, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 2
    ]);
    $pdo_server->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    
    // Hubungkan ke database tekpram_db
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    $db_driver = 'mysql';
} catch (Exception $e) {
    // Jika MySQL offline atau terblokir di sandbox, fallback otomatis ke SQLite lokal
    $sqlite_file = __DIR__ . '/../database/tekpram.sqlite';
    $dsn = "sqlite:" . $sqlite_file;
    $pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $db_driver = 'sqlite';
}

/**
 * Buat tabel & seeder data bawaan jika belum ada
 */
function initialize_database($pdo, $driver) {
    if ($driver === 'mysql') {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                nama_lengkap VARCHAR(100) NOT NULL,
                role VARCHAR(20) DEFAULT 'admin',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS inventory (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama_barang VARCHAR(150) NOT NULL,
                kategori VARCHAR(20) NOT NULL, -- 'satuan' atau 'paket'
                jenis VARCHAR(50) DEFAULT 'umum', -- 'tenda', 'tali_tongkat', 'aksesoris', 'paket'
                harga INT NOT NULL,
                satuan_durasi VARCHAR(50) DEFAULT 'perhari', -- 'perhari', 'pcs', 'paket_3hari', 'paket_2hari'
                durasi_hari INT DEFAULT 1,
                deskripsi TEXT,
                rincian_paket TEXT NULL,
                foto VARCHAR(255) NULL,
                stok INT DEFAULT 10,
                is_active INT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS tutorials (
                id INT AUTO_INCREMENT PRIMARY KEY,
                judul VARCHAR(150) NOT NULL,
                kategori VARCHAR(50) NOT NULL, -- 'simpul', 'ikatan', 'pionering', 'semaphore', 'morse', 'sandi', 'tenda', 'kompas'
                deskripsi TEXT NOT NULL,
                fungsi TEXT,
                langkah_langkah TEXT,
                tips TEXT,
                gambar VARCHAR(255) NULL,
                tingkat_kesulitan VARCHAR(20) DEFAULT 'dasar',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS archives (
                id INT AUTO_INCREMENT PRIMARY KEY,
                judul VARCHAR(200) NOT NULL,
                kategori VARCHAR(50) NOT NULL, -- 'kegiatan', 'proker', 'keuangan', 'notulensi', 'inventaris'
                file_path VARCHAR(255) NOT NULL,
                nama_file_asli VARCHAR(255),
                ukuran_file VARCHAR(50),
                tipe_file VARCHAR(20),
                keterangan TEXT,
                nominal_kas INT DEFAULT 0,
                tanggal_arsip DATE NOT NULL,
                is_public INT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(50) NOT NULL UNIQUE,
                setting_value TEXT NOT NULL
            ) ENGINE=InnoDB;
        ");
    } else {
        // SQLite Schema
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                nama_lengkap TEXT NOT NULL,
                role TEXT DEFAULT 'admin',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS inventory (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_barang TEXT NOT NULL,
                kategori TEXT NOT NULL,
                jenis TEXT DEFAULT 'umum',
                harga INTEGER NOT NULL,
                satuan_durasi TEXT DEFAULT 'perhari',
                durasi_hari INTEGER DEFAULT 1,
                deskripsi TEXT,
                rincian_paket TEXT,
                foto TEXT,
                stok INTEGER DEFAULT 10,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS tutorials (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                judul TEXT NOT NULL,
                kategori TEXT NOT NULL,
                deskripsi TEXT NOT NULL,
                fungsi TEXT,
                langkah_langkah TEXT,
                tips TEXT,
                gambar TEXT,
                tingkat_kesulitan TEXT DEFAULT 'dasar',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS archives (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                judul TEXT NOT NULL,
                kategori TEXT NOT NULL,
                file_path TEXT NOT NULL,
                nama_file_asli TEXT,
                ukuran_file TEXT,
                tipe_file TEXT,
                keterangan TEXT,
                nominal_kas INTEGER DEFAULT 0,
                tanggal_arsip DATE NOT NULL,
                is_public INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                setting_key TEXT NOT NULL UNIQUE,
                setting_value TEXT NOT NULL
            );
        ");
    }

    // Seeder Pengaturan Sistem Default
    $stmtSettings = $pdo->query("SELECT COUNT(*) as cnt FROM settings");
    if ($stmtSettings->fetch()['cnt'] == 0) {
        $settings = [
            ['whatsapp_number', '085851635670'],
            ['sanggar_name', 'Ambalan Bisma - Srikandi'],
            ['gudep_name', 'Gudep 04.001 - 04.002 SMKN 1 Probolinggo'],
            ['alamat_sanggar', 'SMK Negeri 1 Probolinggo'],
            ['motto', 'Satyaku Kudarmakan, Darmaku Kubaktikan'],
            ['syarat_sewa', '1. Menyerahkan kartu identitas (KTA Pramuka / KTP / Kartu Pelajar) sebagai jaminan.\n2. Pembayaran uang muka (DP) minimal 50% saat pengambilan barang.\n3. Keterlambatan pengembalian dikenakan denda sesuai tarif harian.\n4. Barang yang rusak atau hilang wajib diganti sesuai spesifikasi asli.']
        ];
        $insertSetting = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
        foreach ($settings as $s) {
            $insertSetting->execute($s);
        }
    } else {
        $pdo->exec("UPDATE settings SET setting_value = 'Ambalan Bisma - Srikandi' WHERE setting_key = 'sanggar_name'");
        $pdo->exec("UPDATE settings SET setting_value = 'Gudep 04.001 - 04.002 SMKN 1 Probolinggo' WHERE setting_key = 'gudep_name'");
        $pdo->exec("UPDATE settings SET setting_value = 'SMK Negeri 1 Probolinggo' WHERE setting_key = 'alamat_sanggar'");
    }

    // Seeder Akun Admin Default (admin / pramuka123)
    $stmtUser = $pdo->query("SELECT COUNT(*) as cnt FROM users");
    if ($stmtUser->fetch()['cnt'] == 0) {
        $passwordHash = password_hash('pramuka123', PASSWORD_DEFAULT);
        $insertUser = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
        $insertUser->execute(['admin', $passwordHash, 'Anggota Tekpram', 'admin']);
    } else {
        // Pastikan nama lengkap terupdate menjadi Anggota Tekpram
        $pdo->exec("UPDATE users SET nama_lengkap = 'Anggota Tekpram' WHERE username = 'admin' AND nama_lengkap LIKE '%Pembina%'");
    }

    // Seeder Barang Inventaris & Paket Sanggar
    $pdo->exec("UPDATE inventory SET nama_barang = 'Ring Kacu Pramuka' WHERE nama_barang = 'Kacu Pramuka'");
    $stmtInv = $pdo->query("SELECT COUNT(*) as cnt FROM inventory");
    if ($stmtInv->fetch()['cnt'] == 0) {
        $items = [
            // Barang Satuan
            [
                'nama_barang' => 'Tongkat Pramuka',
                'kategori' => 'satuan',
                'jenis' => 'tali_tongkat',
                'harga' => 2000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Tongkat kayu standar ukuran 160 cm, halus, lurus, dan kokoh untuk pionering maupun baris-berbaris.',
                'rincian_paket' => null,
                'foto' => 'tongkat.png',
                'stok' => 50
            ],
            [
                'nama_barang' => 'Tali Pramuka 10 Meter',
                'kategori' => 'satuan',
                'jenis' => 'tali_tongkat',
                'harga' => 4000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Tali maron serat kuat panjang 10 meter, lentur, ideal untuk ikatan pionering menara dan tiang bendera.',
                'rincian_paket' => null,
                'foto' => 'tali10m.png',
                'stok' => 30
            ],
            [
                'nama_barang' => 'Tali Pramuka 5 Meter',
                'kategori' => 'satuan',
                'jenis' => 'tali_tongkat',
                'harga' => 2000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Tali maron panjang 5 meter untuk simpul dasar, tandu darurat, dan latihan regu.',
                'rincian_paket' => null,
                'foto' => 'tali5m.png',
                'stok' => 40
            ],
            [
                'nama_barang' => 'Semaphore (1 Pasang)',
                'kategori' => 'satuan',
                'jenis' => 'aksesoris',
                'harga' => 5000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Sepasang bendera semaphore ukuran standar merah-kuning cerah lengkap dengan stik kayu.',
                'rincian_paket' => null,
                'foto' => 'semaphore.png',
                'stok' => 15
            ],
            [
                'nama_barang' => 'Tenda Prisma (Tenda Regu)',
                'kategori' => 'satuan',
                'jenis' => 'tenda',
                'harga' => 25000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Tenda prisma klasik kapasitas 6-8 orang, bahan terpal tebal waterproof tahan hujan dan angin lapangan.',
                'rincian_paket' => null,
                'foto' => 'tenda_prisma.png',
                'stok' => 8
            ],
            [
                'nama_barang' => 'Tenda Dome',
                'kategori' => 'satuan',
                'jenis' => 'tenda',
                'harga' => 35000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Tenda dome modern kapasitas 4-5 orang dengan double layer, frame fiber elastis, dan ventilasi jaring anti-nyamuk.',
                'rincian_paket' => null,
                'foto' => 'tenda_dome.png',
                'stok' => 6
            ],
            [
                'nama_barang' => '10 Pasak Besi',
                'kategori' => 'satuan',
                'jenis' => 'tenda',
                'harga' => 10000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => '1 set berisi 10 buah pasak besi ulir tebal, sangat kuat menancap di tanah keras dan bebatuan.',
                'rincian_paket' => null,
                'foto' => 'pasak_besi.png',
                'stok' => 20
            ],
            [
                'nama_barang' => '10 Pasak Kayu',
                'kategori' => 'satuan',
                'jenis' => 'tenda',
                'harga' => 5000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => '1 set berisi 10 buah pasak kayu runcing padat, ramah tanah gembur dan perkemahan rumput.',
                'rincian_paket' => null,
                'foto' => 'pasak_kayu.png',
                'stok' => 25
            ],
            [
                'nama_barang' => 'Paku Besar (Tiang/Pionering)',
                'kategori' => 'satuan',
                'jenis' => 'tali_tongkat',
                'harga' => 1000,
                'satuan_durasi' => 'perhari',
                'durasi_hari' => 1,
                'deskripsi' => 'Paku besi ukuran besar untuk pengikat kuat rangka konstruksi perkemahan dan gapura.',
                'rincian_paket' => null,
                'foto' => 'paku.png',
                'stok' => 50
            ],
            [
                'nama_barang' => 'Ring Kacu Pramuka',
                'kategori' => 'satuan',
                'jenis' => 'aksesoris',
                'harga' => 8000,
                'satuan_durasi' => 'pcs',
                'durasi_hari' => 1,
                'deskripsi' => 'Ring rotan / kaleng setangan leher pramuka dengan lambang tunas kelapa siap pakai.',
                'rincian_paket' => null,
                'foto' => 'ring_kacu.png',
                'stok' => 30
            ],
            // Paket Hemat
            [
                'nama_barang' => 'Paket KEMAH HEMAT',
                'kategori' => 'paket',
                'jenis' => 'paket',
                'harga' => 90000,
                'satuan_durasi' => 'paket_3hari',
                'durasi_hari' => 3,
                'deskripsi' => 'Paket lengkap dan praktis untuk regu kemah 3 hari 2 malam. Hemat budget dan siap dirikan kemah mandiri.',
                'rincian_paket' => "1. Tenda Prisma (1 unit)\n2. Pasak Kayu (10 pcs / 1 set)\n3. Tali Pramuka 10 Meter (2 pcs)\n4. Tongkat Pramuka (2 pcs)",
                'foto' => 'paket_kemah.png',
                'stok' => 5
            ],
            [
                'nama_barang' => 'Paket PIONERING MINI',
                'kategori' => 'paket',
                'jenis' => 'paket',
                'harga' => 30000,
                'satuan_durasi' => 'paket_2hari',
                'durasi_hari' => 2,
                'deskripsi' => 'Paket latihan pionering untuk membuat menara pandang mini, tiang bendera 3 kaki, atau jembatan darurat selama 2 hari.',
                'rincian_paket' => "1. Tongkat Pramuka (7 pcs)\n2. Tali Pramuka 5 Meter (5 pcs)",
                'foto' => 'paket_pionering.png',
                'stok' => 8
            ],
            [
                'nama_barang' => 'Paket LATIHAN REGU',
                'kategori' => 'paket',
                'jenis' => 'paket',
                'harga' => 30000,
                'satuan_durasi' => 'paket_2hari',
                'durasi_hari' => 2,
                'deskripsi' => 'Paket latihan mingguan regu penggalang/penegak untuk materi isyarat semaphore, tandu darurat, dan baris tongkat.',
                'rincian_paket' => "1. Semaphore (3 pasang)\n2. Tongkat Pramuka (3 pcs)\n3. Tali Pramuka 10 Meter (1 pcs)",
                'foto' => 'paket_regu.png',
                'stok' => 6
            ]
        ];

        $insertItem = $pdo->prepare("INSERT INTO inventory (nama_barang, kategori, jenis, harga, satuan_durasi, durasi_hari, deskripsi, rincian_paket, foto, stok) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $insertItem->execute([
                $item['nama_barang'],
                $item['kategori'],
                $item['jenis'],
                $item['harga'],
                $item['satuan_durasi'],
                $item['durasi_hari'],
                $item['deskripsi'],
                $item['rincian_paket'],
                $item['foto'],
                $item['stok']
            ]);
        }
    }

    // Tambahkan kolom youtube_url jika belum ada
    try {
        $pdo->exec("ALTER TABLE tutorials ADD COLUMN youtube_url VARCHAR(255) NULL");
    } catch (Exception $e) {
        // Kolom sudah ada
    }

    // Update YouTube URL untuk materi yang sudah ada
    $pdo->exec("UPDATE tutorials SET youtube_url = 'https://www.youtube.com/watch?v=J8n0G8eQd_Q' WHERE judul LIKE '%Simpul Pangkal%'");
    $pdo->exec("UPDATE tutorials SET youtube_url = 'https://www.youtube.com/watch?v=vVj4x96F0f4' WHERE judul LIKE '%Simpul Jangkar%'");
    $pdo->exec("UPDATE tutorials SET youtube_url = 'https://www.youtube.com/watch?v=8Vz9qWb39tY' WHERE judul LIKE '%Simpul Mati%'");
    $pdo->exec("UPDATE tutorials SET youtube_url = 'https://www.youtube.com/watch?v=4UqK_c7T4V8' WHERE judul LIKE '%Ikatan Palang%'");
    $pdo->exec("UPDATE tutorials SET youtube_url = 'https://www.youtube.com/watch?v=33G1Yw_oW_A' WHERE judul LIKE '%Semaphore%'");
    $pdo->exec("UPDATE tutorials SET youtube_url = 'https://www.youtube.com/watch?v=x0bC4n41oWk' WHERE judul LIKE '%Sandi%'");

    // Seeder Materi & Simpul Awal
    $stmtTut = $pdo->query("SELECT COUNT(*) as cnt FROM tutorials");
    if ($stmtTut->fetch()['cnt'] == 0) {
        $tutorials = [
            [
                'judul' => 'Simpul Pangkal (Clove Hitch)',
                'kategori' => 'simpul',
                'tingkat_kesulitan' => 'dasar',
                'deskripsi' => 'Simpul yang paling vital dalam teknik kepramukaan. Berfungsi sebagai awal dan akhir dari hampir seluruh ikatan pionering tongkat.',
                'fungsi' => 'Mengikatkan tali pada tongkat/kayu, memulai ikatan palang, silang, dan canggah, serta mengikat tali tenda pada pasak.',
                'langkah_langkah' => "1. Lilitkan ujung tali melingkari tongkat dari arah depan ke belakang.\n2. Silangkan ujung tali di atas bagian tali utama sehingga membentuk huruf X.\n3. Masukkan ujung tali ke bawah silangan huruf X tersebut.\n4. Tarik kedua ujung tali secara berlawanan hingga simpul mengunci dengan rapat pada tongkat.",
                'tips' => 'Pastikan lilitan rapat ke permukaan kayu agar tidak mudah bergeser saat diberi beban tarikan pionering.',
                'gambar' => 'simpul_pangkal.png'
            ],
            [
                'judul' => 'Simpul Jangkar (Cow Hitch)',
                'kategori' => 'simpul',
                'tingkat_kesulitan' => 'dasar',
                'deskripsi' => 'Simpul sederhana berbentuk dua lingkaran simetris yang mencengkeram tiang atau benda bulat silinder.',
                'fungsi' => 'Membuat tandu darurat (dragbar), menambatkan perahu/tali pasak kemah, dan mengikat cincin atau ember timba air.',
                'langkah_langkah' => "1. Bagi dua tali menjadi sebuah kolong/lingkaran (bight).\n2. Kalungkan kolong tersebut mengelilingi tongkat dari arah belakang.\n3. Masukkan kedua utas ujung tali melewati kolong tersebut.\n4. Tarik kedua utas tali secara bersamaan hingga menjepit erat tiang.",
                'tips' => 'Simpul ini bisa dibuat langsung tanpa memasukkan ujung tali jika tongkat memiliki ujung yang terbuka.',
                'gambar' => 'simpul_jangkar.png'
            ],
            [
                'judul' => 'Simpul Mati (Reef / Square Knot)',
                'kategori' => 'simpul',
                'tingkat_kesulitan' => 'dasar',
                'deskripsi' => 'Simpul penyambung dua utas tali yang sama besar dalam keadaan kering dan tidak licin.',
                'fungsi' => 'Menyambung tali pramuka ukuran sama, mengikat perban atau mitela P3K (karena bentuknya yang pipih dan tidak menekan luka).',
                'langkah_langkah' => "1. Pegang ujung tali kiri di tangan kiri dan tali kanan di tangan kanan.\n2. Silangkan ujung kanan di atas kiri, lalu lingkarkan ke bawah (kanan di atas kiri).\n3. Silangkan ujung kiri di atas kanan, lalu lingkarkan ke bawah (kiri di atas kanan).\n4. Tarik kedua ujungnya. Simpul yang benar akan berbentuk dua cincin yang saling mengunci sejajar.",
                'tips' => 'Ingat rumusnya: Kanan di atas Kiri, lalu Kiri di atas Kanan. Jangan sampai tertukar menjadi simpul betina yang mudah lepas.',
                'gambar' => 'simpul_mati.png'
            ],
            [
                'judul' => 'Ikatan Palang (Square Lashing)',
                'kategori' => 'ikatan',
                'tingkat_kesulitan' => 'menengah',
                'deskripsi' => 'Ikatan untuk menyatukan dua tongkat atau balok kayu yang bersilangan tegak lurus membentuk sudut 90 derajat.',
                'fungsi' => 'Membangun rangka menara pandang, tiang bendera 4 kaki, gapura perkemahan, meja makan kemah, dan pagar sanggar.',
                'langkah_langkah' => "1. Awali dengan membuat Simpul Pangkal pada salah satu tongkat (biasanya tiang vertikal di bawah silangan).\n2. Belitkan tali mengelilingi kedua tongkat secara berselang-seling (depan-belakang) sebanyak 3 hingga 4 lilitan rapi dan rapat.\n3. Lakukan frapping (lilitan pengunci) di antara kedua tongkat sebanyak 2-3 lilitan kencang untuk menarik ikatan.\n4. Akhiri ikatan dengan membuat Simpul Pangkal pada tongkat yang berlawanan.",
                'tips' => 'Kekuatan ikatan palang ditentukan oleh kekencangan saat frapping (lilitan tengah). Gunakan berat badan saat menarik tali.',
                'gambar' => 'ikatan_palang.png'
            ],
            [
                'judul' => 'Isyarat Semaphore & Alfabet Lapangan',
                'kategori' => 'semaphore',
                'tingkat_kesulitan' => 'dasar',
                'deskripsi' => 'Metode pengiriman pesan visual jarak jauh menggunakan sepasang bendera berukuran 45x45 cm warna merah-kuning diagonal.',
                'fungsi' => 'Komunikasi visual antar bukit/pos perkemahan tanpa suara, perlombaan teknik kepramukaan (LT), dan sandi regu.',
                'langkah_langkah' => "1. Sikap sempurna, kedua tangan menyilang di bawah setinggi paha (sikap siap).\n2. Lingkaran 1: Huruf A (45° kiri bawah) sampai G.\n3. Lingkaran 2: Huruf H (mendatar kanan) sampai N.\n4. Lingkaran 3: Huruf O (45° kanan atas) sampai S.\n5. Tanda angka: Tangan kanan membentuk huruf B, tangan kiri membentuk huruf D.",
                'tips' => 'Jaga kedua lengan tetap lurus dari bahu ke pergelangan, jangan menekuk siku agar sudut terlihat jelas dari kejauhan.',
                'gambar' => 'semaphore_guide.png'
            ],
            [
                'judul' => 'Sandi Rumput & Sandi Kotak I - II',
                'kategori' => 'sandi',
                'tingkat_kesulitan' => 'menengah',
                'deskripsi' => 'Sandi visual pramuka yang ditransformasikan dari kode morse (sandi rumput) serta kisi-kisi geometris berpasangan (sandi kotak).',
                'fungsi' => 'Pemberian petunjuk penjelajahan (wide game), pos bayangan, dan mengasah ketelitian logika anggota pramuka.',
                'langkah_langkah' => "1. Sandi Rumput: Titik morse diwakili rumput pendek (v), garis morse diwakili rumput tinggi (V).\n2. Pisahkan antar huruf dengan garis bawah horizontal, pisahkan antar kata dengan spasi lebih panjang.\n3. Sandi Kotak 1: Menggunakan silang ganda (#) dan silang X, huruf kedua diberi tanda titik di dalamnya.",
                'tips' => 'Kuasai dulu hafalan sandi morse agar memecahkan sandi rumput menjadi sangat cepat.',
                'gambar' => 'sandi_rumput.png'
            ]
        ];

        $insertTut = $pdo->prepare("INSERT INTO tutorials (judul, kategori, tingkat_kesulitan, deskripsi, fungsi, langkah_langkah, tips, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($tutorials as $tut) {
            $insertTut->execute([
                $tut['judul'],
                $tut['kategori'],
                $tut['tingkat_kesulitan'],
                $tut['deskripsi'],
                $tut['fungsi'],
                $tut['langkah_langkah'],
                $tut['tips'],
                $tut['gambar']
            ]);
        }
    }

    // Seeder Arsip Laporan & Kas Awal
    $stmtArc = $pdo->query("SELECT COUNT(*) as cnt FROM archives");
    if ($stmtArc->fetch()['cnt'] == 0) {
        $archives = [
            [
                'judul' => 'Rencana Program Kerja (Proker) Divisi Tekpram 2026/2027',
                'kategori' => 'proker',
                'file_path' => 'proker_tekpram_2026.pdf',
                'nama_file_asli' => 'PROKER_TEKPRAM_2026_FINAL.pdf',
                'ukuran_file' => '1.4 MB',
                'tipe_file' => 'pdf',
                'keterangan' => 'Dokumen resmi matriks kegiatan mingguan, jadwal perawatan inventaris sanggar, dan target pencapaian SKU/SKK Bantara-Laksana.',
                'nominal_kas' => 0,
                'tanggal_arsip' => '2026-08-15',
                'is_public' => 1
            ],
            [
                'judul' => 'Laporan Pertanggungjawaban (LPJ) Dianpinsat & Kemah Bakti',
                'kategori' => 'kegiatan',
                'file_path' => 'lpj_dianpinsat_kemah_bakti.pdf',
                'nama_file_asli' => 'LPJ_DIANPINSAT_BAKTI.pdf',
                'ukuran_file' => '3.8 MB',
                'tipe_file' => 'pdf',
                'keterangan' => 'Laporan rekapitulasi pelaksanaan Gladhi Pemimpin Satuan, evaluasi materi pionering darurat, serta dokumentasi lapangan.',
                'nominal_kas' => 0,
                'tanggal_arsip' => '2026-10-02',
                'is_public' => 1
            ],
            [
                'judul' => 'Buku Kas Sanggar & Inventarisasi Logistik Triwulan III',
                'kategori' => 'keuangan',
                'file_path' => 'rekap_kas_sanggar_triwulan3.xlsx',
                'nama_file_asli' => 'KAS_DAN_INVENTARIS_Q3_2026.xlsx',
                'ukuran_file' => '650 KB',
                'tipe_file' => 'xlsx',
                'keterangan' => 'Laporan penerimaan uang sewa tenda sanggar, belanja tambang maron dan pasak besi, serta saldo kas aktif divisi.',
                'nominal_kas' => 1450000,
                'tanggal_arsip' => '2026-11-01',
                'is_public' => 1
            ]
        ];

        $insertArc = $pdo->prepare("INSERT INTO archives (judul, kategori, file_path, nama_file_asli, ukuran_file, tipe_file, keterangan, nominal_kas, tanggal_arsip, is_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($archives as $arc) {
            $insertArc->execute([
                $arc['judul'],
                $arc['kategori'],
                $arc['file_path'],
                $arc['nama_file_asli'],
                $arc['ukuran_file'],
                $arc['tipe_file'],
                $arc['keterangan'],
                $arc['nominal_kas'],
                $arc['tanggal_arsip'],
                $arc['is_public']
            ]);
        }
    }
}

// Jalankan inisialisasi tabel & data otomatis
initialize_database($pdo, $db_driver);

/**
 * Helper Functions
 */
function get_setting($pdo, $key, $default = '') {
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? $row['setting_value'] : $default;
}

function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function get_youtube_id($url) {
    if (empty($url)) return '';
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    return '';
}
