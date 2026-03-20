<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}

if (isset($_POST['submit'])) {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $harga = (int)$_POST['harga'];
  $stok = (int)$_POST['stok'];
  $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

  $filename = $_FILES['gambar']['name'];
  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  $allowed = array('jpg', 'jpeg', 'png', 'webp');

  if (in_array($ext, $allowed)) {
    $new_name = time() . "-" . bin2hex(random_bytes(4)) . "." . $ext; // Nama file acak agar tidak bentrok
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "../src/img/" . $new_name)) {
      mysqli_query($koneksi, "INSERT INTO produk (nama, harga, deskripsi, gambar, stok) VALUES ('$nama', '$harga', '$deskripsi', '$new_name', '$stok')");
      echo "<script>window.location.href='index.php?page=menu';</script>";
      exit;
    }
  } else {
    echo "<script>alert('File harus gambar (jpg/png)!');</script>";
  }
}

// Logika Hapus
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $res = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id");
  $data = mysqli_fetch_assoc($res);
  if ($data && $data['gambar'] && file_exists("../src/img/".$data['gambar'])) {
    unlink("../src/img/".$data['gambar']);
  }
  mysqli_query($koneksi, "DELETE FROM produk WHERE id=$id");
  echo "<script>window.location.href='index.php?page=menu';</script>";
  exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
?>

<h2 class="text-2xl font-bold mb-6 text-slate-800">Manajemen Menu</h2>

<div class="mx-auto bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-10">
  <h2 class="text-xl font-bold mb-6 text-slate-800">Tambah Menu Baru</h2>
  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="text" name="nama" placeholder="Nama Menu" class="w-full border p-2 rounded-lg outline-none focus:ring-2 focus:ring-orange-500" required>
    <div class="grid grid-cols-2 gap-4">
      <input type="number" name="harga" placeholder="Harga" class="w-full border p-2 rounded-lg outline-none" required>
      <input type="number" name="stok" placeholder="Stok" class="w-full border p-2 rounded-lg outline-none" required>
    </div>
    <textarea name="deskripsi" rows="4" placeholder="Deskripsi" class="w-full border p-2 rounded-lg outline-none"></textarea>
    <div>
      <label class="text-xs text-gray-500">Foto Menu:</label>
      <input type="file" name="gambar" accept="image/*" class="w-full text-sm mt-1" required>
    </div>
    <button type="submit" name="submit" class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700">Simpan Menu</button>
  </form>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
  <table id="tabelPesanan" class="w-full text-left border-collapse">
    <thead class="bg-slate-50 border-b">
      <tr>
        <th class="p-4 text-sm font-semibold text-slate-600">Gambar</th>
        <th class="p-4 text-sm font-semibold text-slate-600">Nama</th>
        <th class="p-4 text-sm font-semibold text-slate-600">Harga</th>
        <th class="p-4 text-sm font-semibold text-slate-600">Stok</th>
        <th class="p-4 text-sm font-semibold text-slate-600 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      <?php while ($row = mysqli_fetch_assoc($query)): ?>
      <tr class="hover:bg-gray-50">
        <td class="p-4"><img src="../src/img/<?= $row['gambar'] ?>" class="w-12 h-12 object-cover rounded shadow-sm"></td>
        <td class="p-4 text-sm font-medium"><?= htmlspecialchars($row['nama']) ?></td>
        <td class="p-4 text-sm">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
        <td class="p-4 text-sm"><?= htmlspecialchars($row['stok']) ?></td>
        <td class="p-4 text-center">
          <div class="flex justify-center gap-3">
            <a href="index.php?page=menu_edit&id=<?= $row['id'] ?>" class="text-yellow-600 text-sm font-bold">Edit</a>
            <a href="index.php?page=menu&delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-500 text-sm font-bold">Hapus</a>
          </div>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>