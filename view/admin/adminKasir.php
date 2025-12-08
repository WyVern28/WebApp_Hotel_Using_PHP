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
                <li><a href="../../controller/admin/adminTamu.php"  >TAMU</a></li>
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
        
        <header style="margin-bottom: 30px;">
            <h1>Manajemen Kasir</h1>
        </header>

        <?php if (!empty($data['pesan'])): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?= $data['pesan']; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3 style="margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                + Tambah Kasir Baru
            </h3>
            <form method="POST" action="">
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Username (Login)</label>
                        <input type="text" name="username" class="form-control" required placeholder="Contoh: kasir01">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Siti Aminah">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Password</label>
                        <input type="text" name="password" class="form-control" required placeholder="Password">
                    </div>
                </div>
                <br><button type="submit" name="add_kasir" class="btn btn-primary">Simpan Data</button>
            </form>
        </div>
        <div class="card">
            <h2>Daftar Pegawai Kasir</h2><br>
            <form method="GET" action="" class="search-box">
                <input type="text" name="q" class="search-input" placeholder="Cari nama atau username" value="<?= isset($data['search']) ? htmlspecialchars($data['search']) : '' ?>">
                <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">Cari</button>
                <?php if(isset($data['search']) && $data['search'] != ''){ ?>
                    <a href="adminKasir.php" class="btn btn-danger" style="padding: 8px 15px;">Reset</a>
                <?php } ?>
            </form>
            <br>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Status Akun</th>
                        <th width="20%">Aksi</th> </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['allKasir'])): ?>
                        <tr><td colspan="5" style="text-align:center; padding: 20px;">Data kasir tidak ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($data['allKasir'] as $row){ ?>
                        <tr>
                            <td>#<?= $row['id_kasir']; ?></td>
                            <td><b><?= htmlspecialchars($row['username']); ?></b></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                            <td>
                                <?php if ($row['status'] == 1){ ?>
                                    <span class="badge bg-active">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-inactive">Non-Aktif</span>
                                <?php } ?>
                            </td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id_kasir']; ?>">
                                    <button type="submit" name="trigger_edit" class="btn btn-edit">Edit</button>
                                </form>

                                <?php if ($row['status'] == 1){ ?>
                                    <a href="?aksi=status&id=<?= $row['id_kasir']; ?>&val=0" 
                                    class="btn btn-danger"
                                    onclick="return confirm('Non-aktifkan kasir ini?')">Non-aktifkan</a>
                                <?php } else { ?>
                                    <a href="?aksi=status&id=<?= $row['id_kasir']; ?>&val=1" 
                                    class="btn btn-success">Aktifkan</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
            <footer>
                Copyright &copy; Hotel <?php echo date('Y'); ?>
            </footer>
            </div>
    </div>
</body>
</html>
