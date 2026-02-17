<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}
// Cek session admin
if (!isset($_SESSION['admin_logged_in'])) {
  echo "<script>window.location.href='login.php';</script>"; exit;
}

$action = $_GET['action'] ?? null;
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// --- LOGIKA PROSES DATABASE (Lebih Ketat)
if ($action && $id > 0) {
  // Gunakan ID yang sudah di-validate (int)
  $query_cek = "SELECT p.stok as qty_pesan, p.produk_id, pr.stok as stok_sekarang
                FROM pesanan p
                JOIN produk pr ON p.produk_id = pr.id
                WHERE p.id = $id AND p.status = 'pending'"; // Tambahkan cek status pending
  $res_cek = mysqli_query($koneksi, $query_cek);
  $data = mysqli_fetch_assoc($res_cek);

  if ($data) {
    $qty_pesan = (int)$data['qty_pesan'];
    $stok_sekarang = (int)$data['stok_sekarang'];
    $pid = (int)$data['produk_id'];

    if ($action === 'setuju') {
      if ($stok_sekarang >= $qty_pesan) {
        // Update status dan kurangi stok secara atomik
        mysqli_query($koneksi, "UPDATE pesanan SET status = 'setuju' WHERE id = $id");
        mysqli_query($koneksi, "UPDATE produk SET stok = stok - $qty_pesan WHERE id = $pid");
      } else {
        echo "<script>alert('Gagal! Stok mendadak habis.'); window.location.href='index.php?page=orders';</script>";
        exit;
      }
    } elseif ($action === 'tolak') {
      mysqli_query($koneksi, "UPDATE pesanan SET status = 'tolak' WHERE id = $id");
    }
    echo "<script>window.location.href='index.php?page=orders';</script>"; exit;
  }
}


// Ambil data pesanan
$query = "SELECT p.*, pr.nama as nama_produk, pr.harga, pr.stok as stok_saat_ini FROM pesanan p
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
      // Persiapan Data untuk WhatsApp agar akurat
      $clean_phone = preg_replace('/[^0-9]/', '', $row['whatsapp']);
      if (substr($clean_phone, 0, 1) === '0') $clean_phone = '62' . substr($clean_phone, 1);

      $msg_setuju = urlencode("✅ *PESANAN ANDA TELAH DISETUJUI*\n------------------------------------------\nHalo, *{$row['nama_pembeli']}* 👋\nKabar baik! Pesanan Anda telah kami verifikasi.\n\n📑 *RINGKASAN PESANAN*\n━━━━━━━━━━━━━━━━━━━━\n🍱 *Menu:* {$row['nama_produk']}\n🔢 *Jumlah:* {$row['stok']} Unit\n💰 *Total Bayar:* Rp " . number_format($row['harga'] * $row['stok'], 0, ',', '.') . "\n━━━━━━━━━━━━━━━━━━━━\n\n📊 *Status:* SIAP DIKIRIM 🚚\n------------------------------------------");

      $msg_tolak = urlencode("❌ *PEMBATALAN PESANAN*\n------------------------------------------\nMohon maaf *{$row['nama_pembeli']}*,\n\n📦 *Produk:* {$row['nama_produk']}\n⚠️ *Alasan:* Stok Tidak Mencukupi\n📊 *Sisa Stok Saat Ini:* {$row['stok_saat_ini']} unit\n\nSilakan pesan kembali atau pilih menu lainnya. Terima kasih!");
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
          <span class="font-medium"><?= $row['nama_produk'] ?></span> <br>
          <small class="text-slate-400">Order: <?= $row['stok'] ?> | Sisa: <?= $row['stok_saat_ini'] ?></small>
        </td>
        <td class="p-4 text-center">
          <?php if ($row['status'] == 'pending'): ?>
          <div class="flex flex-col gap-2 max-w-[120px] mx-auto">
            <button type="button"
              onclick="prosesPesanan('setuju', <?= $row['id'] ?>, <?= $row['stok'] ?>, <?= $row['stok_saat_ini'] ?>, '<?= $clean_phone ?>', '<?= $msg_setuju ?>', '<?= $row['nama_produk'] ?>')"
              class="bg-emerald-500 text-white py-2 rounded-lg text-[10px] font-black shadow-sm hover:bg-emerald-600 transition-colors">
              SETUJUI
            </button>

            <button type="button"
              onclick="prosesPesanan('tolak', <?= $row['id'] ?>, 0, 0, '<?= $clean_phone ?>', '<?= $msg_tolak ?>', '')"
              class="bg-white border-2 border-red-500 text-red-500 py-2 rounded-lg text-[10px] font-black hover:bg-red-50 transition-colors">
              TOLAK
            </button>
          </div>
          <?php else : ?>
          <span class="text-[10px] px-2 text-slate-300 font-bold italic uppercase">Selesai</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
  /**
  * Fungsi Utama untuk menangani alur Pesanan
  */
  function prosesPesanan(tipe, id, qtyOrder, stokReady, phone, encodedMsg, namaProduk) {
    const actionText = tipe === 'setuju' ? 'Setujui': 'Tolak';

    if (!confirm(actionText + ' pesanan ini?')) return;

    if (tipe === 'setuju') {
      // Cek Stok Instan di sisi client
      if (stokReady < qtyOrder) {
        alert('Gagal! Stok ' + namaProduk + ' tidak mencukupi.\nStok tersedia: ' + stokReady + '\nJumlah pesanan: ' + qtyOrder);
        return; // Berhenti disini, WA tidak akan terbuka
      }
    }

    // Jika lolos validasi stok (atau jika tipenya 'tolak')
    // 1. Buka WhatsApp
    const waUrl = 'https://api.whatsapp.com/send?phone=' + phone + '&text=' + encodedMsg;
    window.open(waUrl, '_blank');

    // 2. Arahkan halaman utama untuk proses database
    window.location.href = 'index.php?page=orders&action=' + tipe + '&id=' + id;
  }
</script>