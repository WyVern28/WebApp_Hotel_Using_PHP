<?php
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
                <li><a href="../../controller/admin/adminPage.php" class="active">DASHBOARD</a></li>
                <li><a href="../../controller/admin/adminTamu.php">TAMU</a></li>
                <li><a href="../../controller/admin/adminKasir.php">KASIR</a></li>
                <li><a href="../../controller/admin/adminKamar.php">KAMAR</a></li>
                <li><a href="#">DISKON</a></li>
                <li><a href="#">LAPORAN</a></li>
                <li><a href="#">SETTING</a></li>
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
        <h1>Dashboard</h1>
        <h2><span style="color:black">WELCOME BACK, <?php echo $data['username'] ?>!</span></h2><br>
        <div class="stats-container">
            <a href="../../controller/admin/adminKasir.php"><div class="stat-box stat-blue">Total Kasir: <?php echo count($data['allKasir']); ?></div></a>
            <a href="../../controller/admin/adminTamu.php"><div class="stat-box stat-yellow">Total Tamu: <?php echo count($data['allTamu']); ?></div></a>
            <a href="../../controller/admin/adminKamar.php"><div class="stat-box stat-green">Total Kamar: <?php echo count($data['allKamar']); ?></div></a>
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
                        <td><?php echo date('d F Y'); ?></td>                    
                    </tr>
                </table>
            </div>
            <footer>
                Copyright &copy; Ivory Palace <?php echo date('Y'); ?>
            </footer>
            </div>
    </div>
</body>
</html>
