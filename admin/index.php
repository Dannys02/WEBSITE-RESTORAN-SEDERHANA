<?php
define('AKSES_AMAN', true);
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php"); exit;
}
include '../config/db.php';

// Logika penentuan halaman
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
<body class="bg-slate-100 pb-20">

  <main class="max-w-6xl mx-auto p-4 md:p-8">
    <div class="content-wrapper">
      <?php
      switch ($page) {
        case 'dashboard':
          include 'dashboard.php';
          break;
        case 'produk':
          include 'crud_produk.php';
          break;
        case 'produk_edit':
          include 'edit_produk.php';
          break;
        case 'orders':
          include 'orders.php';
          break;
        case 'testimoni':
          include 'crud_testimoni.php';
          break;
        case 'testimoni_edit':
          include 'edit_testimoni.php';
          break;
        default:
          include 'dashboard.php';
          break;
      }
      ?>
    </div>
  </main>

  <nav class="fixed bottom-0 left-0 z-50 w-full bg-white border-t border-gray-100 shadow-lg">
    <div class="flex justify-around items-center h-16">
      <a href="index.php?page=dashboard" class="flex-1 flex flex-col items-center justify-center h-full <?= $page == 'dashboard' ? 'text-blue-600 border-t-2 border-blue-600 bg-blue-50' : 'text-gray-500' ?>">
        <span class="text-[10px] font-bold uppercase">Dashboard</span>
      </a>
      <a href="index.php?page=produk" class="flex-1 flex flex-col items-center justify-center h-full <?= $page == 'produk' ? 'text-blue-600 border-t-2 border-blue-600 bg-blue-50' : 'text-gray-500' ?>">
        <span class="text-[10px] font-bold uppercase">Produk</span>
      </a>
      <a href="index.php?page=orders" class="flex-1 flex flex-col items-center justify-center h-full <?= $page == 'orders' ? 'text-blue-600 border-t-2 border-blue-600 bg-blue-50' : 'text-gray-500' ?>">
        <span class="text-[10px] font-bold uppercase">Pesanan</span>
      </a>
      <a href="index.php?page=testimoni" class="flex-1 flex flex-col items-center justify-center h-full <?= $page == 'testimoni' ? 'text-blue-600 border-t-2 border-blue-600 bg-blue-50' : 'text-gray-500' ?>">
        <span class="text-[10px] font-bold uppercase">Testimoni</span>
      </a>
      <a href="logout.php" class="flex-1 flex flex-col items-center justify-center h-full text-red-500">
        <span class="text-[10px] font-bold uppercase">Keluar</span>
      </a>
    </div>
  </nav>

  <script>
    function handleSetuju(url, waLink) {
      if (confirm('Setujui pesanan dan hubungi via WhatsApp?')) {
        window.open(waLink, '_blank');
        window.location.href = url;
      }
    }
  </script>
  <script src="../assets/js/orders.js"></script>
</body>
</html>