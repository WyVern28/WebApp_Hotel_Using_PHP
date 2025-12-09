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
                <li><a href="#">TAMU</a></li>
                <li><a href="#">KASIR</a></li>
                <li><a href="#">KAMAR</a></li>
                <li><a href="#">DISKON</a></li>
                <li><a href="#">LAPORAN</a></li>
                <li><a href="#">SETTING</a></li>
                <li><a href="../../controller/admin/adminPage.php?logout=true">Logout</a></li>
            </ul>
        </div>
        <!-- ini isinya-->
            
        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
    </div>
    </sidebar>
    <div class="main-content">
        <h1>Dashboard</h1>
        <h2><span style="color:black">WELCOME BACK, <?php echo $data['username'] ?>!</span></h2><br>
        <div class="stats-container">
            <div class="stat-box stat-blue">Total Booking Hari Ini: <?php echo count($data['bookingToday']); ?></div>
            <div class="stat-box stat-yellow">Total Booking: <?php echo count($data['booking']); ?></div>
            <div class="stat-box stat-green">Kamar Tersedia: <?php echo count($data['sisa']); ?></div>
            <div class="stat-box stat-green">Kamar Terisi: <?php echo count($data['rooms']);?></div>
        </div>

        <div class="info-table">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td><?php echo $data['username']; ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td><?php echo "sekarang"//$data['currentDateTime']; ?></td>
                    </tr>
                </table>
            </div>
            <footer>
                Copyright &copy; Hotel <?php echo date('Y'); ?>
            </footer>
            </div>
    </div>
</body>
</html>
