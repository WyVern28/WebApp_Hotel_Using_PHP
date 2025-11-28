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
            <p>ID KASIR: <?php echo $data['id_kasir']; ?></p>
            <p>NAMA KASIR: <?php echo $data['nama_kasir']; ?></p>
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
                <?php if (!empty($data['occupiedRooms'])): ?>
                    <?php foreach ($data['occupiedRooms'] as $index => $room): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $room['nomor_kamar']; ?></td>
                        <td><?php echo $room['nama_lengkap']; ?></td>
                        <td><?php echo $room['nama_tipe']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($room['tgl_check_in'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($room['tgl_check_out'])); ?></td>
                        <td class="status-occupied"><?php echo strtoupper($room['status']); ?></td>
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
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
