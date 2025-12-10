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
        <header style="margin-bottom: 20px;">
            <h1>Manajemen Kamar</h1>
        </header>

        <?php if (!empty($data['pesan'])): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?= $data['pesan']; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3>+ Tambah Kamar Fisik</h3>
            <form method="POST" action="">
                <div style="display: flex; gap: 15px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label>Nomor Kamar</label>
                        <input type="text" name="nomor_kamar" class="form-control" required placeholder="101">
                    </div>
                    <div style="flex: 1;">
                        <label>Tipe Kamar</label>
                        <select name="id_tipe" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($data['listTipe'] as $tipe): ?>
                                <option value="<?= $tipe['id']; ?>"><?= $tipe['nama_tipe']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label>Lantai</label>
                        <input type="number" name="lantai" class="form-control" required placeholder="1">
                    </div>
                    <button type="submit" name="add_kamar" class="btn btn-primary" style="height: 42px;">Simpan</button>
                </div>
            </form>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e2e8f0;">

            <p style="font-weight: 500; color: #64748b; margin-bottom: 10px;">Menu Data Master:</p>
            <div class="action-buttons">
                <a href="adminKamar.php?view=tambah_fasilitas" class="btn btn-info">
                    + Tambah Fasilitas Baru
                </a>
                
                <a href="adminKamar.php?view=tambah_tk" class="btn btn-info">
                    + Tambah Tipe Kamar
                </a>
            </div>
        </div>

        <form method="GET" action="">
            <input type="text" name="q" placeholder="Cari 101, Deluxe, dll..." 
                value="<?= isset($data['search']) ? htmlspecialchars($data['search']) : '' ?>" 
                class="search-input" style="padding:8px; border:1px solid #ccc; border-radius:5px;">
            
            <button type="submit" class="btn btn-primary" style="padding:8px 15px;">Cari</button>
            
            <?php if(!empty($data['search'])){ ?>
                <a href="adminKamar.php" class="btn btn-danger" style="padding:8px 15px; text-decoration:none;">Reset</a>
            <?php } ?>
        </form>

        <div class="card">
            <h3>Daftar Kamar Hotel</h3>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No. Kamar</th>
                        <th>Tipe</th>
                        <th>Lantai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['allKamar'])){ ?>
                        <tr><td colspan="5" align="center">Belum ada data kamar.</td></tr>
                    <?php }else { ?>
                        <?php foreach ($data['allKamar'] as $row) { ?>
                        <tr>
                            <td><b><?= $row['nomor_kamar']; ?></b></td>
                            <td><?= $row['nama_tipe']; ?></td>
                            <td><?= $row['lantai']; ?></td>
                            <td><?= $row['status_kamar']; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="trigger_edit" class="btn" style="background:#eab308; padding:5px 10px;">Edit</button>
                                </form>
                                <a href="?aksi=hapus&id=<?= $row['id']; ?>" class="btn" style="background:#ef4444; padding:5px 10px;" onclick="return confirm('Hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
