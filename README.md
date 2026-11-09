# Web Tekpram — Ambalan Bisma-Srikandi

Website divisi Teknik Kepramukaan (Tekpram) Gudep 04.001-04.002, SMKN 1 Probolinggo.

Fitur:
- Katalog sewa inventaris sanggar + kalkulator harga + checkout via WhatsApp
- Tutorial simpul, sandi, dan materi kepramukaan beserta video YouTube
- Arsip laporan & kas (khusus pengurus, tidak tampil publik)
- Panel admin untuk kelola inventaris, materi, arsip, dan pengaturan

---

## Cara Install (Laragon)

### 1. Clone repo ke folder www Laragon

```
git clone https://github.com/samoyid/web-tekpram.git "web tekpram"
```

> Clone ke `C:\laragon\www\web tekpram\`

### 2. Buat database di phpMyAdmin

1. Buka `http://localhost/phpmyadmin`
2. Buat database baru: `tekpram_db`
3. Import file: `database/schema.sql`

### 3. Sesuaikan konfigurasi database (opsional)

Buka `config/database.php`, pastikan bagian ini sesuai:

```php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tekpram_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Buka di browser

```
http://localhost/web%20tekpram/
```

### 5. Login Admin

- URL: `http://localhost/web%20tekpram/admin/`
- Username: `admin`
- Password: `pramuka123`

---

## Struktur Folder

```
web tekpram/
├── admin/          # Panel admin (login required)
├── assets/         # CSS, JS, gambar
│   └── images/
│       └── logo.png
├── config/
│   └── database.php
├── database/
│   └── schema.sql  # Import ke MySQL via phpMyAdmin
├── includes/       # Header & footer
├── uploads/        # File upload (arsip, foto inventaris, dll)
├── index.php       # Beranda publik
├── sewa.php        # Katalog sewa inventaris
├── tutorial.php    # Tutorial kepramukaan
└── tutorial-detail.php
```

---

## Kontak

WhatsApp Sanggar: [085851635670](https://wa.me/6285851635670)
