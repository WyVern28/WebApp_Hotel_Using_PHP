<?php
// session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamar page - Hotel</title>
    <link rel="stylesheet" href="../../asset/css/adminPage.css">
</head>
<body>
    <sidebar>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Admin</h3>
        </div>
        <div class="sidebar-nav">
            <p>NAVIGASI</p>
            <ul class="sidebar-menu">
                <li><a href="../../controller/admin/adminPage.php">DASHBOARD</a></li>
                <li><a href="../../controller/admin/adminTamu.php">TAMU</a></li>
                <li><a href="../../controller/admin/adminKasir.php">KASIR</a></li>
                <li><a href="../../controller/admin/adminKamar.php">KAMAR</a></li>
                <li><a href="../../controller/admin/adminLaporan.php" class="active">LAPORAN</a></li>
                <li><a href="../../controller/admin/adminSetting.php">SETTING</a></li>
                <li><a href="../../controller/admin/adminPage.php?logout=true">Logout</a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
    </div>
    </sidebar>
   <div class="main-content">
        <header style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h1>Laporan Pendapatan</h1>
            <button onclick="window.print()" class="btn btn-print"><i class="fa fa-print"></i> Cetak PDF</button>
        </header>

        <div class="total-box">
            <p>Total Pendapatan (Periode Ini)</p>
            <h2>Rp <?= number_format($data['total'], 0, ',', '.'); ?></h2>
        </div>

        <div class="card">
            <form method="GET" action="">
                <div class="filter-box">
                    <div class="form-group">
                        <label>Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" value="<?= $data['tgl_mulai']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" value="<?= $data['tgl_selesai']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Status Transaksi</label>
                        <select name="status" class="form-control">
                            <option value="semua" <?= $data['status'] == 'semua' ? 'selected' : '' ?>>Semua (Valid)</option>
                            <option value="dibayar" <?= $data['status'] == 'dibayar' ? 'selected' : '' ?>>Dibayar</option>
                            <option value="check_in" <?= $data['status'] == 'check_in' ? 'selected' : '' ?>>Sedang Menginap</option>
                            <option value="selesai" <?= $data['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 38px;">Filter Data</button>
                </div>
            </form>
        </div>

        <div class="card">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Tanggal Check-in</th>
                        <th>Tamu</th>
                        <th>Kamar</th>
                        <th>Status</th>
                        <th style="text-align: right;">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['laporan'])): ?>
                        <tr><td colspan="6" align="center" style="padding: 30px;">Tidak ada transaksi pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($data['laporan'] as $row): ?>
                        <tr>
                            <td><b>#<?= $row['kode_booking']; ?></b></td>
                            <td><?= date('d M Y', strtotime($row['tgl_check_in'])); ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td>Kamar <?= $row['nomor_kamar'] ?? '-'; ?></td>
                            <td>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #e0f2fe; color: #0369a1; font-weight: bold; text-transform: uppercase;">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: bold;">
                                Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
