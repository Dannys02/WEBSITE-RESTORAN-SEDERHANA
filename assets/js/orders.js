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

// FUNGSI HANDLE KONFIRMASI & WA
function handleSetuju(url, waLink) {
    if (confirm("Apakah Anda yakin ingin MENYETUJUI pesanan ini?")) {
        // 1. Buka WhatsApp di tab baru
        window.open(waLink, "_blank");
        // 2. Redirect halaman utama untuk proses database
        window.location.href = url;
    }
}
