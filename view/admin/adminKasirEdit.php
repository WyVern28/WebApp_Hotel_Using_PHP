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
    <title>Kasir Page - Hotel</title>
    <link rel="stylesheet" href="../../asset/css/admin.css">
</head>
<style>
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #334155; }
        .form-control {
            width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;
        }
        
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; color: white; display: inline-block; text-decoration: none;}
        .btn-primary { background-color: #0f172a; } 
        .btn-danger { background-color: #ef4444; font-size: 12px; padding: 5px 10px; text-decoration: none;}
        .btn-success { background-color: #22c55e; font-size: 12px; padding: 5px 10px; text-decoration: none;}

        .btn-edit { background-color: #0b59f5ff; font-size: 12px; padding: 5px 10px; color: white; }
        .btn-warning:hover { background-color: #d97706; }

        .table-custom { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-custom th { background: #f1f5f9; padding: 12px; text-align: left; font-weight: 600; color: #475569; }
        .table-custom td { padding: 12px; border-bottom: 1px solid #e2e8f0; }

        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .bg-active { background: #dcfce7; color: #166534; }
        .bg-inactive { background: #fee2e2; color: #991b1b; }

        .header-tools {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .search-box {
            display: flex;
            gap: 10px;
        }
        .search-input {
            padding: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 250px;
        }
    </style>
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
                <li><a href="../../controller/admin/adminTamu.php" >TAMU</a></li>
                <li><a href="../../controller/admin/adminKasir.php"class="active" >KASIR</a></li>
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
                    <label for="resetCheck">Reset Password (<b>1234</b>)</label>
                </div>
                <br>

                <button type="submit" name="update_kasir" class="btn btn-primary">Simpan Perubahan</button>
                
                <a href="adminKasir.php" class="btn btn-secondary">Batal</a>
                
            </form>
        </div>
    </div>
</body>
</html>
