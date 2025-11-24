<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

$currentDateTime = date('d-m-y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Page - Hotel</title>
    <!-- <link rel="stylesheet" href="../../asset/css/kasir.css"> -->
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
                <li><a href="kasirPage.php" class="active">DASHBOARD</a></li>
                <li><a href="OnlineOrder.php">ONLINE ORDER</a></li>
                <li><a href="otsOrder.php">OTS ORDER</a></li>
                <li><a href="occupancy.php">OCCUPANCY</a></li>
                <li><a href="kasirPage.php?logout=true">Logout</a></li>
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

        <div class="stats-container">
            <div class="stat-box stat-blue">Total Booking</div>
            <div class="stat-box stat-yellow">Total Kamar</div>
            <div class="stat-box stat-green">Total Tamu</div>
            <div class="stat-box stat-red">Total User</div>
        </div>

        <div class="info-table">
            <table>
                <tr>
                    <td>Nama</td>
                    <td><?php echo $_SESSION['username']; ?></td>
                </tr>
                <tr>
                    <td>Level User</td>
                    <td><?php echo $_SESSION['role']; ?></td>
                </tr>
                <tr>
                    <td>Tanggal Login</td>
                    <td><?php echo $currentDateTime; ?></td>
                </tr>
            </table>
        </div>

        <footer>
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
