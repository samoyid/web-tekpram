<?php
/**
 * Template env.php — salin file ini jadi env.php lalu isi datanya.
 * File env.php TIDAK ter-push ke GitHub.
 *
 * Cara pakai:
 *   cp config/env.example.php config/env.php
 *   lalu edit env.php sesuai kredensial hosting kamu.
 */

// Host MySQL dari InfinityFree (cek di Control Panel > MySQL Databases)
define('ENV_DB_HOST', 'sqlXXX.infinityfree.com');

// Nama database lengkap dengan prefix (contoh: if0_12345678_tekpram)
define('ENV_DB_NAME', 'if0_XXXXXXXX_tekpram');

// Username MySQL (sama dengan username akun InfinityFree, contoh: if0_12345678)
define('ENV_DB_USER', 'if0_XXXXXXXX');

// Password (sama dengan password Control Panel InfinityFree kamu)
define('ENV_DB_PASS', 'password_kamu');

define('ENV_DB_PORT', 3306);

// URL dasar site di hosting (tanpa slash di akhir)
define('BASE_URL', 'https://namasubdomain.free.nf');
