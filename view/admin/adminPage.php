<?php
// session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
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
    <title>Kasir Page - Hotel</title>
    <link rel="stylesheet" href="../../asset/css/admin.css">
</head>
<body>
    <!-- INI SIDEBAR YE DAR -->
    <sidebar>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Admin</h3>
        </div>
        <div class="sidebar-nav">
            <p>NAVIGASI</p>
            <ul class="sidebar-menu">
                <li><a href="adminPage.php" class="active">DASHBOARD</a></li>
                <li><a href="#">#</a></li>
                <li><a href="#">#</a></li>
                <li><a href="#">#</a></li>
                <li><a href="kasirPage.php?logout=true">Logout</a></li>
            </ul>
        </div>
        <!-- ini isinya-->
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
                        <td>Nama</td>
                        <td><?php echo $data['username']; ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
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
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $booking['kode_booking']; ?></td>
                                <td><?php echo $booking['nama_lengkap']; ?></td>
                                <td><?php echo $booking['nomor_kamar']; ?></td>
                                <td><?php echo $booking['status']; ?></td>
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
                Copyright &copy; Hotel <?php echo date('Y'); ?>
            </footer>
            </div>
        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
    </div>
    </sidebar>

        <footer>
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
