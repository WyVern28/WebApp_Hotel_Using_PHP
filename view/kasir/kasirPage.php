<?php
// Proteksi: File ini hanya bisa diakses melalui controller
if (!isset($data)) {
    die('Akses ditolak! Halaman ini harus diakses melalui controller.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Page - Hotel</title>
    <link rel="stylesheet" href="../../asset/css/kasir.css">
    
</head>
<body>
    <sidebar>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Hotel Kasir</h3>
        </div>

        <div class="sidebar-nav">
            <p>NAVIGASI</p>
            <ul class="sidebar-menu">
                <li><a href="../../controller/kasir/DashboardController.php" class="active">DASHBOARD</a></li>
                <li><a href="../../controller/kasir/OnlineOrderController.php">ONLINE ORDER</a></li>
                <li><a href="../../controller/kasir/OtsOrderController.php">OTS ORDER</a></li>
                <li><a href="../../controller/kasir/OccupancyController.php">OCCUPANCY</a></li>
                <li><a href="../../controller/kasir/DashboardController.php?logout=true">Logout</a></li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $data['username']; ?></span>
        </div>
    </div>
    </sidebar>
    <div class="main-content">
        <h1>Dashboard</h1>

        <div class="stats-container">
            <div class="stat-box stat-blue">Total Booking Hari Ini: <?php echo $data['totalBookingToday']; ?></div>
            <div class="stat-box stat-yellow">Total Booking: <?php echo $data['totalBookingAll']; ?></div>
            <div class="stat-box stat-green">Kamar Tersedia: <?php echo $data['availableRooms']; ?></div>
        </div>

        <div class="info-table">
            <table>
                <tr>
                    <td>NAMA</td>
                    <td><?php echo $data['username']; ?></td>
                </tr>
                <tr>
                    <td>TANGGAL</td>
                    <td><?php echo $data['currentDateTime']; ?></td>
                </tr>
            </table>
        </div>

        <h2>Booking Terbaru</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Booking</th>
                    <th>Nama Tamu</th>
                    <th>No Kamar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['recentBookings'])): ?>
                    <?php foreach ($data['recentBookings'] as $index => $booking): ?>
                        <tr>
                            <td class="td-center"><?php echo $index + 1; ?></td>
                            <td><?php echo $booking['kode_booking']; ?></td>
                            <td><?php echo $booking['nama_lengkap']; ?></td>
                            <td class="td-center"><?php echo $booking['nomor_kamar']; ?></td>
                            <td class="td-center"><?php echo $booking['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Belum ada booking</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <footer>
            Copyright &copy; Ivory Palace <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
