<?php
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $new_name = time() . "-" . $_FILES['gambar']['name'];

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $new_name)) {
        mysqli_query($koneksi, "INSERT INTO produk (nama, harga, deskripsi, gambar, stok) VALUES ('$nama', '$harga', '$deskripsi', '$new_name', '$stok')");
        echo "<script>window.location.href='index.php?page=dashboard';</script>";
        exit;
    }
}
?>
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-slate-200">
    <h2 class="text-xl font-bold mb-6 text-slate-800">Tambah Produk Baru</h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="text" name="nama" placeholder="Nama Produk" class="w-full border p-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
        <div class="grid grid-cols-2 gap-4">
            <input type="number" name="harga" placeholder="Harga" class="w-full border p-2 rounded-lg outline-none" required>
            <input type="number" name="stok" placeholder="Stok" class="w-full border p-2 rounded-lg outline-none" required>
        </div>
        <textarea name="deskripsi" rows="4" placeholder="Deskripsi" class="w-full border p-2 rounded-lg outline-none"></textarea>
        <div>
            <label class="text-xs text-gray-500">Foto Produk:</label>
            <input type="file" name="gambar" accept="image/*" class="w-full text-sm mt-1" required>
        </div>
        <button type="submit" name="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">Simpan Produk</button>
    </form>
</div>
