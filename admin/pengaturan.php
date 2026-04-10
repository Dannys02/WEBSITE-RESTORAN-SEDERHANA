<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}

// Inisialisasi Identitas & Notifikasi
$admin_id = $_SESSION['admin_id'];
$pesan_sukses = "";
$pesan_error = "";

// Ambil Data Awal
// Ambil data Akun Admin
$sql_admin = "SELECT username, password FROM admins WHERE id = '$admin_id'";
$query_admin = mysqli_query($koneksi, $sql_admin);
$data_admin = mysqli_fetch_assoc($query_admin);

// Ambil data Konfigurasi WA
$sql_wa = "SELECT nomor FROM admins_wa WHERE id = 1";
$query_wa = mysqli_query($koneksi, $sql_wa);
$data_wa = mysqli_fetch_assoc($query_wa);
$wa_sekarang = $data_wa['nomor'] ?? '628...';

// Proses Pengiriman Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // --- LOGIKA UPDATE USERNAME ---
  if (isset($_POST['update_profile'])) {
    $user_input = mysqli_real_escape_string($koneksi, $_POST['username']);

    $sql_update_user = "UPDATE admins SET username = '$user_input' WHERE id = '$admin_id'";
    if (mysqli_query($koneksi, $sql_update_user)) {
      $pesan_sukses = "Username berhasil diperbarui!";
      $data_admin['username'] = $user_input; // Sinkronisasi tampilan
      $_SESSION['username'] = $user_input;
    } else {
      $pesan_error = "Gagal memperbarui username.";
    }
  }

  // --- LOGIKA UPDATE NOMOR WA ---
  if (isset($_POST['update_wa'])) {
    $wa_input = mysqli_real_escape_string($koneksi, $_POST['nomor_wa']);

    $sql_update_wa = "UPDATE admins_wa SET nomor = '$wa_input' WHERE id = 1";
    if (mysqli_query($koneksi, $sql_update_wa)) {
      $pesan_sukses = "Nomor WhatsApp berhasil diperbarui!";
      $wa_sekarang = $wa_input; // Sinkronisasi tampilan
    } else {
      $pesan_error = "Gagal memperbarui nomor WhatsApp.";
    }
  }

  // --- LOGIKA UPDATE PASSWORD ---
  if (isset($_POST['update_password'])) {
    $pw_lama = $_POST['password_lama'];
    $pw_baru = $_POST['password_baru'];
    $pw_konfirmasi = $_POST['konfirmasi_password'];

    // Validasi: Cek password lama
    if (password_verify($pw_lama, $data_admin['password'])) {
      // Validasi: Cek kecocokan password baru
      if ($pw_baru === $pw_konfirmasi) {
        if (strlen($pw_baru) < 5) {
          $pesan_error = "Password baru minimal 5 karakter!";
        } else {
          $pw_hash = password_hash($pw_baru, PASSWORD_DEFAULT);
          $sql_update_pw = "UPDATE admins SET password = '$pw_hash' WHERE id = '$admin_id'";

          if (mysqli_query($koneksi, $sql_update_pw)) {
            $pesan_sukses = "Password berhasil diperbarui!";
          }
        }
      } else {
        $pesan_error = "Konfirmasi password baru tidak cocok.";
      }
    } else {
      $pesan_error = "Password lama salah.";
    }
  }
}
?>

<div class="mb-8">
  <h1 class="text-2xl font-bold text-slate-800">Pengaturan Akun</h1>
  <p class="text-slate-500">
    Kelola informasi login dan keamanan panel admin Anda.
  </p>
</div>

<?php if ($pesan_error): ?>
<div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-bold rounded shadow-sm">
  ⚠️ <?= $pesan_error ?>
</div>
<?php endif; ?>

<?php if ($pesan_sukses): ?>
<div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm font-bold rounded shadow-sm">
  ✅ <?= $pesan_sukses ?>
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
    <div class="bg-white p-6 rounded-2xl border border-orange-200 shadow-sm space-y-6">

      <form method="POST" class="pb-6 border-b border-orange-50">
        <div class="mb-4">
          <label class="block text-sm font-bold text-slate-600 mb-2">Username Admin</label>
          <input type="text" name="username" value="<?= htmlspecialchars($data_admin['username']) ?>"
          class="w-full border border-orange-200 p-3 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
        </div>
        <button type="submit" name="update_profile" class="bg-orange-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-orange-700 transition">
          Simpan Username
        </button>
      </form>

      <form method="POST">
        <div class="mb-4">
          <label class="block text-sm font-bold text-slate-600 mb-2">Nomor WhatsApp Admin (Gunakan 62)</label>
          <input type="text" name="nomor_wa" value="<?= htmlspecialchars($wa_sekarang) ?>"
          placeholder="Contoh: 62812345678"
          class="w-full border border-orange-200 p-3 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" required>
          <p class="text-[10px] text-slate-400 mt-1">
            *Nomor ini digunakan sebagai tujuan chat pelanggan di halaman depan.
          </p>
        </div>
        <button type="submit" name="update_wa" class="bg-orange-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-orange-700 transition">
          Perbarui Nomor WA
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