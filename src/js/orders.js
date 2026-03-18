/**
 * Fungsi untuk memproses status ke database (Tanpa Direct WA)
 */
function ubahStatus(tipe, id, qty = 0, stok = 0, nama = "") {
    if (!confirm(tipe.toUpperCase() + " pesanan ini?")) return;

    // Cek stok khusus untuk aksi setuju
    if (tipe === "setuju" && stok < qty) {
        alert("Gagal! Stok " + nama + " tidak mencukupi.");
        return;
    }

    // Redirect untuk proses database saja
    window.location.href = "index.php?page=orders&action=" + tipe + "&id=" + id;
}

function confirmSelesai(id) {
    if (confirm("Selesaikan pesanan ini? Pendapatan akan masuk.")) {
        window.location.href =
            "index.php?page=orders&action=selesai&id=" + id;
    }
}

function confirmBatal(id) {
    if (confirm("Batalkan pesanan ini? Stok akan dikembalikan otomatis.")) {
        window.location.href =
            "index.php?page=orders&action=dibatalkan&id=" + id;
    }
}
