<?php
if (isset($_POST['submit'])) {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $harga = (int)$_POST['harga'];
  $stok = (int)$_POST['stok'];
  $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

  // LOGIKA UPLOAD GAMBAR
  $filename = $_FILES['gambar']['name'];
  $tmp_name = $_FILES['gambar']['tmp_name'];
  $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

  // Rename gambar agar unik (mencegah nama file sama)
  $new_name = time() . "-" . $filename;
  $path = "../assets/img/" . $new_name;

  if (move_uploaded_file($tmp_name, $path)) {
    $query = "INSERT INTO produk (nama, harga, deskripsi, gambar, stok)
                  VALUES ('$nama', '$harga', '$deskripsi', '$new_name', '$stok')";
    if (mysqli_query($koneksi, $query)) {
      header("Location: dashboard.php");
    }
  } else {
    echo "Gagal upload gambar!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Tambah Produk</title>
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-6">Tambah Produk Baru</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-4">
        <label class="block mb-1">Nama Produk</label>
        <input type="text" name="nama" class="w-full border p-2 rounded" required>
      </div>
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-1">Harga</label>
          <input type="number" name="harga" class="w-full border p-2 rounded" required>
        </div>
        <div>
          <label class="block mb-1">Stok</label>
          <input type="number" name="stok" class="w-full border p-2 rounded" required>
        </div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border p-2 rounded" rows="3"></textarea>
      </div>
      <div class="mb-6">
        <label class="block mb-1">Foto Produk (Pilih dari Galeri)</label>
        <input type="file" name="gambar" accept="image/*" class="w-full" required>
      </div>
      <div class="flex gap-2">
        <button type="submit" name="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Simpan Produk</button>
        <a href="dashboard.php" class="bg-gray-200 px-6 py-2 rounded">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>