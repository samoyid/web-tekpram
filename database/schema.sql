-- Database Schema & Initial Data: Divisi Teknik Kepramukaan (Tekpram)
-- Compatible with MySQL 5.7+ / 8.0+ / MariaDB

CREATE DATABASE IF NOT EXISTS `tekpram_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tekpram_db`;

-- 1. Tabel Users (Pengurus & Pembina)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Inventaris & Paket Sewa Sanggar
CREATE TABLE IF NOT EXISTS `inventory` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_barang` VARCHAR(150) NOT NULL,
    `kategori` VARCHAR(20) NOT NULL,
    `jenis` VARCHAR(50) DEFAULT 'umum',
    `harga` INT NOT NULL,
    `satuan_durasi` VARCHAR(50) DEFAULT 'perhari',
    `durasi_hari` INT DEFAULT 1,
    `deskripsi` TEXT,
    `rincian_paket` TEXT NULL,
    `foto` VARCHAR(255) NULL,
    `stok` INT DEFAULT 10,
    `is_active` INT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Pustaka Materi & Tutorial Simpul
CREATE TABLE IF NOT EXISTS `tutorials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(150) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `fungsi` TEXT,
    `langkah_langkah` TEXT,
    `tips` TEXT,
    `gambar` VARCHAR(255) NULL,
    `tingkat_kesulitan` VARCHAR(20) DEFAULT 'dasar',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel Arsip Dokumen & Kas
CREATE TABLE IF NOT EXISTS `archives` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(200) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `nama_file_asli` VARCHAR(255),
    `ukuran_file` VARCHAR(50),
    `tipe_file` VARCHAR(20),
    `keterangan` TEXT,
    `nominal_kas` INT DEFAULT 0,
    `tanggal_arsip` DATE NOT NULL,
    `is_public` INT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel Pengaturan Sistem
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(50) NOT NULL UNIQUE,
    `setting_value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Data Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('whatsapp_number', '085851635670'),
('sanggar_name', 'Divisi Teknik Kepramukaan (Tekpram)'),
('gudep_name', 'Sanggar Bakti Gerakan Pramuka'),
('alamat_sanggar', 'Kompleks Sanggar Pramuka, Area Kwarran & Gudep'),
('motto', 'Satyaku Kudarmakan, Darmaku Kubaktikan'),
('syarat_sewa', '1. Menyerahkan kartu identitas (KTA Pramuka / KTP / Kartu Pelajar) sebagai jaminan.\n2. Pembayaran uang muka (DP) minimal 50% saat pengambilan barang.\n3. Keterlambatan pengembalian dikenakan denda sesuai tarif harian.\n4. Barang yang rusak atau hilang wajib diganti sesuai spesifikasi asli.');

-- Default Admin User (username: admin, password: pramuka123)
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`) VALUES
('admin', '$2y$10$wT55lQ09qNskjH.j32t5l.vK6QWJjUj0mX2i3U1zTqJb.sL6yKveq', 'Anggota Tekpram', 'admin');

-- Default Data Inventaris (10 Barang Satuan + 3 Paket Hemat)
INSERT INTO `inventory` (`nama_barang`, `kategori`, `jenis`, `harga`, `satuan_durasi`, `durasi_hari`, `deskripsi`, `rincian_paket`, `foto`, `stok`) VALUES
('Tongkat Pramuka', 'satuan', 'tali_tongkat', 2000, 'perhari', 1, 'Tongkat kayu standar ukuran 160 cm, halus, lurus, dan kokoh untuk pionering maupun baris-berbaris.', NULL, 'tongkat.png', 50),
('Tali Pramuka 10 Meter', 'satuan', 'tali_tongkat', 4000, 'perhari', 1, 'Tali maron serat kuat panjang 10 meter, lentur, ideal untuk ikatan pionering menara dan tiang bendera.', NULL, 'tali10m.png', 30),
('Tali Pramuka 5 Meter', 'satuan', 'tali_tongkat', 2000, 'perhari', 1, 'Tali maron panjang 5 meter untuk simpul dasar, tandu darurat, dan latihan regu.', NULL, 'tali5m.png', 40),
('Semaphore (1 Pasang)', 'satuan', 'aksesoris', 5000, 'perhari', 1, 'Sepasang bendera semaphore ukuran standar merah-kuning cerah lengkap dengan stik kayu.', NULL, 'semaphore.png', 15),
('Tenda Prisma (Tenda Regu)', 'satuan', 'tenda', 25000, 'perhari', 1, 'Tenda prisma klasik kapasitas 6-8 orang, bahan terpal tebal waterproof tahan hujan dan angin lapangan.', NULL, 'tenda_prisma.png', 8),
('Tenda Dome', 'satuan', 'tenda', 35000, 'perhari', 1, 'Tenda dome modern kapasitas 4-5 orang dengan double layer, frame fiber elastis, dan ventilasi jaring anti-nyamuk.', NULL, 'tenda_dome.png', 6),
('10 Pasak Besi', 'satuan', 'tenda', 10000, 'perhari', 1, '1 set berisi 10 buah pasak besi ulir tebal, sangat kuat menancap di tanah keras dan bebatuan.', NULL, 'pasak_besi.png', 20),
('10 Pasak Kayu', 'satuan', 'tenda', 5000, 'perhari', 1, '1 set berisi 10 buah pasak kayu runcing padat, ramah tanah gembur dan perkemahan rumput.', NULL, 'pasak_kayu.png', 25),
('Paku Besar (Tiang/Pionering)', 'satuan', 'tali_tongkat', 1000, 'perhari', 1, 'Paku besi ukuran besar untuk pengikat kuat rangka konstruksi perkemahan dan gapura.', NULL, 'paku.png', 50),
('Ring Kacu Pramuka', 'satuan', 'aksesoris', 8000, 'pcs', 1, 'Ring rotan / kaleng setangan leher pramuka dengan lambang tunas kelapa siap pakai.', NULL, 'ring_kacu.png', 30),
('Paket KEMAH HEMAT', 'paket', 'paket', 90000, 'paket_3hari', 3, 'Paket lengkap dan praktis untuk regu kemah 3 hari 2 malam. Hemat budget dan siap dirikan kemah mandiri.', '1. Tenda Prisma (1 unit)\n2. Pasak Kayu (10 pcs / 1 set)\n3. Tali Pramuka 10 Meter (2 pcs)\n4. Tongkat Pramuka (2 pcs)', 'paket_kemah.png', 5),
('Paket PIONERING MINI', 'paket', 'paket', 30000, 'paket_2hari', 2, 'Paket latihan pionering untuk membuat menara pandang mini, tiang bendera 3 kaki, atau jembatan darurat selama 2 hari.', '1. Tongkat Pramuka (7 pcs)\n2. Tali Pramuka 5 Meter (5 pcs)', 'paket_pionering.png', 8),
('Paket LATIHAN REGU', 'paket', 'paket', 30000, 'paket_2hari', 2, 'Paket latihan mingguan regu penggalang/penegak untuk materi isyarat semaphore, tandu darurat, dan baris tongkat.', '1. Semaphore (3 pasang)\n2. Tongkat Pramuka (3 pcs)\n3. Tali Pramuka 10 Meter (1 pcs)', 'paket_regu.png', 6);
