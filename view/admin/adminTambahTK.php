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
        <h1 style="text-align: center; margin-bottom: 20px;">Master Tipe Kamar</h1>
        
        <div class="card">
            <form method="POST" action="adminKamar.php" enctype="multipart/form-data"> 
                
                <div class="form-group">
                    <label>Nama Tipe Kamar</label>
                    <input type="text" name="nama_tipe" class="form-control" required placeholder="Contoh: Deluxe Room">
                </div>

                <div style="display:flex; gap:15px;">
                    <div style="flex:1;">
                        <label>Harga Per Malam (Rp)</label>
                        <input type="number" name="harga" class="form-control" required placeholder="500000">
                    </div>
                    <div style="flex:1;">
                        <label>Harga Sarapan (Rp)</label>
                        <input type="number" name="harga_sarapan" class="form-control" placeholder="0 jika gratis">
                    </div>
                </div>
                
                <div style="margin-top:15px;">
                    <label>Kapasitas Orang</label>
                    <input type="number" name="kapasitas" class="form-control" required placeholder="2">
                </div>

                <div class="form-group">
                    <label>Foto Kamar</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small style="color:grey;">Format: JPG, PNG, WEBP. Max 2MB.</small>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi fasilitas kamar..."></textarea>
                </div>

                <div class="form-group">
                    <label>Pilih Fasilitas:</label>
                    <div class="fasilitas-grid">
                        <?php if(!empty($listFasilitas)): ?>
                            <?php foreach ($listFasilitas as $fas): ?>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="fasilitas[]" value="<?= $fas['id']; ?>" id="fas_<?= $fas['id']; ?>">
                                    <label for="fas_<?= $fas['id']; ?>" style="margin:0; font-weight:normal; cursor:pointer;">
                                        <?= htmlspecialchars($fas['nama_fasilitas']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="font-size:12px; color:red;">Belum ada data fasilitas master.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-top: 25px; display: flex; justify-content: space-between;">
                    <a href="adminKamar.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" name="save_tipe" class="btn btn-primary">Simpan Tipe & Fasilitas</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
