<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}

// tanggal
date_default_timezone_set('Asia/Jakarta');
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
$formatter->setPattern('EEEE, dd MMM yyyy');

// Hitung order pending
$q_pending = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'pending'");
$count_pending = mysqli_fetch_assoc($q_pending)['total'];

// Hitung total produk & stok menipis
$q_produk = mysqli_query($koneksi, "SELECT COUNT(*) as total, SUM(IF(stok < 5, 1, 0)) as menipis FROM produk");
$res_produk = mysqli_fetch_assoc($q_produk);
$count_produk = $res_produk['total'];
$stok_menipis = $res_produk['menipis'] ?? 0;

// Hitung testimoni baru
$q_testi_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM testimoni");
$count_testi = mysqli_fetch_assoc($q_testi_count)['total'];

// Hitung pendapatan
$q_pendapatan = mysqli_query($koneksi, "SELECT SUM(harga * stok) as total FROM pesanan WHERE status = 'setuju'");
$total_pendapatan = mysqli_fetch_assoc($q_pendapatan)['total'] ?? 0;

$all_testi = mysqli_query($koneksi, "SELECT * FROM testimoni ORDER BY id DESC LIMIT 5");
?>

<header class="flex flex-col md:flex-row md:justify-between md:items-end border-b border-gray-200 pb-4 mb-8">
  <div>
    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
      Dashboard <span class="text-orange-600"><?= $_SESSION['username']; ?></span>
    </h1>
    <p class="text-sm text-gray-400 mt-1 hidden md:block">
      Selamat datang kembali di halaman Admin.
    </p>
  </div>
  <div class="mt-2 md:mt-0 flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <span class="text-sm font-medium text-gray-600 uppercase tracking-wider"><?= $formatter->format(new DateTime()); ?></span>
  </div>
</header>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
  <div class="bg-white p-6 rounded-xl border hover:shadow-sm hover:border-orange-500 transition-colors duration-300 ease">
    <p class="text-gray-500 text-sm">
      Order Pending
    </p>
    <h3 id="count-pending-ui" class="text-2xl font-bold text-gray-800"><?= $count_pending ?></h3>
    <a href="index.php?page=orders" class="text-xs text-orange-600 mt-2 inline-block font-semibold underline cursor-pointer">Cek Sekarang →</a>
  </div>
  <div class="bg-white p-6 rounded-xl border hover:shadow-sm hover:border-orange-500 transition-colors duration-300 ease">
    <p class="text-gray-500 text-sm">
      Total Produk
    </p>
    <h3 class="text-2xl font-bold text-gray-800"><?= $count_produk ?></h3>
    <span class="text-xs text-gray-400 mt-2 inline-block"><?= $stok_menipis ?> Stok Menipis</span>
  </div>
  <div class="bg-white p-6 rounded-xl border hover:shadow-sm hover:border-orange-500 transition-colors duration-300 ease">
    <p class="text-gray-500 text-sm">
      Total Testimoni
    </p>
    <h3 class="text-2xl font-bold text-gray-800"><?= $count_testi ?></h3>
    <span class="text-xs text-green-500 mt-2 inline-block">Review Pembeli</span>
  </div>
  <div class="bg-white p-6 rounded-xl border hover:shadow-sm hover:border-orange-500 transition-colors duration-300 ease">
    <p class="text-gray-500 text-sm">
      Total Pendapatan
    </p>
    <h3 class="text-2xl font-bold text-orange-600">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <div class="bg-white rounded-xl border hover:shadow-sm hover:border-orange-500 transition-colors duration-300 ease p-6">
    <h2 class="font-bold text-gray-800 mb-4">Testimoni Pembeli</h2>
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