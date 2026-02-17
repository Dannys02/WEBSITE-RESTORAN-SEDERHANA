<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}

// 1. Hitung Order Pending
$q_pending = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'pending'");
$count_pending = mysqli_fetch_assoc($q_pending)['total'];

// 2. Hitung Total Produk & Stok Menipis (misal stok < 5)
$q_produk = mysqli_query($koneksi, "SELECT COUNT(*) as total, SUM(IF(stok < 5, 1, 0)) as menipis FROM produk");
$res_produk = mysqli_fetch_assoc($q_produk);
$count_produk = $res_produk['total'];
$stok_menipis = $res_produk['menipis'] ?? 0;

// 3. Hitung Testimoni Baru (Gue asumsiin ada tabel 'testimoni')
$q_testi_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM testimoni");
$count_testi = mysqli_fetch_assoc($q_testi_count)['total'];

// 4. Hitung Pendapatan (Februari) - Berdasarkan pesanan yang 'setuju'
$bulan_ini = date('m');
$tahun_ini = date('Y');
// Jika di tabel pesanan lo ada kolom 'tanggal', ini filternya.
// Kalau gak ada, hapus bagian WHERE MONTH... dan ganti ke status saja.
$q_pendapatan = mysqli_query($koneksi, "SELECT SUM(harga * stok) as total FROM pesanan WHERE status = 'setuju'");
$total_pendapatan = mysqli_fetch_assoc($q_pendapatan)['total'] ?? 0;

// 5. Ambil Data Testimoni untuk List
$all_testi = mysqli_query($koneksi, "SELECT * FROM testimoni ORDER BY id DESC LIMIT 5");
?>

<header class="flex justify-between items-center mb-8">
  <h1 class="text-2xl font-bold text-gray-800">Dashboard Toko</h1>
  <div class="flex items-center justify-end">
    <span class="text-sm text-gray-500"><?= date('l, d M Y') ?></span>
  </div>
</header>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <p class="text-gray-500 text-sm">
      Order Pending
    </p>
    <h3 class="text-2xl font-bold text-gray-800"><?= $count_pending ?></h3>
    <a href="index.php?page=orders" class="text-xs text-orange-600 mt-2 inline-block font-semibold underline cursor-pointer">Cek Sekarang →</a>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <p class="text-gray-500 text-sm">
      Total Produk
    </p>
    <h3 class="text-2xl font-bold text-gray-800"><?= $count_produk ?></h3>
    <span class="text-xs text-gray-400 mt-2 inline-block"><?= $stok_menipis ?> Stok Menipis</span>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <p class="text-gray-500 text-sm">
      Total Testimoni
    </p>
    <h3 class="text-2xl font-bold text-gray-800"><?= $count_testi ?></h3>
    <span class="text-xs text-green-500 mt-2 inline-block">Review Pembeli</span>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-sm border border-orange-500">
    <p class="text-gray-500 text-sm">
      Total Pendapatan
    </p>
    <h3 class="text-2xl font-bold text-orange-600">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="font-bold text-gray-800">Testimoni Pembeli</h2>
    </div>
    <div class="space-y-4">
      <?php if (mysqli_num_rows($all_testi) > 0): ?>
      <?php while ($t = mysqli_fetch_assoc($all_testi)): ?>
      <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
        <div class="text-orange-500 mt-1">
          <i class="fa-solid fa-quote-left"></i>
        </div>
        <div>
          <p class="text-xs text-gray-700 italic">
            "<?= htmlspecialchars($t['isi']) ?>"
          </p>
          <p class="text-[10px] text-gray-500 mt-1 font-bold">
            - <?= htmlspecialchars($t['nama_pelanggan']) ?> (Bintang <?= $t['bintang'] ?>)
          </p>
        </div>
      </div>
      <?php endwhile; ?>
      <?php else : ?>
      <p class="text-xs text-gray-400 italic">
        Belum ada testimoni.
      </p>
      <?php endif; ?>
    </div>
  </div>
</div>