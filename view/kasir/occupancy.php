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
    $kasir = $result_kasir->fetch_assoc();
    $id_kasir = $kasir['id_kasir'];
}


$currentDateTime = date('H:i:s d/m/Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupancy - Hotel</title>
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
                <li><a href="OnlineOrder.php">ONLINE ORDER</a></li>
                <li><a href="otsOrder.php">OTS ORDER</a></li>
                <li><a href="occupancy.php" class="active">OCCUPANCY</a></li>
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
        <h1>Occupancy</h1>

        <div class="section-title">TABEL OCCUPANCY KAMAR</div>

        <div class="info-box">
            <p>ID KASIR: <?php echo $id_kasir; ?></p>
            <p>NAMA KASIR: <?php echo $nama_kasir; ?></p>
            <p>STATUS: Aktif</p>
        </div>

        <div class="filter-container">
            <button class="btn-filter">FILTER</button>
            <button class="btn-filter">SEARCH</button>
        </div>

        <table class="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NO KAMAR</th>
                    <th>NAMA CUSTOMER</th>
                    <th>JENIS KAMAR</th>
                    <th>TANGGAL CEK IN</th>
                    <th>TANGGAL CEK OUT</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="status-occupied"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="status-available"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="status-occupied"></td>
                </tr>
            </tbody>
        </table>

        <footer>
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
