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
            <table class="info-box-child">
                <tr>
                    <th>ID KASIR</th>
                    <td><?php echo $data['id_kasir'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>NAMA KASIR</th>
                    <td><?php echo $data['nama_kasir'] ?? $_SESSION['username']; ?></td>
                </tr>
                <tr>
                    <th>STATUS</th>
                    <td>Aktif</td>
                </tr>
            </table>
        </div>

        <div class="info-kamar">
            Informasi Jenis Kamar
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
                            <td class="td-center"><?php echo $no++; ?></td>
                            <td><?php echo $order['nama_lengkap'] ?? '-'; ?></td>
                            <td><?php echo $order['nama_tipe'] ?? '-'; ?></td>
                            <td class="td-center"><?php echo date('d/m/Y', strtotime($order['tgl_check_in'])); ?></td>
                            <td class="td-center"><?php echo date('d/m/Y', strtotime($order['tgl_check_out'])); ?></td>
                            <td class="td-center">
                                <a href="../../controller/kasir/OnlineOrderEditController.php?id=<?php echo $order['id']; ?>">
                                    <button type="button" class="btn-primary">EDIT</button>
                                </a>
                            </td>
                            <td class="td-center">
                                <form method="POST" style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_booking" value="<?php echo $order['id']; ?>">
                                    <button type="submit" class="btn-danger">DELETE</button>
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

        <div class="section-title">PENDING BOOKINGS</div>

        <div class="form-container" style="padding: 0; overflow: hidden;">

            <table class="table table-striped" style="margin-bottom: 0; box-shadow: none; border-radius: 8;">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Kamar</th>
                        <th>Tipe</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
<<<<<<< HEAD
                <?php else: ?>
                    <?php foreach ($data['pendingBookings'] as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['kode_booking']); ?></td>
                        <td><?php echo htmlspecialchars($booking['nomor_kamar'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($booking['nama_tipe']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($booking['tgl_check_in'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($booking['tgl_check_out'])); ?></td>
                        <td>Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge bg-warning">PENDING</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalBayar<?php echo $booking['id']; ?>">
                                Konfirmasi Bayar
                            </button>
                        </td>
                    </tr>
                    
                    <div class="modal fade" id="modalBayar<?php echo $booking['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="konfirmasi_bayar">
                                        <input type="hidden" name="id_booking" value="<?php echo $booking['id']; ?>">
                                        
                                        <p><strong>Kode Booking:</strong> <?php echo $booking['kode_booking']; ?></p>
                                        <p><strong>Nama Tamu:</strong> <?php echo $booking['nama_lengkap']; ?></p>
                                        <p><strong>Total:</strong> Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?></p>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Metode Pembayaran</label>
                                            <select name="metode_bayar" class="form-select" required>
                                                <option value="">-- Pilih Metode --</option>
                                                <option value="tunai">Tunai</option>
                                                <option value="transfer">Transfer Bank</option>
                                                <option value="qris">QRIS</option>
                                                <option value="debit">Kartu Debit</option>
                                                <option value="kredit">Kartu Kredit</option>
                                            </select>
=======
                </thead>
                <tbody>
                    <?php if (empty($data['pendingBookings'])): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada booking online yang perlu dikonfirmasi</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['pendingBookings'] as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['kode_booking']); ?></td>
                                <td><?php echo htmlspecialchars($booking['nomor_kamar'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($booking['nama_tipe']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($booking['tgl_check_in'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($booking['tgl_check_out'])); ?></td>
                                <td>Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge bg-warning">PENDING</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modalBayar<?php echo $booking['id']; ?>">
                                        Konfirmasi
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalBayar<?php echo $booking['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
>>>>>>> refs/remotes/origin/main
                                        </div>

                                        <form method="POST">
                                            <div class="modal-body">

                                                <input type="hidden" name="action" value="konfirmasi_bayar">
                                                <input type="hidden" name="id_booking" value="<?php echo $booking['id']; ?>">

                                                <div class="info-block">
                                                    <div class="info-row">
                                                        <span class="info-label">Kode Booking</span>
                                                        <span class="info-value"><?php echo $booking['kode_booking']; ?></span>
                                                    </div>

                                                    <div class="info-row">
                                                        <span class="info-label">Nama Tamu</span>
                                                        <span class="info-value"><?php echo $booking['nama_lengkap']; ?></span>
                                                    </div>
                                                </div>

                                                <div class="total-box">
                                                    <div class="total-label">Total Pembayaran</div>
                                                    <div class="total-value">
                                                        Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?>
                                                    </div>
                                                </div>

                                                <div class="mb-3" style="margin-top: 18px;">
                                                    <label class="form-label">Metode Pembayaran</label>
                                                    <select name="metode_bayar" class="form-select" required>
                                                        <option value="">-- Pilih Metode --</option>
                                                        <option value="tunai">Tunai</option>
                                                        <option value="transfer">Transfer Bank</option>
                                                        <option value="qris">QRIS</option>
                                                        <option value="debit">Kartu Debit</option>
                                                        <option value="kredit">Kartu Kredit</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Konfirmasi</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <footer>
            Copyright &copy; Ivory Palace <?php echo date('Y'); ?>
        </footer>
    </div>
</body>

</html>