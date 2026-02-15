<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php"); exit;
}
include '../config/db.php';

// Logika penentuan halaman (Seperti Route di Laravel)
$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Admin UMKM - <?= ucfirst($page) ?></title>
</head>
<body class="bg-slate-100 p-4">
  <header class="flex justify-between items-center">
    <h2 class="text-2xl font-bold ">Admin UMKM</h2>
    <nav class="flex items-center space-x-4">
      <a href="index.php?page=dashboard" class="cursor-pointer block p-3 rounded <?= $page == 'dashboard' ? 'bg-blue-600 text-white' : 'hover:bg-blue-100' ?>">Dashboard</a>
      <a href="index.php?page=produk" class="cursor-pointer block p-3 rounded <?= $page == 'produk' ? 'bg-blue-600 text-white' : 'hover:bg-blue-100' ?>">Data Produk</a>
      <a href="index.php?page=orders" class="cursor-pointer block p-3 rounded <?= $page == 'orders' ? 'bg-blue-600 text-white' : 'hover:bg-blue-100' ?>">Pesanan Masuk</a>
      <a href="logout.php" class="cursor-pointer block p-3 text-red-400 hover:text-red-300">Keluar</a>
    </nav>
  </header>
  <main class="flex-1 p-8">
    <div class="content-wrapper">
      <?php
      switch ($page) {
        case 'produk':
          include 'crud_produk.php';
          break;
        case 'edit_produk':
          include 'edit_produk.php';
          break;
        case 'orders':
          include 'orders.php';
          break;
        default:
          include 'dashboard.php';
          break;
      }
      ?>
    </div>
  </main>

</body>
</html>