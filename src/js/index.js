document.addEventListener("DOMContentLoaded", function () {
    const boxes = document.querySelectorAll(".from-bottom");

    const observerOptions = {
        root: null, // Mengacu pada viewport
        threshold: 0.1 // Elemen terlihat 15% langsung trigger
    };

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Jika terlihat di layar, tambahkan class active
                entry.target.classList.add("active");
            } else {
                // Jika tidak terlihat (scrolled away), hapus class active agar animasi berulang
                entry.target.classList.remove("active");
            }
        });
    }, observerOptions);

    boxes.forEach(box => {
        observer.observe(box);
    });
});

/**
 * UMKM Katalog Interactive Script
 * Handled: Modal interactions, price calculations, and animations.
 */

const orderModal = document.getElementById("orderModal");
const body = document.body;

function openModal(product) {
    // 1. Masukkan ID Produk
    document.getElementById("produk_id").value = product.id;

    // 2. Masukkan Harga ke input hidden untuk perhitungan
    document.getElementById("harga_modal").value = product.harga;

    // 3. Update Subtitle di modal (Pastikan elemen id="modalSubTitle" ada di HTML)
    const subTitle = document.getElementById("modalSubTitle");
    if (subTitle) {
        subTitle.innerText = product.nama;
    }

    // 4. RESET Jumlah Beli ke 1 setiap buka modal
    document.getElementById("stok_input").value = 1;

    // --- BAGIAN YANG DIHAPUS/DIPERBAIKI ---
    // Jangan panggil harga_display karena di HTML sudah tidak ada id tersebut
    // ---------------------------------------

    // 5. Munculkan Modal
    orderModal.classList.remove("hidden");
    body.style.overflow = "hidden";

    // 6. Jalankan hitung total awal
    hitungTotal();
}

function closeModal() {
    orderModal.classList.add("hidden");
    body.style.overflow = "auto";
}

function hitungTotal() {
    const harga = parseFloat(document.getElementById("harga_modal").value) || 0;
    const jumlah = parseInt(document.getElementById("stok_input").value) || 0;
    const total = harga * jumlah;

    const formattedTotal = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(total);

    document.getElementById("total_harga").innerText = formattedTotal;
}

// Close modal on 'Esc' key
window.addEventListener("keydown", e => {
    if (e.key === "Escape") closeModal();
});

function toggleMobileMenu() {
    const menu = document.getElementById("mobileMenu");
    const sheet = document.getElementById("mobileSheet");
    const body = document.body;

    if (menu.classList.contains("hidden")) {
        // Tampilkan
        menu.classList.remove("hidden");
        setTimeout(() => {
            sheet.style.bottom = "0";
        }, 10);
        body.style.overflow = "hidden"; // Lock scroll
    } else {
        // Sembunyikan
        sheet.style.bottom = "-100%";
        setTimeout(() => {
            menu.classList.add("hidden");
        }, 400); // Tunggu animasi selesai
        body.style.overflow = ""; // Unlock scroll
    }
}