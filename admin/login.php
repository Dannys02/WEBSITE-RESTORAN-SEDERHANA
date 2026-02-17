<?php
session_start();
include '../config/db.php';

// Jika sudah login, tendang ke index
if (isset($_SESSION['admin_logged_in'])) {
  header("Location: index.php");
  exit;
}

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = $_POST['password'];

  // 1. Cari user berdasarkan username
  $query = mysqli_query($koneksi, "SELECT * FROM admins WHERE username = '$username'");

  if (mysqli_num_rows($query) === 1) {
    $admin = mysqli_fetch_assoc($query);

    // 2. Verifikasi password hash
    if (password_verify($password, $admin['password'])) {
      // Login Berhasil (Seperti Breeze: Simpan data ke session)
      $_SESSION['admin_logged_in'] = true;
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['username'] = $admin['username'];

      header("Location: index.php");
      exit;
    } else {
      $error = "Password yang kamu masukkan salah.";
    }
  } else {
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
    <?php if (isset($error)) echo "<p class='text-red-500 text-sm mb-4'>$error</p>"; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block text-sm font-semibold">Username</label>
        <input type="text" name="username" class="w-full border p-2 rounded focus:outline-orange-500" required>
      </div>
      <div class="mb-6">
        <label class="block text-sm font-semibold">Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded focus:outline-orange-500" required>
      </div>
      <button type="submit" name="login" class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700">Masuk</button>
    </form>
  </div>
</body>
</html>