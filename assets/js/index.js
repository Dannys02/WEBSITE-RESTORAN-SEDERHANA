/**
 * UMKM Katalog Interactive Script
 * Handled: Modal interactions, price calculations, and animations.
 */

const orderModal = document.getElementById("orderModal");
const body = document.body;

function openModal(product) {
    // Inject Data
    document.getElementById("produk_id").value = product.id;
    document.getElementById("modalSubTitle").innerText = product.nama;
    document.getElementById("harga_modal").value = product.harga;

    // Formatting Display Price
    const formattedPrice = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(product.harga);

    document.getElementById("harga_display").value = formattedPrice;
    document.getElementById("stok_input").value = 1;

    // Show Modal with Animation
    orderModal.classList.remove("hidden");
    body.style.overflow = "hidden"; // Prevent scroll

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
