const status = document.getElementById("status_sukses");

if (status) {
    status.style.display = "block";

    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        url.searchParams.delete("status");
        window.history.replaceState({ path: url.href }, "", url.href);
    }

    setTimeout(() => {
        status.style.opacity = "0";
        setTimeout(() => {
            status.style.display = "none";
        }, 500);
    }, 5000);
}

/**
 * File: order-handler.js
 * Fungsi untuk menangani konfirmasi pesanan (Setuju/Tolak)
 */

function gasKonfirmasi(urlDatabase, linkWhatsApp, pesanKonfirmasi) {
    // Munculkan alert konfirmasi sesuai parameter
    if (confirm(pesanKonfirmasi)) {
        // 1. Buka link WhatsApp di tab baru
        const waWindow = window.open(linkWhatsApp, '_blank');
        
        // 2. Jika browser berhasil buka tab baru atau bahkan diblokir popup, 
        // kita tetap paksa halaman utama untuk update database
        if (waWindow) {
            waWindow.focus();
        }

        // 3. Eksekusi redirect ke PHP untuk proses MySQL (UPDATE status)
        window.location.href = urlDatabase;
    }
}
