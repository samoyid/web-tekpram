<?php
require_once __DIR__ . '/../config/database.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Silakan isi username dan password pengurus.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Username atau kata sandi tidak cocok. Silakan periksa kembali.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengurus - Divisi Tekpram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #26170F; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-amber-600 text-white shadow-md text-xl mb-3">
                <i class="fa-solid fa-compass"></i>
            </div>
            <h1 class="font-bold text-xl text-white">Panel Pengurus Tekpram</h1>
            <p class="text-xs text-stone-400 mt-0.5">Satyaku Kudarmakan, Darmaku Kubaktikan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-7 border border-stone-200">
            <h2 class="font-bold text-base text-stone-900 mb-1">Masuk ke Sistem</h2>
            <p class="text-xs text-stone-500 mb-5">Akses khusus pengelolaan alat, arsip, dan materi.</p>

            <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="space-y-3.5">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Username Pengurus:</label>
                    <input type="text" name="username" id="username" required placeholder="admin" class="w-full text-xs px-3 py-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Kata Sandi:</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full text-xs px-3 py-2.5 rounded-lg border border-stone-300 focus:outline-none focus:ring-1 focus:ring-stone-800">
                </div>

                <button type="submit" class="w-full bg-stone-900 hover:bg-stone-800 text-white font-semibold text-xs py-3 px-4 rounded-lg shadow transition duration-200">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-stone-100 bg-stone-50 p-3 rounded-lg text-xs text-stone-600">
                <div class="flex items-center justify-between font-semibold mb-1">
                    <span>Akun Default:</span>
                    <button type="button" onclick="fillDemo()" class="text-amber-800 underline text-[11px]">Isi Otomatis</button>
                </div>
                <div class="font-mono text-[11px] text-stone-500">
                    admin / pramuka123
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="../index.php" class="text-xs text-stone-500 hover:text-stone-800 transition">
                    &larr; Kembali ke Beranda Publik
                </a>
            </div>
        </div>

        <p class="text-center text-[11px] text-stone-500 mt-5">
            &copy; <?= date('Y') ?> Divisi Teknik Kepramukaan (Tekpram)
        </p>
    </div>

    <script>
        function fillDemo() {
            document.getElementById('username').value = 'admin';
            document.getElementById('password').value = 'pramuka123';
        }
    </script>

</body>
</html>
