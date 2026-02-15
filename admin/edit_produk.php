<?php
// Ambil ID dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID Produk tidak valid";
    exit;
}

// Ambil data produk lama
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    echo "Produk tidak ditemukan";
    exit;
}
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Edit Produk</h2>
        <a href="index.php?page=produk" class="text-slate-500 hover:text-slate-700 text-sm">Kembali</a>
    </div>

    <form action="api/update_produk.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Produk</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($p['nama']) ?>" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="<?= $p['harga'] ?>" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Stok</label>
                <input type="number" name="stok" value="<?= $p['stok'] ?>" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"><?= htmlspecialchars($p['deskripsi']) ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Foto Produk</label>
            <div class="flex items-center gap-4 mb-2">
                <img src="../assets/img/<?= $p['gambar'] ?>" class="w-20 h-20 object-cover rounded border">
                <p class="text-xs text-slate-500 italic">Gambar saat ini</p>
            </div>
            <input type="file" name="gambar" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-[10px] text-slate-400 mt-1">*Kosongkan jika tidak ingin mengganti foto</p>
        </div>

        <div class="pt-4">
            <button type="submit" name="update" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-md">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
