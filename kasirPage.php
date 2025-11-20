<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Page - Hotel</title>
</head>
<body>
    <h1>Halaman Kasir</h1>
    <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
    <p>Role: <?php echo $_SESSION['role']; ?></p>

    <a href="kasirPage.php?logout=true">Logout</a>
</body>
</html>
