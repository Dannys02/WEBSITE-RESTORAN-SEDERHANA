<?php
if (!defined('AKSES_AMAN')) {
    die('Akses langsung tidak diizinkan!');
}

// 1. LOGIKA UPDATE (Taruh di paling atas biar gak pusing path)
if (isset($_POST['update_produk_ini'])) {
    $id_update = (int)$_POST['id'];
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga     = (int)$_POST['harga'];
    $stok      = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    if (!empty($_FILES['gambar']['name'])) {
        $nama_file = time() . '-' . $_FILES['gambar']['name'];
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $nama_file)) {
            // Hapus gambar lama
            $res_lama = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id_update");
            $data_lama = mysqli_fetch_assoc($res_lama);
            if ($data_lama['gambar'] && file_exists("../assets/img/" . $data_lama['gambar'])) {
                unlink("../assets/img/" . $data_lama['gambar']);
            }
            $sql = "UPDATE produk SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi', gambar='$nama_file' WHERE id=$id_update";
        }
    } else {
        $sql = "UPDATE produk SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi' WHERE id=$id_update";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('BERHASIL UPDATE STOK!'); window.location.href='index.php?page=dashboard';</script>";
        exit;
    } else {
        echo "Error SQL: " . mysqli_error($koneksi);
    }
}

// 2. LOGIKA TAMPIL DATA (Ambil data buat isi form)
$id_edit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id_edit");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    echo "<div class='p-4 bg-red-100'>Produk Gak Ketemu! ID: $id_edit</div>";
    exit;
}
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-slate-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Edit Produk & Stok</h2>
        <a href="index.php?page=dashboard" class="text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">

        <div>
            <label class="block text-sm font-bold mb-1">Nama Produk</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($p['nama']) ?>" class="w-full border p-2 rounded shadow-sm" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="<?= $p['harga'] ?>" class="w-full border p-2 rounded shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1 text-blue-600">Stok</label>
                <input type="number" name="stok" value="<?= $p['stok'] ?>" class="w-full border p-2 rounded shadow-sm" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full border p-2 rounded shadow-sm"><?= htmlspecialchars($p['deskripsi']) ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">Foto (Biarkan jika gak ganti)</label>
            <img src="../assets/img/<?= $p['gambar'] ?>" class="w-20 h-20 object-cover rounded mb-2 border">
            <input type="file" name="gambar" accept="image/*" class="text-sm">
        </div>

        <button type="submit" name="update_produk_ini" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-blue-700">
            SIMPAN PERUBAHAN STOK
        </button>
    </form>
</div>
