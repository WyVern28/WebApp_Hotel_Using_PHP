<?php
// session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
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
    <title>Kamar page - Hotel</title>
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
                <li><a href="../../controller/admin/adminPage.php">DASHBOARD</a></li>
                <li><a href="../../controller/admin/adminTamu.php">TAMU</a></li>
                <li><a href="../../controller/admin/adminKasir.php">KASIR</a></li>
                <li><a href="../../controller/admin/adminKamar.php" class="active">KAMAR</a></li>
                <li><a href="../../controller/admin/adminLaporan.php">LAPORAN</a></li>
                <li><a href="../../controller/admin/adminSetting.php">SETTING</a></li>
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
        <h1 style="text-align: center; margin-bottom: 20px;">Master Fasilitas</h1>
        
        <div class="card">
            <form method="POST" action="adminKamar.php" enctype="multipart/form-data">
                
                <label>Nama Fasilitas</label>
                <input type="text" name="nama_fasilitas" class="form-control" required placeholder="Contoh: AC, WiFi, TV">

                <label>Icon / Foto (Opsional)</label>
                <input type="file" name="foto" class="form-control">

                <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                    <a href="adminKamar.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" name="save_fasilitas" class="btn btn-primary">Simpan Fasilitas</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
