<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Order - Hotel</title>
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
                <li><a href="../../controller/kasir/OnlineOrderController.php" class="active">ONLINE ORDER</a></li>
                <li><a href="../../controller/kasir/OtsOrderController.php">OTS ORDER</a></li>
                <li><a href="../../controller/kasir/OccupancyController.php">OCCUPANCY</a></li>
                <li><a href="../../controller/kasir/OnlineOrderController.php?logout=true">Logout</a></li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $data['username']; ?></span>
        </div>
    </div>
    </sidebar>
    <div class="main-content">
        <h1>Online Order</h1>

        <div class="section-title">TABEL ONLINE ORDER</div>

        <?php if ($data['message']): ?>
        <div class="alert alert-<?php echo $data['message_type']; ?>">
            <?php echo $data['message']; ?>
        </div>
        <?php endif; ?>

        <div class="info-box">
            <p>ID KASIR: <?php echo $data['id_kasir']; ?></p>
            <p>NAMA KASIR: <?php echo $data['nama_kasir']; ?></p>
            <p>STATUS: Aktif</p>
        </div>

        <div class="info-kamar">
            INFORMASI JENIS KAMAR
        </div>

        <table class="order-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA CUST</th>
                    <th>JENIS KAMAR</th>
                    <th>TANGGAL CEK IN</th>
                    <th>TANGGAL CEK OUT</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['onlineOrders'])): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($data['onlineOrders'] as $order): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $order['nama_lengkap'] ?? '-'; ?></td>
                        <td><?php echo $order['nama_tipe'] ?? '-'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['tgl_check_in'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['tgl_check_out'])); ?></td>
                        <td><button class="icon-btn">📝</button></td>
                        <td>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_booking" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="icon-btn">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada online order</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- <button class="btn-tambah">+ TAMBAH ORDER</button> -->

        <div class="btn-container">
            <button class="btn-action secondary">CETAK STRUK</button>
            <button class="btn-action">SIMPAN</button>
        </div>

        <footer>
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
