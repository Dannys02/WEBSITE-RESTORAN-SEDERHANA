<?php
include '../../config/db.php';
$q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'pending'");
$res = mysqli_fetch_assoc($q);
echo json_encode(['total_pending' => (int)$res['total']]);
