<?php
/**
 * ============================================
 * HALAMAN EDIT DATA PESANAN
 * ============================================
 * Fungsi:
 * - Tampilkan form edit untuk pesanan yang dipilih
 * - Proses update data pesanan (nama, WA, alamat, jumlah)
 * - Sanitasi dan validasi input
 */

if (!defined('AKSES_AMAN')) {
    die('Akses langsung tidak diizinkan!');
}

// ========== PROSES UPDATE PESANAN ==========
if (isset($_POST['update_order_ini'])) {
    $id_update = (int)$_POST['id'];
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_pembeli']);
    $wa      = mysqli_real_escape_string($koneksi, $_POST['whatsapp']);
    $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $stok    = (int)$_POST['stok'];

    // Query update pesanan
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

// ========== AMBIL DATA PESANAN UNTUK DIEDIT ==========
$id_edit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$query = mysqli_query($koneksi, "SELECT p.*, pr.nama as nama_produk FROM pesanan p 
                                 JOIN produk pr ON p.produk_id = pr.id 
                                 WHERE p.id = $id_edit");
$o = mysqli_fetch_assoc($query);

// Cek apakah pesanan ditemukan
if (!$o) {
    echo "<div class='p-4 bg-red-100 text-red-700 rounded-xl font-bold'>Data Pesanan Tidak Ditemukan! ID: $id_edit</div>";
    exit;
}
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-slate-200">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Data Pesanan</h2>
            <p class="text-sm text-slate-500 italic">Produk: <?= $o['nama_produk'] ?></p>
        </div>
        <a href="index.php?page=orders" class="text-gray-400 hover:text-red-500 font-semibold transition">Batal</a>
    </div>

    <!-- Form Edit Pesanan -->
    <form action="" method="POST" class="space-y-4">
        <!-- Hidden: ID Pesanan -->
        <input type="hidden" name="id" value="<?= $o['id'] ?>">

        <!-- Input: Nama Pembeli -->
        <div>
            <label class="block text-sm font-bold mb-1 text-slate-700">Nama Pembeli</label>
            <input type="text" name="nama_pembeli" 
                value="<?= htmlspecialchars($o['nama_pembeli']) ?>" 
                class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                required>
        </div>

        <!-- Input: WhatsApp & Jumlah Stok -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1 text-slate-700">WhatsApp</label>
                <input type="number" name="whatsapp" 
                    value="<?= htmlspecialchars($o['whatsapp']) ?>" 
                    class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                    required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1 text-slate-700">Jumlah Beli (Stok)</label>
                <input type="number" name="stok" 
                    value="<?= (int)$o['stok'] ?>" 
                    class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                    required>
            </div>
        </div>

        <!-- Input: Alamat Lengkap -->
        <div>
            <label class="block text-sm font-bold mb-1 text-slate-700">Alamat Lengkap</label>
            <textarea name="alamat" rows="4" 
                class="w-full border border-slate-200 p-3 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                required><?= htmlspecialchars($o['alamat']) ?></textarea>
        </div>

        <!-- Tombol Submit -->
        <div class="pt-4">
            <button type="submit" name="update_order_ini" 
                class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-100 hover:bg-orange-600 active:scale-95 transition-all">
                Update Data Pesanan
            </button>
        </div>
    </form>
</div>
