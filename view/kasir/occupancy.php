<?php
// Proteksi: File ini hanya bisa diakses melalui controller
if (!isset($data)) {
    die('Akses ditolak! Halaman ini harus diakses melalui controller.');
}
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
                    <li><a href="../../controller/kasir/DashboardController.php">DASHBOARD</a></li>
                    <li><a href="../../controller/kasir/OnlineOrderController.php">ONLINE ORDER</a></li>
                    <li><a href="../../controller/kasir/OtsOrderController.php">OTS ORDER</a></li>
                    <li><a href="../../controller/kasir/OccupancyController.php" class="active">OCCUPANCY</a></li>
                    <li><a href="../../controller/kasir/OccupancyController.php?logout=true">Logout</a></li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <p>Logged in as:</p>
                <span><?php echo $data['username']; ?></span>
            </div>
        </div>
    </sidebar>

    <div class="main-content">
        <h1>Occupancy</h1>

        <div class="section-title">TABEL OCCUPANCY KAMAR</div>

        <div class="info-box">
            <table class="info-box-child">
                <tr>
                    <th>ID KASIR</th>
                    <td><?php echo $data['id_kasir']; ?></td>
                </tr>
                <tr>
                    <th>NAMA KASIR</th>
                    <td><?php echo $data['nama_kasir']; ?></td>
                </tr>
                <tr>
                    <th>STATUS</th>
                    <td>Aktif</td>
                </tr>
            </table>
        </div>
        <div class="filter-container">
            <button class="btn-filter btn-blue">FILTER</button>
            <button class="btn-filter btn-blue">SEARCH</button>
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
                <?php if (!empty($data['occupiedRooms'])): ?>
                    <?php foreach ($data['occupiedRooms'] as $index => $room): ?>
                        <tr>
                            <td class="td-center"><?php echo $index + 1; ?></td>
                            <td class="td-center"><?php echo $room['nomor_kamar']; ?></td>
                            <td><?php echo $room['nama_lengkap']; ?></td>
                            <td><?php echo $room['nama_tipe']; ?></td>
                            <td class="td-center"><?php echo date('d/m/Y', strtotime($room['tgl_check_in'])); ?></td>
                            <td class="td-center"><?php echo date('d/m/Y', strtotime($room['tgl_check_out'])); ?></td>
                            <td class="status-occupied td-center"><?php echo strtoupper($room['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada kamar yang terisi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <footer>
            Copyright &copy; Ivory Palace <?php echo date('Y'); ?>
        </footer>
    </div>
</body>

</html>