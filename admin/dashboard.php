<?php
// --- LOGIKA HAPUS PRODUK ---
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  // Ambil nama gambar dulu untuk dihapus dari folder
  $res = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id");
  $data = mysqli_fetch_assoc($res);
  if ($data['gambar'] != '') {
    unlink("../assets/img/" . $data['gambar']);
  }

  mysqli_query($koneksi, "DELETE FROM produk WHERE id=$id");
  header("Location: dashboard.php?msg=deleted");
}

$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
?>

<div class="max-w-6xl mx-auto">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Manajemen Produk</h1>
    <div class="flex gap-4">
      <a href="index.php?page=orders" class="bg-gray-500 text-white px-4 py-2 rounded">Lihat Pesanan</a>
      <a href="index.php?page=produk" class="bg-blue-600 text-white px-4 py-2 rounded font-bold">+ Tambah Produk</a>
    </div>
  </div>

  <div class="overflow-hidden">
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-100">
          <tr>
            <th class="p-4">Gambar</th>
            <th class="p-4">Nama Produk</th>
            <th class="p-4">Harga</th>
            <th class="p-4">Stok</th>
            <th class="p-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php while ($row = mysqli_fetch_assoc($query)): ?>
          <tr>
            <td class="p-4">
              <img src="../assets/img/<?= $row['gambar'] ?>" class="w-16 h-16 object-cover rounded border">
            </td>
            <td class="p-4 font-medium"><?= $row['nama'] ?></td>
            <td class="p-4">Rp <?= number_format($row['harga']) ?></td>
            <td class="p-4"><?= $row['stok'] ?></td>
            <td class="p-4 text-center">
              <a href="index.php?page=detail&id=<?= $row['id'] ?>" class="text-blue-600 hover:underline mr-3">Detail</a>
              <a href="index.php?page=edit&id=<?= $row['id'] ?>" class="text-yellow-600 hover:underline mr-3">Edit</a>
              <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="text-red-600 hover:underline">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>