<?php
/**
 * ============================================
 * PROSES PEMBUATAN PESANAN BARU
 * ============================================
 * Langkah-langkah validasi:
 * 1. Verifikasi reCAPTCHA (anti-bot Google)
 * 2. Deteksi bot silent (perangkap form)
 * 3. Rate limiting (max 5 order/menit per session)
 * 4. Sanitasi & validasi input
 * 5. Cek ketersediaan stok
 * 6. Simpan ke database dengan Prepared Statement
 * 7. Kirim notifikasi WhatsApp ke admin & pelanggan
 */

session_start();
include '../config/db.php';

// Ambil nomor admin WhatsApp untuk notifikasi
$q_wa = mysqli_query($koneksi, "SELECT nomor FROM admins_wa LIMIT 1");
$row_wa = mysqli_fetch_assoc($q_wa);
$nomor_tujuan = $row_wa['nomor'] ?? '6285645837298';

// ========== PROSES ORDER (HANYA JIKA POST) ==========
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // ===== LANGKAH 1: VERIFIKASI reCAPTCHA (Anti-Bot) =====
    if (!isset($_POST['g-recaptcha-response'])) {
        die("Captcha wajib diisi.");
    }

    $secret = "6LcBT4wsAAAAAL_pBRW0tYfRCa9PQLIhQcb7kbm8";
    $response = $_POST['g-recaptcha-response'];

    // Verifikasi token reCAPTCHA ke server Google
    $verify = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response"
    );
    $captcha = json_decode($verify);

    if (!$captcha->success) {
        echo "<script>alert('Mohon verifikasi bahwa kamu bukan robot.'); history.back();</script>";
        exit;
    }

    // ===== LANGKAH 2: DETEKSI BOT SILENT =====
    // Honeypot field - jika di-isi, berarti bot
    if (!empty($_POST['perangkap'])) {
        die("Bot detected!");
    }

    // ===== LANGKAH 3: CEK KECEPATAN PENGIRIMAN (Anti-Spam) =====
    $load_time = $_SESSION['load_time'] ?? 0;
    if ((time() - $load_time) < 2) {
        die("Terlalu cepat!");
    }

    // ===== LANGKAH 4: RATE LIMITING (Max 5 order per menit) =====
    if (!isset($_SESSION['order_count'])) {
        $_SESSION['order_count'] = 0;
        $_SESSION['first_order_time'] = time();
    }

    // Reset counter jika sudah lewat 1 menit
    if (time() - $_SESSION['first_order_time'] > 60) {
        $_SESSION['order_count'] = 0;
        $_SESSION['first_order_time'] = time();
    }

    // Cek jika sudah mencapai limit
    if ($_SESSION['order_count'] >= 5) {
        echo "<script>alert('Mohon tunggu 1 menit sebelum memesan lagi.'); history.back();</script>";
        exit;
    }

    // ===== LANGKAH 5: SANITASI & VALIDASI INPUT =====
    $produk_id = (int)$_POST['produk_id'];
    $nama = trim(htmlspecialchars($_POST['nama_pembeli']));
    $wa_pembeli = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);
    $stok = (int)$_POST['stok'];
    $harga = (int)$_POST['harga'];
    $alamat = trim(htmlspecialchars($_POST['alamat']));

    // Validasi nama minimal 3 karakter
    if (strlen($nama) < 3) {
        echo "<script>alert('Nama minimal 3 karakter!'); history.back();</script>";
        exit;
    }

    // Validasi nomor WhatsApp (10-14 digit)
    if (strlen($wa_pembeli) < 10 || strlen($wa_pembeli) > 14) {
        echo "<script>alert('WA harus 10-14 digit!'); history.back();</script>";
        exit;
    }

    // Validasi alamat minimal 10 karakter
    if (strlen($alamat) < 10) {
        echo "<script>alert('Alamat minimal 10 karakter!'); history.back();</script>";
        exit;
    }

    // ===== LANGKAH 6: CEK STOK PRODUK (Dengan Prepared Statement) =====
    $stmt_stok = mysqli_prepare($koneksi, "SELECT nama, stok FROM produk WHERE id = ?");
    mysqli_stmt_bind_param($stmt_stok, "i", $produk_id);
    mysqli_stmt_execute($stmt_stok);
    $data_produk = mysqli_stmt_get_result($stmt_stok)->fetch_assoc();

    // Cek apakah produk ada dan stok cukup
    if (!$data_produk || $data_produk['stok'] < $stok) {
        echo "<script>alert('Maaf, stok tidak mencukupi!'); window.location.href='../store#menu';</script>";
        exit;
    }

    // ===== LANGKAH 7: SIMPAN PESANAN KE DATABASE =====
    $query = "INSERT INTO pesanan (nama_pembeli, whatsapp, stok, harga, alamat, produk_id, status, user_ip) 
              VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";
    $stmt_ins = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt_ins, "ssiisis", $nama, $wa_pembeli, $stok, $harga, $alamat, $produk_id, $user_ip);

    if (mysqli_stmt_execute($stmt_ins)) {
        $_SESSION['order_count']++;
        $nama_produk = $data_produk['nama'];

        // ===== LANGKAH 8: BUAT PESAN NOTIFIKASI UNTUK ADMIN =====
        $nomor_admin = $nomor_tujuan;
        $pesan = "Halo Admin, saya ingin memesan 🙋‍♂️\n"
            . "------------------------------------------\n"
            . "Berikut adalah data pesanan saya:\n\n"
            . "📑 *DETAIL PESANAN*\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "🍱 *Menu:* " . $nama_produk . "\n"
            . "🔢 *Jumlah:* " . $stok . " porsi\n"
            . "💰 *Total Estimasi:* Rp " . number_format($harga, 0, ',', '.') . "\n"
            . "━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🚚 *DATA PENGIRIMAN*\n"
            . "👤 *Nama:* " . $nama . "\n"
            . "📍 *Alamat:* " . $alamat . "\n"
            . "📱 *WhatsApp:* " . $wa_pembeli . "\n\n"
            . "------------------------------------------\n"
            . "Mohon segera diproses ya Min. Terima kasih! 🙏";

        // Encode pesan untuk URL (WhatsApp API)
        $url_wa = "https://api.whatsapp.com/send?phone=" . $nomor_admin . "&text=" . urlencode($pesan);

        // ===== LANGKAH 9: REDIRECT KE WhatsApp ADMIN & KEMBALI KE HALAMAN UTAMA =====
        echo "<script>
                alert('Pesanan Berhasil Dikirim, hubungi dan tunggu respon Admin!');
                window.location.href = '$url_wa';
                window.location.href = '/store#menu';
              </script>";
    } else {
        echo "Error: Gagal menyimpan pesanan.";
    }
}