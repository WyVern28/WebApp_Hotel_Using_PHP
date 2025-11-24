<?php
session_start();
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
    <link rel="stylesheet" href="../../asset/css/kasir.css">
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
