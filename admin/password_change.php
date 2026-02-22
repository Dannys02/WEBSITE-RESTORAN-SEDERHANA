<?php
session_start();
include '../config/db.php';

// Pastikan hanya admin yang login yang bisa akses
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit;
}

if (isset($_POST['update_password'])) {
  $id_admin = $_SESSION['admin_id'];
  $pw_lama = $_POST['password_lama'];
  $pw_baru = $_POST['password_baru'];
  $konfirmasi_pw = $_POST['konfirmasi_password'];

  // 1. Ambil password lama dari DB untuk verifikasi
  $query = mysqli_query($koneksi, "SELECT password FROM admins WHERE id = '$id_admin'");
  $data = mysqli_fetch_assoc($query);

  if (password_verify($pw_lama, $data['password'])) {
    // 2. Cek apakah password baru dan konfirmasi cocok
    if ($pw_baru === $konfirmasi_pw) {
      // 3. Hash password baru
      $password_fixed = password_hash($pw_baru, PASSWORD_DEFAULT);

      // 4. Update ke database
      $update = mysqli_query($koneksi, "UPDATE admins SET password = '$password_fixed' WHERE id = '$id_admin'");

      header("Location: index.php");

      if ($update) {
        $success = "Password berhasil diperbarui!";
      } else {
        $error = "Gagal memperbarui database.";
      }
    } else {
      $error = "Konfirmasi password baru tidak cocok.";
    }
  } else {
    $error = "Password lama yang Anda masukkan salah.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Ubah Password</title>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-5">Ubah Password Admin</h2>

    <?php if (isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='text-green-500 mb-4'>$success</p>"; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block text-sm">Password Lama</label>
        <input type="password" name="password_lama" class="w-full border p-2 rounded" required>
      </div>
      <hr class="my-4">
      <div class="mb-4">
        <label class="block text-sm">Password Baru</label>
        <input type="password" name="password_baru" class="w-full border p-2 rounded" required>
      </div>
      <div class="mb-6">
        <label class="block text-sm">Konfirmasi Password Baru</label>
        <input type="password" name="konfirmasi_password" class="w-full border p-2 rounded" required>
      </div>
      <button type="submit" name="update_password" class="w-full bg-orange-600 text-white py-2 rounded">Simpan Perubahan</button>
      <a href="index.php" class="block text-center mt-4 text-sm text-gray-500">Kembali ke Dashboard</a>
    </form>
  </div>
</body>
</html>