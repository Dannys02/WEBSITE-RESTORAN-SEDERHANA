<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "penjualan_umkm";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

<!--
$host = "sql107.infinityfree.com";
$user = "if0_41189890";
$pass = "O1P5m478YDrwwP";
$db   = "if0_41189890_penjualan_umkm";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
-->