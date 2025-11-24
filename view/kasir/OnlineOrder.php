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
$username = $_SESSION['username'];
$id_kasir = 'KSR001';
$nama_kasir = $username;

$query_kasir = "SELECT id_kasir FROM kasir LIMIT 1";
$result_kasir = $conn->query($query_kasir);
if($result_kasir && $result_kasir->num_rows > 0){
    $kasir = $result_kasir ->fetch_assoc();
    $id_kasir = $kasir['id_kasir'];
}

$currentDateTime = date('H:i:s d/m/Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Order - Hotel</title>
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
                <li><a href="kasirPage.php">DASHBOARD</a></li>
                <li><a href="OnlineOrder.php" class="active">ONLINE ORDER</a></li>
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
        <h1>Online Order</h1>

        <div class="section-title">TABEL ONLINE ORDER</div>

        <div class="info-box">
            <p>ID KASIR: <?php echo $id_kasir; ?></p>
            <p>NAMA KASIR: <?php echo $_SESSION['username']; ?></p>
            <p>STATUS: Aktif</p>
        </div>

        <div class="info-kamar">
            INFORMASI JENIS KAMAR
        </div>

        <table class="order-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA CUST</th>
                    <th>JENIS KAMAR</th>
                    <th>TANGGAL CEK IN</th>
                    <th>TANGGAL CEK OUT</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><button class="icon-btn">📝</button></td>
                    <td><button class="icon-btn">🗑️</button></td>
                </tr>
            </tbody>
        </table>

        <!-- <button class="btn-tambah">+ TAMBAH ORDER</button> -->

        <div class="btn-container">
            <button class="btn-action secondary">CETAK STRUK</button>
            <button class="btn-action">SIMPAN</button>
        </div>

        <footer>
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
