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
        
        <div class="card">
            <div class="header-edit">
                <h2>Edit Data Kamar</h2>
            </div>

            <form method="POST" action="adminKamar.php">
                
                <input type="hidden" name="id" value="<?= $dataEdit['id']; ?>">

                <div class="form-group">
                    <label>Nomor Kamar</label>
                    <input type="text" name="nomor_kamar" value="<?= htmlspecialchars($dataEdit['nomor_kamar']); ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Tipe Kamar</label>
                    <select name="id_tipe" class="form-control" required>
                        <option value="">-- Pilih Tipe --</option>
                        <?php foreach ($listTipe as $tipe): ?>
                            <?php $selected = ($tipe['id'] == $dataEdit['id_tipe_kamar']) ? 'selected' : ''; ?>
                            <option value="<?= $tipe['id']; ?>" <?= $selected; ?>>
                                <?= htmlspecialchars($tipe['nama_tipe']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Lantai</label>
                    <input type="number" name="lantai" value="<?= htmlspecialchars($dataEdit['lantai']); ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status Kamar</label>
                    <select name="status" class="form-control">
                        <option value="tersedia" <?= ($dataEdit['status_kamar'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="terisi" <?= ($dataEdit['status_kamar'] == 'terisi') ? 'selected' : ''; ?>>Terisi (Tamu)</option>
                        <option value="perbaikan" <?= ($dataEdit['status_kamar'] == 'perbaikan') ? 'selected' : ''; ?>>Perbaikan (Maintenance)</option>
                    </select>
                    <small style="color: #64748b; font-size: 12px;">*Gunakan 'Perbaikan' jika kamar rusak/sedang dibersihkan.</small>
                </div>

                <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                    <a href="adminKamar.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="update_kamar" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>
