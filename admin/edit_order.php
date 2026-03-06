<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}

if (isset($_POST['update_order_ini'])) {
  $id_update = (int)$_POST['id'];
  $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_pembeli']);
  $wa      = mysqli_real_escape_string($koneksi, $_POST['whatsapp']);
  $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);
  $stok    = (int)$_POST['stok'];

  $sql = "UPDATE pesanan SET 
            nama_pembeli = '$nama', 
            whatsapp = '$wa', 
            alamat = '$alamat', 
            stok = $stok 
          WHERE id = $id_update";

  if (mysqli_query($koneksi, $sql)) {
    echo "<script>alert('DATA PESANAN BERHASIL DIUPDATE!'); window.location.href='index.php?page=orders';</script>";
    exit;
  } else {
    echo "Error SQL: " . mysqli_error($koneksi);
  }
}

$id_edit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$query = mysqli_query($koneksi, "SELECT p.*, pr.nama as nama_produk FROM pesanan p 
                                 JOIN produk pr ON p.produk_id = pr.id 
                                 WHERE p.id = $id_edit");
$o = mysqli_fetch_assoc($query);

if (!$o) {
  echo "<div class='p-4 bg-red-100 text-red-700 rounded-xl font-bold'>Data Pesanan Tidak Ditemukan! ID: $id_edit</div>";
  exit;
}
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-slate-200">
  <div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Edit Data Pesanan</h2>
        <p class="text-sm text-slate-500 italic">Produk: <?= $o['nama_produk'] ?></p>
    </div>
    <a href="index.php?page=orders" class="text-gray-400 hover:text-red-500 font-semibold transition">Batal</a>
  </div>

  <form action="" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $o['id'] ?>">

    <div>
      <label class="block text-sm font-bold mb-1 text-slate-700">Nama Pembeli</label>
      <input type="text" name="nama_pembeli" value="<?= htmlspecialchars($o['nama_pembeli']) ?>" 
      class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-bold mb-1 text-slate-700">WhatsApp</label>
        <input type="number" name="whatsapp" value="<?= htmlspecialchars($o['whatsapp']) ?>" 
        class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" required>
      </div>
      <div>
        <label class="block text-sm font-bold mb-1 text-slate-700">Jumlah Beli (Stok)</label>
        <input type="number" name="stok" value="<?= (int)$o['stok'] ?>" 
        class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" required>
      </div>
    </div>

    <div>
      <label class="block text-sm font-bold mb-1 text-slate-700">Alamat Lengkap</label>
      <textarea name="alamat" rows="4" 
        class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" required><?= htmlspecialchars($o['alamat']) ?></textarea>
    </div>

    <div class="pt-4">
      <button type="submit" name="update_order_ini" 
        class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-100 hover:bg-orange-600 active:scale-95 transition-all">
        Update Data Pesanan
      </button>
    </div>
  </form>
</div>
