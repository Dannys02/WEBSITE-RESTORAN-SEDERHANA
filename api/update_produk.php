<?php
session_start();
include '../../config/db.php';

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // 1. Cek apakah ada upload gambar baru
    if ($_FILES['gambar']['name'] != "") {
        $filename = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $new_name = time() . "-" . $filename;
        $path = "../../assets/img/" . $new_name;

        if (move_uploaded_file($tmp_name, $path)) {
            // Hapus gambar lama agar storage tidak penuh
            $res = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id");
            $old_data = mysqli_fetch_assoc($res);
            if ($old_data['gambar'] != '') {
                unlink("../../assets/img/" . $old_data['gambar']);
            }

            // Update dengan gambar baru
            $sql = "UPDATE produk SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi', gambar='$new_name' WHERE id=$id";
        }
    } else {
        // Update tanpa mengganti gambar
        $sql = "UPDATE produk SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi' WHERE id=$id";
    }

    if (mysqli_query($koneksi, $sql)) {
        header("Location: ../index.php?page=produk&status=updated");
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
