<?php
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
                <li><a href="../../controller/admin/adminPage.php">DASHBOARD</a></li>
                <li><a href="../../controller/admin/adminTamu.php">TAMU</a></li>
                <li><a href="../../controller/admin/adminKasir.php" class="active">KASIR</a></li>
                <li><a href="../../controller/admin/adminKamar.php">KAMAR</a></li>
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
        <h1>Edit Data Kasir</h1>
        
        <div class="card" style="max-width: 600px;">
            <form method="POST" action="">
                <div class="form-group">
                    <label>ID Kasir</label>
                    <input type="text" name="id_kasir" value="<?= $dataKasir['id_kasir']; ?>" class="form-control" readonly style="background-color: #e2e8f0;">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= $dataKasir['username']; ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $dataKasir['nama']; ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= $dataKasir['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= $dataKasir['status'] == 0 ? 'selected' : '' ?>>Non-Aktif</option>
                    </select>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="reset_password" id="resetCheck" value="yes">
                    <label for="resetCheck">Reset Password (<b>username</b>)</label>
                </div>
                <br>

                <button type="submit" name="update_kasir" class="btn btn-primary">Simpan Perubahan</button>
                
                <a href="adminKasir.php" class="btn btn-secondary">Batal</a>
                
            </form>
        </div>
    </div>
</body>
</html>
