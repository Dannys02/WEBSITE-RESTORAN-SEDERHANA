// 1. Script untuk buka WA otomatis jika ada trigger dari PHP
<?php if (isset($_SESSION['wa_trigger'])): ?>
    window.open('<?= $_SESSION['wa_trigger'] ?>', '_blank');
    <?php unset($_SESSION['wa_trigger']); // Hapus supaya tidak buka berulang kali ?>
<?php endif; ?>

// 2. Script Notifikasi Sukses bawaan kamu
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
        setTimeout(() => { status.style.display = "none"; }, 500);
    }, 5000);
}
