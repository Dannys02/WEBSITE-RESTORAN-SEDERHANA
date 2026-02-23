<?php
$jumlah = $_GET['jumlah'];
$token = "8150467230:AAHpPlZWlVng8wHy7Vgk8wmKipMLFUh1dQg";
$chat_id = "6894989857";
$pesan = "Admin, ada $jumlah pesanan pending baru dari website!";

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($pesan));
