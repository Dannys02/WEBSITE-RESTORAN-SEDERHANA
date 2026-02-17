// Script Notifikasi Sukses bawaan kamu
const status = document.getElementById("status_sukses");
if (status) {
    status.style.display = "block";
    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        url.searchParams.delete("status");
        window.history.replaceState(
            {
                path: url.href
            },
            "",
            url.href
        );
    }
    setTimeout(() => {
        status.style.opacity = "0";
        setTimeout(() => {
            status.style.display = "none";
        }, 500);
    }, 5000);
}

/**
 * Fungsi Utama untuk menangani alur Pesanan
 */
function prosesPesanan(
    tipe,
    id,
    qtyOrder,
    stokReady,
    phone,
    encodedMsg,
    namaProduk
) {
    const actionText = tipe === "setuju" ? "Setujui" : "Tolak";

    if (!confirm(actionText + " pesanan ini?")) return;

    if (tipe === "setuju") {
        // Cek Stok Instan di sisi client
        if (stokReady < qtyOrder) {
            alert(
                "Gagal! Stok " +
                    namaProduk +
                    " tidak mencukupi.\nStok tersedia: " +
                    stokReady +
                    "\nJumlah pesanan: " +
                    qtyOrder
            );
            return; // Berhenti disini, WA tidak akan terbuka
        }
    }

    // Jika lolos validasi stok (atau jika tipenya 'tolak')
    // Buka WhatsApp
    const waUrl =
        "https://api.whatsapp.com/send?phone=" + phone + "&text=" + encodedMsg;
    window.open(waUrl, "_blank");

    // Arahkan halaman utama untuk proses database
    window.location.href = "index.php?page=orders&action=" + tipe + "&id=" + id;
}
