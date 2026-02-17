<?php
if (!defined('AKSES_AMAN')) {
    die('Akses langsung tidak diizinkan!');
}
// Logika Hapus
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id");
    $data = mysqli_fetch_assoc($res);
    if ($data && $data['gambar'] && file_exists("../assets/img/".$data['gambar'])) {
        unlink("../assets/img/".$data['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM produk WHERE id=$id");
    echo "<script>window.location.href='index.php?page=dashboard';</script>";
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
?>
<div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex justify-between items-center">
    <h1 class="text-xl font-bold text-slate-700">Manajemen Produk</h1>
    <a href="index.php?page=produk" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">+ Produk Baru</a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
    <table class="w-full text-left border-collapse">
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
            <tr>
                <td class="p-4"><img src="../assets/img/<?= $row['gambar'] ?>" class="w-12 h-12 object-cover rounded shadow-sm"></td>
                <td class="p-4 text-sm font-medium"><?= htmlspecialchars($row['nama']) ?></td>
                <td class="p-4 text-sm">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td class="p-4 text-sm"><?= htmlspecialchars($row['stok']) ?></td>
                <td class="p-4 text-center">
                    <div class="flex justify-center gap-3">
                        <a href="index.php?page=produk_edit&id=<?= $row['id'] ?>" class="text-yellow-600 text-sm font-bold">Edit</a>
                        <a href="index.php?page=dashboard&delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-500 text-sm font-bold">Hapus</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
