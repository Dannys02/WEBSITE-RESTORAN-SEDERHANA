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

// --- LOGIKA PROSES DATABASE ---
if ($action && $id > 0) {
  $query_cek = "SELECT p.stok as qty_pesan, p.produk_id, p.status, pr.stok as stok_sekarang
                FROM pesanan p
                JOIN produk pr ON p.produk_id = pr.id
                WHERE p.id = $id";
  $res_cek = mysqli_query($koneksi, $query_cek);
  $data = mysqli_fetch_assoc($res_cek);

  if ($data) {
    $qty_pesan = (int)$data['qty_pesan'];
    $stok_sekarang = (int)$data['stok_sekarang'];
    $pid = (int)$data['produk_id'];
    $status_saat_ini = $data['status'];

    if ($action === 'setuju' && $status_saat_ini === 'pending') {
      if ($stok_sekarang >= $qty_pesan) {
        mysqli_query($koneksi, "UPDATE pesanan SET status = 'setuju' WHERE id = $id");
        mysqli_query($koneksi, "UPDATE produk SET stok = stok - $qty_pesan WHERE id = $pid");
      } else {
        echo "<script>alert('Gagal! Stok tidak mencukupi.'); window.location.href='index.php?page=orders';</script>";
        exit;
      }
    } elseif ($action === 'tolak' && $status_saat_ini === 'pending') {
      mysqli_query($koneksi, "UPDATE pesanan SET status = 'tolak' WHERE id = $id");
    } elseif ($action === 'dibatalkan' && $status_saat_ini === 'setuju') {
      mysqli_query($koneksi, "UPDATE produk SET stok = stok + $qty_pesan WHERE id = $pid");
      mysqli_query($koneksi, "UPDATE pesanan SET status = 'dibatalkan' WHERE id = $id");
    }
    // Seteleh proses selesai, redirect balik ke halaman orders tanpa buka WA
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

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
  <table id="tabelPesanan" class="w-full text-left border-collapse">
    <thead class="bg-slate-50 border-b border-slate-200">
      <tr>
        <th class="p-4 text-xs font-bold uppercase text-slate-500">Pembeli</th>
        <th class="p-4 text-xs font-bold uppercase text-slate-500">Produk</th>
        <th class="p-4 text-xs font-bold uppercase text-slate-500">Alamat</th>
        <th class="p-4 text-xs font-bold uppercase text-slate-500 text-center">Status</th>
        <th class="p-4 text-xs font-bold uppercase text-slate-500 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      <?php while ($row = mysqli_fetch_assoc($all_orders)): ?>
      <?php
      $clean_phone = preg_replace('/[^0-9]/', '', $row['whatsapp']);
      if (substr($clean_phone, 0, 1) === '0') $clean_phone = '62' . substr($clean_phone, 1);

      $format_harga = "Rp " . number_format($row['harga'] * $row['stok'], 0, ',', '.');
      $msg_setuju = urlencode("✅ *PESANAN DISETUJUI*\nHalo *{$row['nama_pembeli']}*,\n\nPesanan *{$row['nama_produk']}* ({$row['stok']} pcs) senilai *{$format_harga}* telah kami terima!");
      $msg_tolak = urlencode("❌ *PESANAN DITOLAK*\nMohon maaf *{$row['nama_pembeli']}*,\n\nPesanan *{$row['nama_produk']}* ditolak karena stok habis.");
      $msg_batal = urlencode("⚠️ *PESANAN DIBATALKAN*\nHalo *{$row['nama_pembeli']}*,\n\nPesanan Anda untuk *{$row['nama_produk']}* telah dibatalkan.");
      ?>
      <tr class="hover:bg-slate-50 transition-colors">
        <td class="p-4 text-sm">
          <div class="font-bold">
            <?= htmlspecialchars($row['nama_pembeli']) ?>
          </div>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter
            <?= $row['status'] == 'setuju' ? 'bg-green-100 text-green-700' : ($row['status'] == 'tolak' ? 'bg-red-100 text-red-700' : ($row['status'] == 'dibatalkan' ? 'bg-gray-100 text-gray-500' : 'bg-amber-100 text-amber-700')) ?>">
            <?= $row['status'] ?>
          </span>
        </td>
        <td class="p-4 text-sm">
          <span class="font-medium"><?= $row['nama_produk'] ?></span> <br>
          <small class="text-slate-400">Order: <?= $row['stok'] ?> | Sisa: <?= $row['stok_saat_ini'] ?></small>
        </td>
        <td class="p-4 text-sm text-slate-600 italic"><?= htmlspecialchars($row['alamat']) ?></td>
        <td class="p-4 text-center">
          <div class="flex flex-col gap-2 max-w-[130px] mx-auto">
            <?php if ($row['status'] == 'pending'): ?>
            <button type="button" onclick="ubahStatus('setuju', <?= $row['id'] ?>, <?= $row['stok'] ?>, <?= $row['stok_saat_ini'] ?>, '<?= $row['nama_produk'] ?>')"
              class="bg-emerald-500 text-white py-2 rounded-lg text-[10px] font-black shadow-sm hover:bg-emerald-600">
              SETUJUI
            </button>
            <button type="button" onclick="ubahStatus('tolak', <?= $row['id'] ?>)"
              class="bg-white border-2 border-red-500 text-red-500 py-2 rounded-lg text-[10px] font-black hover:bg-red-50">
              TOLAK
            </button>

            <?php elseif ($row['status'] == 'setuju'): ?>
            <div class="flex gap-1">
              <button type="button" onclick="window.open('https://api.whatsapp.com/send?phone=<?= $clean_phone ?>&text=<?= $msg_setuju ?>', '_blank')"
                class="flex-1 bg-emerald-500 text-white p-2 rounded-lg hover:bg-emerald-600 shadow-sm">
                🟢 <span class="text-[9px] font-bold">WA</span>
              </button>
              <button type="button" onclick="confirmBatal(<?= $row['id'] ?>)"
                class="bg-slate-100 text-slate-400 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                ✕
              </button>
            </div>
            <span class="text-[9px] text-slate-400 font-bold italic mt-1 uppercase">Setuju</span>

            <?php else : ?>
            <button type="button" onclick="window.open('https://api.whatsapp.com/send?phone=<?= $clean_phone ?>&text=<?= $row['status'] == 'tolak' ? $msg_tolak : $msg_batal ?>', '_blank')"
              class="bg-gray-100 text-gray-500 py-2 rounded-lg text-[10px] font-bold border border-gray-200 hover:bg-emerald-50 hover:text-emerald-600">
              🟢 HUBUNGI WA
            </button>
            <span class="text-[9px] text-slate-400 font-bold italic mt-1 uppercase"><?= $row['status'] ?></span>
            <?php endif; ?>
          </div>
        </td>
        <td class="p-4 text-center">
          <div class="flex justify-center gap-3">
            <a href="" class="text-yellow-600 text-sm font-bold">Edit</a>
            <a href="" onclick="return confirm('Hapus testimoni ini?')" class="text-red-500 hover:text-red-700 font-bold text-sm">Hapus</a>
          </div>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>