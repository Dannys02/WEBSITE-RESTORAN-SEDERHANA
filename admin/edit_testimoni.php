<?php
/**
 * ============================================
 * HALAMAN EDIT TESTIMONI PELANGGAN
 * ============================================
 * Fungsi:
 * - Tampilkan form edit untuk testimoni yang dipilih
 * - Proses update data testimoni
 * - Sanitasi dan validasi input
 */

if (!defined('AKSES_AMAN')) {
    die('Akses langsung tidak diizinkan!');
}

// ========== PROSES UPDATE TESTIMONI ==========
if (isset($_POST['update_testimoni_ini'])) {
    $id_update = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $kerja = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $bintang = (int)$_POST['bintang'];

    // Query update testimoni
    $sql = "UPDATE testimoni SET
                nama_pelanggan = '$nama',
                pekerjaan = '$kerja',
                isi = '$isi',
                bintang = '$bintang'
            WHERE id = $id_update";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('DATA TESTIMONI BERHASIL DIUPDATE!'); window.location.href='index.php?page=testimoni';</script>";
        exit;
    } else {
        echo "Error SQL: " . mysqli_error($koneksi);
    }
}

// ========== AMBIL DATA TESTIMONI UNTUK DIEDIT ==========
$id_edit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$query = mysqli_query($koneksi, "SELECT * FROM testimoni WHERE id = $id_edit");
$t = mysqli_fetch_assoc($query);

// Cek apakah testimoni ditemukan
if (!$t) {
    echo "<div class='p-4 bg-red-100 rounded-xl'>Testimoni Gak Ketemu! ID: $id_edit</div>";
    exit;
}
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-slate-200">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Edit Testimoni Pelanggan</h2>
        <a href="index.php?page=testimoni" class="text-gray-500 hover:text-orange-500 transition">Batal</a>
    </div>

    <!-- Form Edit Testimoni -->
    <form action="" method="POST" class="space-y-4">
        <!-- Hidden: ID Testimoni -->
        <input type="hidden" name="id" value="<?= $t['id'] ?>">

        <!-- Input: Nama Pelanggan -->
        <div>
            <label class="block text-sm font-bold mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" 
                value="<?= htmlspecialchars($t['nama_pelanggan']) ?>"
                class="w-full border border-slate-200 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                required>
        </div>

        <!-- Input: Pekerjaan & Rating Bintang -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1">Pekerjaan</label>
                <input type="text" name="pekerjaan" 
                    value="<?= htmlspecialchars($t['pekerjaan']) ?>"
                    class="w-full border border-slate-200 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                    required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Rating Bintang</label>
                <select name="bintang" 
                    class="w-full border border-slate-200 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= ($t['bintang'] == $i) ? 'selected' : '' ?>>
                            <?= str_repeat('⭐', $i) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <!-- Input: Isi Testimoni -->
        <div>
            <label class="block text-sm font-bold mb-1">Isi Testimoni</label>
            <textarea name="isi" rows="5"
                class="w-full border border-slate-200 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 outline-none transition" 
                required><?= htmlspecialchars($t['isi']) ?></textarea>
        </div>

        <!-- Tombol Submit -->
        <div class="pt-4">
            <button type="submit" name="update_testimoni_ini"
                class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-100 hover:bg-orange-600 active:scale-95 transition-all">
                Simpan Update
            </button>
        </div>
    </form>
</div>