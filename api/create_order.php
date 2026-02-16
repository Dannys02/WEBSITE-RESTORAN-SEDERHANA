<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. CEK HONEYPOT
  if (!empty($_POST['perangkap'])) {
    die("Bot detected!");
  }

  // 2. CEK TIMER (Min 3 detik)
  $load_time = isset($_SESSION['load_time']) ? $_SESSION['load_time'] : 0;
  $selisih = time() - $load_time;
  if ($load_time == 0 || $selisih < 3) {
    die("Bot Detected: Mengisi terlalu cepat!");
  }

  // 3. CEK RATE LIMIT IP (Min 30 detik)
  $user_ip = $_SERVER['REMOTE_ADDR'];
  $last_sub = isset($_SESSION['last_submit']) ? $_SESSION['last_submit'] : 0;
  if (isset($_SESSION['last_ip']) && $_SESSION['last_ip'] == $user_ip && (time() - $last_sub < 30)) {
    echo "<script>(Mohon tunggu 30 detik sebelum memesan lagi.)</script>";
  }

  // Sanitasi Input
  $produk_id = mysqli_real_escape_string($koneksi, $_POST['produk_id']);
  $nama = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['nama_pembeli']));
  $alamat = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['alamat']));
  $stok = (int)$_POST['stok'];
  $harga = (int)$_POST['harga'];
  $wa_pembeli = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);

  if ($stok < 1) {
    echo "<script>alert('Stok tidak valid!'); history.back();</script>";
    exit;
  }

  // CEK APAKAH STOK MASIH ADA (CUMA CEK, GAK NGURANGIN) ---
  $cek_stok = mysqli_query($koneksi, "SELECT stok FROM produk WHERE id = '$produk_id'");
  $data_produk = mysqli_fetch_assoc($cek_stok);

  if (!$data_produk || $data_produk['stok'] < $stok) {
    echo "<script>alert('Maaf, stok tidak mencukupi!'); window.location.href='../index.php';</script>";
    exit;
  }

  // SIMPAN PESANAN (INSERT BIASA) ---
  $query = "INSERT INTO pesanan (nama_pembeli, whatsapp, stok, harga, alamat, produk_id, status)
            VALUES ('$nama', '$wa_pembeli', '$stok', '$harga', '$alamat', '$produk_id', 'pending')";

  if (mysqli_query($koneksi, $query)) {
    // Update session untuk cegah spam
    $_SESSION['last_submit'] = time();
    $_SESSION['last_ip'] = $user_ip;

    // Ambil Nama Produk untuk Pesan WA
    $res = mysqli_query($koneksi, "SELECT nama FROM produk WHERE id='$produk_id' LIMIT 1");
    $p = mysqli_fetch_assoc($res);
    $nama_produk = ($p) ? $p['nama'] : "Produk";

    // Setup WhatsApp
    $nomor_admin = "6285645837298";
    $pesan = "Halo Admin, saya ingin memesan 🙋‍♂️\n"
    . "------------------------------------------\n"
    . "Berikut adalah data pesanan saya:\n\n"
    . "📑 *DETAIL PESANAN*\n"
    . "━━━━━━━━━━━━━━━━━━━━\n"
    . "🍱 *Menu:* " . $nama_produk . "\n"
    . "🔢 *Jumlah:* " . $stok . " pcs\n"
    . "💰 *Total Estimasi:* Rp " . number_format($harga, 0, ',', '.') . "\n"
    . "━━━━━━━━━━━━━━━━━━━━\n\n"
    . "🚚 *DATA PENGIRIMAN*\n"
    . "👤 *Nama:* " . $nama . "\n"
    . "📍 *Alamat:* " . $alamat . "\n"
    . "📱 *WhatsApp:* " . $wa_pembeli . "\n\n"
    . "------------------------------------------\n"
    . "Mohon segera diproses ya Min. Terima kasih! 🙏";

    $url_wa = "https://api.whatsapp.com/send?phone=" . $nomor_admin . "&text=" . urlencode($pesan);

    echo "<script>
                  alert('Pesanan Berhasil Disimpan!');
                  window.location.href='$url_wa';
                  window.location.href = '../index.php';
                </script>";
  } else {
    echo "Error: " . mysqli_error($koneksi);
  }
}