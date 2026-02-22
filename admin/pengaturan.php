<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}

$id_admin = $_SESSION['admin_id'];
$success = "";
$error = "";

// Ambil data admin saat ini
$query_admin = mysqli_query($koneksi, "SELECT username, password FROM admins WHERE id = '$id_admin'");
$data_admin = mysqli_fetch_assoc($query_admin);

// LOGIKA UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // A. UPDATE USERNAME
  if (isset($_POST['update_profile'])) {
    $username_baru = mysqli_real_escape_string($koneksi, $_POST['username']);

    $update = mysqli_query($koneksi, "UPDATE admins SET username = '$username_baru' WHERE id = '$id_admin'");
    if ($update) {
      $success = "Username berhasil diperbarui!";
      $data_admin['username'] = $username_baru; // Update tampilan
    } else {
      $error = "Gagal memperbarui username.";
    }
  }

  // B. UPDATE PASSWORD
  if (isset($_POST['update_password'])) {
    $pw_lama = $_POST['password_lama'];
    $pw_baru = $_POST['password_baru'];
    $konfirmasi_pw = $_POST['konfirmasi_password'];

    if (password_verify($pw_lama, $data_admin['password'])) {
      if ($pw_baru === $konfirmasi_pw) {
        if (strlen($pw_baru) < 5) {
          $error = "Password baru minimal 5 karakter!";
        } else {
          $password_fixed = password_hash($pw_baru, PASSWORD_DEFAULT);
          $update = mysqli_query($koneksi, "UPDATE admins SET password = '$password_fixed' WHERE id = '$id_admin'");
          if ($update) {
            $success = "Password berhasil diperbarui!";
          }
        }
      } else {
        $error = "Konfirmasi password baru tidak cocok.";
      }
    } else {
      $error = "Password lama salah.";
    }
  }
}
?>

<div class="mb-8">
  <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pengaturan Akun</h1>
  <p class="text-slate-500">
    Kelola informasi login dan keamanan panel admin Anda.
  </p>
</div>

<?php if ($error): ?>
<div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-bold rounded shadow-sm">
  ⚠️ <?= $error ?>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm font-bold rounded shadow-sm">
  ✅ <?= $success ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">

  <div class="md:col-span-1">
    <h3 class="text-lg font-bold text-slate-700 mb-2">Profil Admin</h3>
    <p class="text-sm text-slate-500">
      Username digunakan untuk masuk ke dashboard ini.
    </p>
  </div>

  <div class="md:col-span-2">
    <div class="bg-white p-6 rounded-2xl border border-orange-200 shadow-sm">
      <form method="POST">
        <div class="mb-4">
          <label class="block text-sm font-bold text-slate-600 mb-2">Username Saat Ini</label>
          <input type="text" name="username" value="<?= htmlspecialchars($data_admin['username']) ?>"
          class="w-full border border-orange-200 p-3 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
        </div>
        <button type="submit" name="update_profile" class="bg-orange-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-orange-700 transition">
          Simpan Username
        </button>
      </form>
    </div>
  </div>

  <div class="md:col-span-3 border-t border-orange-100 my-4"></div>

  <div class="md:col-span-1">
    <h3 class="text-lg font-bold text-slate-700 mb-2">Keamanan</h3>
    <p class="text-sm text-slate-500">
      Ganti password secara berkala untuk menjaga keamanan data UMKM.
    </p>
  </div>

  <div class="md:col-span-2">
    <div class="bg-white p-6 rounded-2xl border border-orange-200 shadow-sm">
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-bold text-slate-600 mb-2">Password Lama</label>
          <input type="password" name="password_lama" placeholder="••••••••"
          class="w-full border border-orange-200 p-3 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-slate-600 mb-2">Password Baru</label>
            <input type="password" name="password_baru" placeholder="Minimal 5 karakter"
            class="w-full border border-orange-200 p-3 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-600 mb-2">Konfirmasi Password</label>
            <input type="password" name="konfirmasi_password" placeholder="Ulangi password baru"
            class="w-full border border-orange-200 p-3 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
          </div>
        </div>

        <div class="pt-2">
          <button type="submit" name="update_password" class="w-full bg-orange-500 text-white py-3 rounded-xl font-bold shadow-lg shadow-orange-100 hover:bg-orange-600 active:scale-95 transition-all text-sm">
            Perbarui Kata Sandi
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<div class="mt-12 text-center">
  <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-slate-500 transition">← Kembali ke Dashboard</a>
</div>