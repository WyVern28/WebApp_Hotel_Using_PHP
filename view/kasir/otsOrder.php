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
    <title>OTS Order - Hotel</title>
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
                <li><a href="../../controller/kasir/OtsOrderController.php" class="active">OTS ORDER</a></li>
                <li><a href="../../controller/kasir/OccupancyController.php">OCCUPANCY</a></li>
                <li><a href="../../controller/kasir/OtsOrderController.php?logout=true">Logout</a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $data['username']; ?></span>
        </div>
    </div>
    </sidebar>
    <div class="main-content">
        <h1>OTS Order</h1>
        <div class="section-title">WALK-IN BOOKING</div>

        <?php if ($data['message']): ?>
        <div class="alert alert-<?php echo $data['message_type']; ?>">
            <?php echo $data['message']; ?>
        </div>
        <?php endif; ?>

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

        <form method="POST" class="form-container" id="bookingForm">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="total_harga" id="total_harga_input" value="0">

            <h3>Data Tamu</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-input" placeholder="Masukkan nama tamu" required>
                </div>
                <div class="form-group">
                    <label>No. KTP</label>
                    <input type="text" name="no_ktp" class="form-input" placeholder="Masukkan no KTP" required>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp" class="form-input" placeholder="Masukkan no telepon" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-input" placeholder="Masukkan alamat" required>
                </div>
            </div>

            <h3>Data Booking</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>No. Kamar (Tersedia)</label>
                    <select name="id_kamar" class="form-input" id="select_kamar" required>
                        <option value="" data-harga="0">Pilih Kamar</option>
                        <?php foreach ($data['availableRooms'] as $kamar): ?>
                        <option value="<?php echo $kamar['id']; ?>" data-harga="<?php echo $kamar['harga_per_malam']; ?>">
                            <?php echo $kamar['nomor_kamar'] . ' - ' . $kamar['nama_tipe'] . ' - Lt.' . $kamar['lantai'] . ' (Rp ' . number_format($kamar['harga_per_malam'], 0, ',', '.') . '/malam)'; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Check-in</label>
                    <input type="date" name="tanggal_checkin" class="form-input" id="checkin" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Check-out</label>
                    <input type="date" name="tanggal_checkout" class="form-input" id="checkout" required>
                </div>
                <div class="form-group">
                    <label>Jumlah Malam</label>
                    <input type="number" class="form-input" id="jumlah_malam" value="1" readonly>
                </div>
            </div>

            <h3>Pembayaran</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Total Harga</label>
                    <input type="text" class="form-input" id="total_display" value="Rp 0" readonly>
                </div>
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_bayar" class="form-input" required>
                        <option value="">Pilih Metode</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="debit">Kartu Debit</option>
                        <option value="credit">Kartu Kredit</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action btn-blue">PROSES BOOKING</button>
                <button type="reset" class="btn-action secondary btn-blue">RESET</button>
            </div>
        </form>

        <script>
        function hitungTotal() {
            const kamar = document.getElementById('select_kamar');
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const harga = kamar.options[kamar.selectedIndex].dataset.harga || 0;

            if (checkin && checkout) {
                const date1 = new Date(checkin);
                const date2 = new Date(checkout);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    document.getElementById('jumlah_malam').value = diffDays;
                    const total = harga * diffDays;
                    document.getElementById('total_display').value = 'Rp ' + total.toLocaleString('id-ID');
                    document.getElementById('total_harga_input').value = total;
                }
            }
        }

        document.getElementById('select_kamar').addEventListener('change', hitungTotal);
        document.getElementById('checkin').addEventListener('change', hitungTotal);
        document.getElementById('checkout').addEventListener('change', hitungTotal);
        </script>

        <div class="section-title" style="margin-top: 30px;">DAFTAR OTS ORDER HARI INI</div>

        <table class="order-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                    <th>NO</th>
                    <th>ID BOOKING</th>
                    <th>NAMA TAMU</th>
                    <th>NO KTP</th>
                    <th>NO KAMAR</th>
                    <th>CHECK-IN</th>
                    <th>CHECK-OUT</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['todayOrders'])): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($data['todayOrders'] as $order): ?>
                    <tr>
                        <td class="td-center">
                            <input type="checkbox" name="selected_bookings[]" value="<?php echo $order['id']; ?>" class="booking-checkbox">
                        </td>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $order['kode_booking']; ?></td>
                        <td><?php echo $order['nama_lengkap'] ?? '-'; ?></td>
                        <td><?php echo $order['no_ktp'] ?? '-'; ?></td>
                        <td><?php echo $order['nomor_kamar']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['tgl_check_in'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['tgl_check_out'])); ?></td>
                        <td>Rp <?php echo number_format($order['total_harga'] ?? 0, 0, ',', '.'); ?></td>
                        <td>
                            <?php if ($order['status'] === 'dibayar' || $order['status_pembayaran'] === 'berhasil'): ?>
                                <span class="status-paid">PAID</span>
                            <?php else: ?>
                                <span class="status-pending">PENDING</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_booking" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="btn-delete">DELETE</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align: center;">Belum ada booking hari ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="btn-container" style="margin-top: 20px;">
            <button class="btn-action btn-blue" onclick="cetakStruk()">🖨️ CETAK STRUK</button>
        </div>

        <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.booking-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        function cetakStruk() {
            const selected = [];
            document.querySelectorAll('.booking-checkbox:checked').forEach(cb => {
                selected.push(cb.value);
            });

            if (selected.length === 0) {
                alert('Pilih minimal 1 booking untuk dicetak!');
                return;
            }

            const ids = selected.join(',');
            window.open('../../controller/kasir/PrintStrukController.php?ids=' + ids, '_blank');
        }
        </script>

        <footer>
            Copyright &copy; Hotel <?php echo date('Y'); ?>
        </footer>
    </div>
</body>
</html>
