<?php
/**
 * ============================================
 * KONFIGURASI KONEKSI DATABASE & API
 * ============================================
 * File ini berisi semua konfigurasi penting:
 * - Database MySQL (MySQLi)
 * - Google reCAPTCHA API Keys
 */

// Database Configuration
$host = "127.0.0.1";          // Server database
$user = "root";               // Username database
$pass = "";                   // Password database
$db   = "penjualan_umkm";     // Nama database

// Google reCAPTCHA Configuration (untuk verifikasi anti-bot)
$siteKey = "6LcBT4wsAAAAAMjKbTsmCm6W-tNGyG-3Ah0FNqOS";
$secretKey = "6LcBT4wsAAAAAL_pBRW0tYfRCa9PQLIhQcb7kbm8";

// Koneksi ke Database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi gagal
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>