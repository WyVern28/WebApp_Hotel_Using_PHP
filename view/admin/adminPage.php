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
    <title>Admin Page - Hotel</title>
</head>
<body>
    <?php include '../../component/adminSidebar.php'; ?>
    <h1>Halaman Admin</h1>
    <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
    <p>Role: <?php echo $_SESSION['role']; ?></p>

    <a href="adminPage.php?logout=true">Logout</a>
</body>
</html>
