<?php
// Cek session admin
if (!isset($_SESSION['admin_logged_in'])) {
  echo "<script>window.location.href='login.php';</script>"; exit;
}

$action = $_GET['action'] ?? null;
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// --- LOGIKA PROSES DATABASE (PASTI JALAN) ---
if ($action && $id) {
  if ($action === 'setuju') {
    $res = mysqli_query($koneksi, "SELECT produk_id, stok FROM pesanan WHERE id = $id");
    $order = mysqli_fetch_assoc($res);
    if ($order) {
      mysqli_query($koneksi, "UPDATE pesanan SET status = 'setuju' WHERE id = $id");
      mysqli_query($koneksi, "UPDATE produk SET stok = stok - {$order['stok']} WHERE id = {$order['produk_id']}");
      echo "<script>window.location.href='index.php?page=orders';</script>"; exit;
    }
  } elseif ($action === 'tolak') {
    mysqli_query($koneksi, "UPDATE pesanan SET status = 'tolak' WHERE id = $id");
    echo "<script>window.location.href='index.php?page=orders';</script>"; exit;
  }
}

// Ambil data pesanan
$query = "SELECT p.*, pr.nama as nama_produk, pr.harga FROM pesanan p
          JOIN produk pr ON p.produk_id = pr.id
          ORDER BY p.id DESC";
$all_orders = mysqli_query($koneksi, $query);
?>

<div class="mb-6">
  <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Pesanan Masuk</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
  <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 border-b border-slate-200">
      <tr>
        <th class="p-4 text-xs font-bold uppercase text-slate-500">Pembeli</th>
        <th class="p-4 text-xs font-bold uppercase text-slate-500">Produk</th>
        <th class="p-4 text-xs font-bold uppercase text-slate-500 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      <?php while ($row = mysqli_fetch_assoc($all_orders)): ?>
      <?php
      $clean_phone = preg_replace('/[^0-9]/', '', $row['whatsapp']);
      if (substr($clean_phone, 0, 1) === '0') {
        $clean_phone = '62' . substr($clean_phone, 1);
      }

      $url_setuju = "index.php?page=orders&action=setuju&id={$row['id']}";
      $url_tolak = "index.php?page=orders&action=tolak&id={$row['id']}";

      $txt_setuju = urlencode("✅ *PESANAN TELAH DISETUJUI*\n"
    . "------------------------------------------\n"
    . "⚠️ *NOTIFIKASI SISTEM - SIAP DIKIRIM*\n\n"
    . "Detail Order Yang Harus Diproses:\n"
    . "📦 *Menu:* {$row['nama_produk']}\n"
    . "🔢 *Jumlah:* {$row['stok']} Unit\n"
    . "💰 *Total Pembayaran:* Rp " . number_format($row['harga'] * $row['stok'], 0, ',', '.') . "\n"
    . "------------------------------------------\n"
    . "📊 *Status Aktif:* SIAP DIKIRIM\n"
    . "🕒 *Log Time:* " . date('d/m/Y H:i') . "\n"
    . "------------------------------------------\n"
    . "👉 _Segera lakukan pengemasan dan update nomor resi melalui dashboard admin._"
); // <--- TUTUP KURUNG UNTUK urlencode ADA DI SINI!

        $txt_tolak = urlencode("*PEMBATALAN PESANAN*\n--------------------------\nMohon maaf *{$row['nama_pembeli']}*,\n\n📦 *Produk:* {$row['nama_produk']}\n❌ *Status:* DITOLAK\n⚠️ *Alasan:* Stok Habis.");

        $wa_setuju = "https://api.whatsapp.com/send?phone=$clean_phone&text=$txt_setuju";
        $wa_tolak = "https://api.whatsapp.com/send?phone=$clean_phone&text=$txt_tolak";
        ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="p-4 text-sm">
            <div class="font-bold">
              <?= htmlspecialchars($row['nama_pembeli']) ?>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter
              <?= $row['status'] == 'setuju' ? 'bg-green-100 text-green-700' : ($row['status'] == 'tolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>">
              <?= $row['status'] ?>
            </span>
          </td>
          <td class="p-4 text-sm">
            <?= $row['nama_produk'] ?> (<?= $row['stok'] ?>)
          </td>
          <td class="p-4 text-center">
            <?php if ($row['status'] == 'pending'): ?>
            <div class="flex flex-col gap-2 max-w-[120px] mx-auto">

              <button type="button"
                onclick="if(confirm('SETUJUI PESANAN?')){ window.open('<?= $wa_setuju ?>', '_blank'); window.location.href='<?= $url_setuju ?>'; }"
                class="bg-emerald-500 text-white py-2 rounded-lg text-[10px] font-black shadow-sm">
                SETUJUI
              </button>

              <button type="button"
                onclick="if(confirm('TOLAK PESANAN?')){ window.open('<?= $wa_tolak ?>', '_blank'); window.location.href='<?= $url_tolak ?>'; }"
                class="bg-white border-2 border-red-500 text-red-500 py-2 rounded-lg text-[10px] font-black">
                TOLAK
              </button>

            </div>
            <?php else : ?>
            <span class="text-[10px] text-slate-300 font-bold italic uppercase">Selesai</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>