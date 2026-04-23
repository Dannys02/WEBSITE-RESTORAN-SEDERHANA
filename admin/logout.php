<?php
/**
 * ============================================
 * PROSES LOGOUT ADMIN
 * ============================================
 * Menghapus semua session admin dan redirect ke halaman login
 */

session_start();

// Bersihkan semua isi session
$_SESSION = [];

// Hapus session
session_unset();

// Destroy session di server
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
