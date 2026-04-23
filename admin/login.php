<?php
/**
 * ============================================
 * HALAMAN LOGIN ADMIN
 * ============================================
 * Proses:
 * 1. Cek session - jika sudah login, redirect ke dashboard
 * 2. Validasi form login (username + password)
 * 3. Verifikasi password dengan database
 * 4. Set session jika login berhasil
 */

session_start();
include '../config/db.php';

// Cek apakah admin sudah login - jika ya, langsung ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

// Proses form login
if (isset($_POST['login'])) {
    // Sanitasi input username (cegah SQL injection)
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Query cari admin berdasarkan username
    $query = mysqli_query($koneksi, "SELECT * FROM admins WHERE username = '$username'");

    // Cek apakah username ditemukan
    if (mysqli_num_rows($query) === 1) {
        $admin = mysqli_fetch_assoc($query);

        // Verifikasi password (password di-hash dengan password_verify)
        if (password_verify($password, $admin['password'])) {
            // Login berhasil - set session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];

            // Redirect ke dashboard
            header("Location: index.php");
            exit;
        } else {
            // Password salah
            $error = "Password yang kamu masukkan salah.";
        }
    } else {
        // Username tidak ditemukan
        $error = "Username tidak terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login Admin</title>
</head>
<body class="bg-gray-200 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Login Admin</h2>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-sm mb-4"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <!-- Input Username -->
            <div class="mb-4">
                <label class="block text-sm font-semibold">Username</label>
                <input type="text" name="username" 
                    class="w-full border p-2 rounded focus:outline-orange-500" 
                    required>
            </div>

            <!-- Input Password -->
            <div class="mb-6">
                <label class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" 
                    class="w-full border p-2 rounded focus:outline-orange-500" 
                    required>
            </div>

            <!-- Tombol Login -->
            <button type="submit" name="login" 
                class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>