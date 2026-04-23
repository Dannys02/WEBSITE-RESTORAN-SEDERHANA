<?php
/**
 * ============================================
 * API: KIRIM NOTIFIKASI KE TELEGRAM
 * ============================================
 * Fungsi: Mengirim notifikasi ke admin via Telegram
 *         ketika ada pesanan pending baru
 * 
 * Parameter GET:
 * - jumlah: Jumlah pesanan pending yang ingin diinformasikan
 * 
 * Cara kerja:
 * Menggunakan Telegram Bot untuk push notification real-time
 */

// Parameter: Jumlah pesanan pending yang akan dinotifikasi
$jumlah = $_GET['jumlah'];

// Konfigurasi Telegram Bot
$token = "8150467230:AAHpPlZWlVng8wHy7Vgk8wmKipMLFUh1dQg";  // Bot token
$chat_id = "6894989857";                                      // Chat ID admin

// Pesan notifikasi
$pesan = "Admin, ada $jumlah pesanan pending baru dari website!";

// Kirim ke Telegram API
file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($pesan));
?>