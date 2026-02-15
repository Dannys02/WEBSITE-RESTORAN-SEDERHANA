<?php
include 'config/db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id");
$p = mysqli_fetch_assoc($query);

if (!$p) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= $p['nama'] ?> - Detail Produk</title>
</head>
<body class="bg-slate-50">
    <div class="max-w-4xl mx-auto p-6 md:p-12">
        <a href="index.php" class="text-blue-600 mb-6 inline-block font-medium">&larr; Kembali ke Katalog</a>
        
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col md:flex-row border border-slate-200">
            <div class="md:w-1/2">
                <img src="assets/img/<?= $p['gambar'] ?>" class="w-full h-full object-cover">
            </div>
            
            <div class="md:w-1/2 p-8">
                <h1 class="text-3xl font-bold text-slate-800 mb-2"><?= $p['nama'] ?></h1>
                <p class="text-2xl text-green-600 font-bold mb-4">Rp <?= number_format($p['harga']) ?></p>
                <div class="bg-slate-100 p-2 rounded mb-4 inline-block text-sm">Stok Tersedia: <?= $p['stok'] ?></div>
                
                <h3 class="font-bold text-slate-700 mb-2">Deskripsi Produk:</h3>
                <p class="text-slate-600 leading-relaxed mb-8"><?= nl2br(htmlspecialchars($p['deskripsi'])) ?></p>
                
                <button onclick="alert('Silahkan pesan melalui katalog depan untuk saat ini.')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg">
                    Pesan Sekarang
                </button>
            </div>
        </div>
    </div>
</body>
</html>
