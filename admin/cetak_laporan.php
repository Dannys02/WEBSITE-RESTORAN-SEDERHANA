<?php
/**
 * ============================================
 * HALAMAN CETAK/LAPORAN PENJUALAN
 * ============================================
 * Fungsi: Menampilkan laporan semua pesanan yang sudah SELESAI
 *         Dalam format yang bisa dicetak atau save sebagai PDF
 */

// Ambil data hanya yang statusnya 'selesai' (sudah dibayar/selesai)
$query = "SELECT p.*, pr.nama as nama_produk
          FROM pesanan p
          JOIN produk pr ON p.produk_id = pr.id
          WHERE p.status = 'selesai'
          ORDER BY p.created_at DESC";
$result = mysqli_query($koneksi, $query);

$total_omzet = 0;
?>

<style>
    /**
     * CSS untuk menyembunyikan elemen tertentu saat di-print
     * Untuk hasil cetak/PDF yang rapi
     */
    @media print {
        .no-print {
            display: none;
        }
        body {
            padding: 0;
        }
    }
</style>

<!-- Tombol Navigasi & Cetak (Disembunyikan saat print) -->
<div class="no-print mb-10 flex justify-between">
    <a href="index.php?page=dashboard" class="text-blue-600">← Kembali ke Dashboard</a>
    <button onclick="window.print()" class="bg-orange-500 text-white px-6 py-2 rounded shadow">
        Klik untuk Cetak / Save PDF
    </button>
</div>

<!-- Header Laporan -->
<div class="text-center mb-8 border-b-2 border-gray-800 pb-4">
    <h1 class="text-3xl font-black uppercase">Laporan Penjualan Selesai</h1>
    <p class="text-gray-600">
        Dicetak pada: <?= date('d F Y, H:i') ?> WIB
    </p>
</div>

<!-- Tabel Laporan Penjualan -->
<table class="w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-100">
            <th class="border border-gray-300 p-2 text-left">Tanggal</th>
            <th class="border border-gray-300 p-2 text-left">Pembeli</th>
            <th class="border border-gray-300 p-2 text-left">Produk</th>
            <th class="border border-gray-300 p-2 text-right">Qty</th>
            <th class="border border-gray-300 p-2 text-right">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)):
            // Hitung subtotal per baris
            $subtotal = $row['harga'] * $row['stok'];
            $total_omzet += $subtotal;
        ?>
            <tr>
                <!-- Kolom Tanggal Pesanan -->
                <td class="border border-gray-300 p-2 text-sm">
                    <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                </td>

                <!-- Kolom Nama Pembeli -->
                <td class="border border-gray-300 p-2 text-sm">
                    <?= htmlspecialchars($row['nama_pembeli']) ?>
                </td>

                <!-- Kolom Nama Produk -->
                <td class="border border-gray-300 p-2 text-sm">
                    <?= $row['nama_produk'] ?>
                </td>

                <!-- Kolom Jumlah (Qty) -->
                <td class="border border-gray-300 p-2 text-sm text-right">
                    <?= $row['stok'] ?>
                </td>

                <!-- Kolom Total Harga -->
                <td class="border border-gray-300 p-2 text-sm text-right">
                    Rp <?= number_format($subtotal, 0, ',', '.') ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    <!-- Footer Tabel: Total Pendapatan -->
    <tfoot>
        <tr class="bg-gray-200 font-bold">
            <td colspan="4" class="border border-gray-300 p-2 text-right">
                TOTAL PENDAPATAN
            </td>
            <td class="border border-gray-300 p-2 text-right text-orange-600">
                Rp <?= number_format($total_omzet, 0, ',', '.') ?>
            </td>
        </tr>
    </tfoot>
</table>

<!-- Footer Dokumen -->
<div class="mt-10 text-right italic text-sm text-gray-400">
    Dokumen ini dihasilkan secara otomatis oleh Sistem Website Restoran.
</div>