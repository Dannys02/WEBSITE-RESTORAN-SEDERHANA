/**
 * ============================================
 * SCRIPT JAVASCRIPT - HALAMAN MANAJEMEN ORDERS (PESANAN)
 * ============================================
 * Fungsi-fungsi untuk interaksi UI di halaman orders (pesanan)
 */

/**
 * Fungsi: ubahStatus()
 * Tujuan: Mengubah status pesanan ke database
 * 
 * Parameter:
 * - tipe: Jenis status perubahan (setuju, selesai, tolak, dibatalkan)
 * - id: ID pesanan yang akan diubah
 * - qty: Jumlah pesanan (untuk validasi stok)
 * - stok: Stok tersedia di database
 * - nama: Nama produk (untuk pesan error)
 */
function ubahStatus(tipe, id, qty = 0, stok = 0, nama = "") {
    // Minta konfirmasi dari admin sebelum melakukan perubahan
    if (!confirm(tipe.toUpperCase() + " pesanan ini?")) return;

    // Validasi khusus untuk aksi 'setuju' - cek stok
    if (tipe === "setuju" && stok < qty) {
        alert("Gagal! Stok " + nama + " tidak mencukupi.");
        return;
    }

    // Redirect ke halaman dengan parameter action & id untuk proses database
    window.location.href = "index.php?page=orders&action=" + tipe + "&id=" + id;
}

/**
 * Fungsi: confirmSelesai()
 * Tujuan: Konfirmasi penyelesaian pesanan
 * 
 * Parameter:
 * - id: ID pesanan yang akan diselesaikan
 */
function confirmSelesai(id) {
    if (confirm("Selesaikan pesanan ini? Pendapatan akan masuk.")) {
        window.location.href = "index.php?page=orders&action=selesai&id=" + id;
    }
}

/**
 * Fungsi: confirmBatal()
 * Tujuan: Konfirmasi pembatalan pesanan
 * 
 * Parameter:
 * - id: ID pesanan yang akan dibatalkan
 * 
 * Catatan: Stok akan otomatis dikembalikan ke database
 */
function confirmBatal(id) {
    if (confirm("Batalkan pesanan ini? Stok akan dikembalikan otomatis.")) {
        window.location.href = "index.php?page=orders&action=dibatalkan&id=" + id;
    }
}
