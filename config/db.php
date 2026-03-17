<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "penjualan_umkm";
$siteKey = "6LcBT4wsAAAAAMjKbTsmCm6W-tNGyG-3Ah0FNqOS";
$secretKey = "6LcBT4wsAAAAAL_pBRW0tYfRCa9PQLIhQcb7kbm8";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>