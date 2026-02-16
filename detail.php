<?php
include 'config/db.php';
// Pastikan ID aman dari SQL Injection
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Ambil data produk
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id");
$p = mysqli_fetch_assoc($query);

// Redirect jika produk tidak ada
if (!$p) {
  header("Location: index.php");
  exit;
}

// Logika Stok
$isOut = ($p['stok'] <= 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Detail produk <?= htmlspecialchars($p['nama']) ?> - UMKM Berkualitas">
  <title><?= $p['nama'] ?> | Detail Produk TokoSaya</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .orange-gradient {
      background: linear-gradient(135deg, #FF7E5F 0%, #FEB47B 100%);
    }
  </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased">

  <nav class="bg-white/80 backdrop-blur-md border-b border-orange-100 sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4">
      <a href="index.php" class="text-2xl font-extrabold text-orange-600 tracking-tight">Toko<span class="text-slate-900">Saya.</span></a>
    </div>
  </nav>

  <main class="container mx-auto px-4 py-8 md:py-16">
    <nav class="mb-8 flex items-center gap-2 text-sm font-medium text-gray-500">
      <a href="index.php" class="hover:text-orange-500 transition">Katalog</a>
      <span>/</span>
      <span class="text-orange-600">Detail Produk</span>
    </nav>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-orange-100/50 overflow-hidden border border-orange-50">
      <div class="flex flex-col lg:flex-row">

        <div class="lg:w-1/2 p-4 md:p-8">
          <div class="relative group overflow-hidden rounded-[2rem] bg-gray-100 aspect-square">
            <img src="<?= (!empty($p['gambar'])) ? 'assets/img/' . $p['gambar'] : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&q=80&w=800' ?>"
            alt="<?= htmlspecialchars($p['nama']) ?>"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

            <?php if ($isOut): ?>
            <div class="absolute top-6 left-6 bg-red-500 text-white px-6 py-2 rounded-full font-bold shadow-lg">
              Stok Habis
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="lg:w-1/2 p-8 md:p-12 lg:pl-4 flex flex-col justify-center">
          <div class="mb-6">
            <span class="text-orange-500 font-bold uppercase tracking-widest text-xs">Produk Unggulan</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mt-2 mb-4 leading-tight">
              <?= htmlspecialchars($p['nama']) ?>
            </h1>
            <div class="flex items-center gap-4 mb-6">
              <span class="text-3xl font-black text-slate-900">
                Rp <?= number_format($p['harga'], 0, ',', '.') ?>
              </span>
              <div class="h-6 w-[1px] bg-gray-300"></div>
              <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-sm font-bold">
                Stok: <?= $p['stok'] ?> Unit
              </span>
            </div>
          </div>

          <div class="border-t border-gray-100 pt-6 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-3 uppercase tracking-wide">Deskripsi Produk</h3>
            <p class="text-gray-600 leading-relaxed text-lg">
              <?= nl2br(htmlspecialchars($p['deskripsi'])) ?>
            </p>
          </div>

          <div class="flex flex-col sm:flex-row gap-4">
            <button onclick="handleOrder()"
              class="flex-1 orange-gradient text-white py-5 rounded-2xl font-bold text-lg shadow-lg shadow-orange-200 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:hover:scale-100"
              <?= $isOut ? 'disabled' : '' ?>>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              Pesan Sekarang
            </button>
            <a href="https://wa.me/62812345678?text=Halo, saya ingin tanya produk <?= urlencode($p['nama']) ?>"
              target="_blank"
              class="px-8 py-5 border-2 border-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-50 transition flex items-center justify-center">
              Tanya CS
            </a>
          </div>

          <div class="mt-10 grid grid-cols-3 gap-4 border-t border-gray-100 pt-8">
            <div class="text-center">
              <div class="bg-orange-50 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2 text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
              </div>
              <span class="text-[10px] font-bold uppercase text-gray-400">Higienis</span>
            </div>
            <div class="text-center">
              <div class="bg-orange-50 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2 text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
              </div>
              <span class="text-[10px] font-bold uppercase text-gray-400">Segar Cepat</span>
            </div>
            <div class="text-center">
              <div class="bg-orange-50 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2 text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
              </div>
              <span class="text-[10px] font-bold uppercase text-gray-400">Terpercaya</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <script src="assets/js/detail.js"></script>
</body>
</html>