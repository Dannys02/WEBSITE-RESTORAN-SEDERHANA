<?php
include '../config/db.php';

$user_input = "DANNYS MARTHA";
$pass_input = "admin123";

$query = mysqli_query($koneksi, "SELECT * FROM admins WHERE username = '$user_input'");
$row = mysqli_fetch_assoc($query);

echo "<h3>Hasil Debug:</h3>";
if ($row) {
    echo "Username ketemu di DB!<br>";
    echo "Hash di DB: " . $row['password'] . "<br>";
    
    if (password_verify($pass_input, $row['password'])) {
        echo "<b style='color:green'>VERIFIKASI BERHASIL! Berarti login.php kamu yang ada typo.</b>";
    } else {
        echo "<b style='color:red'>VERIFIKASI GAGAL! Hash di DB tidak cocok dengan 'admin123'.</b>";
    }
} else {
    echo "<b style='color:red'>Username TIDAK KETEMU! Cek nama database di db.php kamu.</b>";
}
?>
