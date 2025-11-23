<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}
$username = $_SESSION['username'];
$id_kasir = 'KSR001';
$nama_kasir = $username;

$query_kasir = "SELECT id_kasir FROM kasir LIMIT 1";
$result_kasir = $conn->query($query_kasir);
if ($result_kasir && $result_kasir->num_rows > 0) {
    $kasir = $result_kasir->fetch_assoc();
    $id_kasir = $kasir['id_kasir'];
}

$query_tipe = "SELECT * FROM tipe_kamar";
$result_tipe = $conn->query($query_tipe);

$query_kamar = "SELECT k.id, k.nomor_kamar, k.lantai, k.id_tipe_kamar,
                       tk.nama_tipe, tk.harga_per_malam
                FROM kamar k
                JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                WHERE k.status = 'tersedia'";
$result_kamar = $conn->query($query_kamar);

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete' && isset($_POST['id_booking'])) {
            $id_booking = $_POST['id_booking'];

            $query_get_kamar = "SELECT id_kamar FROM booking WHERE id = ?";
            $stmt = $conn->prepare($query_get_kamar);
            $stmt->bind_param("i", $id_booking);
            $stmt->execute();
            $result_kamar_booking = $stmt->get_result();
            $kamar_booking = $result_kamar_booking->fetch_assoc();

            $query_del_bayar = "DELETE FROM pembayaran WHERE id_booking = ?";
            $stmt = $conn->prepare($query_del_bayar);
            $stmt->bind_param("i", $id_booking);
            $stmt->execute();

            $query_del = "DELETE FROM booking WHERE id = ?";
            $stmt = $conn->prepare($query_del);
            $stmt->bind_param("i", $id_booking);

            if ($stmt->execute()) {
                if ($kamar_booking) {
                    $query_update_kamar = "UPDATE kamar SET status = 'tersedia' WHERE id = ?";
                    $stmt = $conn->prepare($query_update_kamar);
                    $stmt->bind_param("i", $kamar_booking['id_kamar']);
                    $stmt->execute();
                }
                $message = "Booking berhasil dihapus!";
                $message_type = "success";
            } else {
                $message = "Gagal menghapus booking!";
                $message_type = "error";
            }
        }
        elseif ($_POST['action'] === 'create') {
            $nama = $_POST['nama'];
            $no_ktp = $_POST['no_ktp'];
            $no_telp = $_POST['no_telp'];
            $alamat = $_POST['alamat'];
            $id_kamar = $_POST['id_kamar'];
            $tanggal_checkin = $_POST['tanggal_checkin'];
            $tanggal_checkout = $_POST['tanggal_checkout'];
            $metode_bayar = $_POST['metode_bayar'];
            $total_harga = $_POST['total_harga'];

            $id_tamu = 'TMU' . date('YmdHis');
            $id_booking = 'BKG' . date('YmdHis');
            $id_pembayaran = 'PBY' . date('YmdHis');

            $query_tamu = "INSERT INTO tamu (username, nama_lengkap, no_hp, status) VALUES (?, ?, ?, 1)";
            $stmt = $conn->prepare($query_tamu);
            $stmt->bind_param("sss", $no_ktp, $nama, $no_telp);
            $stmt->execute();
            $id_tamu = $conn->insert_id;

            $status_booking = 'dibayar';
            $kode_booking = 'BKG' . date('YmdHis');
            $query_booking = "INSERT INTO booking (kode_booking, id_tamu, id_kamar, tgl_check_in, tgl_check_out, total_harga, status)
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query_booking);
            $stmt->bind_param("siissds", $kode_booking, $id_tamu, $id_kamar, $tanggal_checkin, $tanggal_checkout, $total_harga, $status_booking);
            $stmt->execute();
            $id_booking = $conn->insert_id;

            $status_pembayaran = 'berhasil';
            $query_bayar = "INSERT INTO pembayaran (id_booking, metode_bayar, jumlah_bayar, tgl_bayar, status_pembayaran)
                           VALUES (?, ?, ?, NOW(), ?)";
            $stmt = $conn->prepare($query_bayar);
            $stmt->bind_param("ssds", $id_booking, $metode_bayar, $total_harga, $status_pembayaran);
            $stmt->execute();

            $query_update = "UPDATE kamar SET status = 'terisi' WHERE id = ?";
            $stmt = $conn->prepare($query_update);
            $stmt->bind_param("i", $id_kamar);
            $stmt->execute();

            $message = "Booking berhasil dibuat!";
            $message_type = "success";

            $result_kamar = $conn->query($query_kamar);
        }
    }
}

$today = date('Y-m-d');
$query_orders = "SELECT b.*, t.nama_lengkap, k.nomor_kamar, p.jumlah_bayar, p.status_pembayaran
                 FROM booking b
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN kamar k ON b.id_kamar = k.id
                 LEFT JOIN pembayaran p ON b.id = p.id_booking
                 WHERE DATE(b.tgl_check_in) = ?
                 ORDER BY b.id DESC";
$stmt = $conn->prepare($query_orders);
$stmt->bind_param("s", $today);
$stmt->execute();
$result_orders = $stmt->get_result();

$currentDateTime = date('d-m-y');
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
                <li><a href="kasirPage.php">DASHBOARD</a></li>
                <li><a href="OnlineOrder.php">ONLINE ORDER</a></li>
                <li><a href="otsOrder.php" class="active">OTS ORDER</a></li>
                <li><a href="occupancy.php">OCCUPANCY</a></li>
                <li><a href="kasirPage.php?logout=true">Logout</a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <p>Logged in as:</p>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
    </div>
    </sidebar>
    <div class="main-content">
        <h1>OTS Order</h1>
        <div class="section-title">BUAT BOOKING BARU (WALK-IN)</div>

        <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="info-box">
            <p>ID KASIR: <?php echo $id_kasir; ?></p>
            <p>NAMA KASIR: <?php echo $nama_kasir; ?></p>
            <p>TANGGAL: <?php echo date('d/m/Y'); ?></p>
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
                        <?php while ($kamar = $result_kamar->fetch_assoc()): ?>
                        <option value="<?php echo $kamar['id']; ?>" data-harga="<?php echo $kamar['harga_per_malam']; ?>">
                            <?php echo $kamar['nomor_kamar'] . ' - ' . $kamar['nama_tipe'] . ' - Lt.' . $kamar['lantai'] . ' (Rp ' . number_format($kamar['harga_per_malam'], 0, ',', '.') . '/malam)'; ?>
                        </option>
                        <?php endwhile; ?>
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
                <button type="submit" class="btn-action">PROSES BOOKING</button>
                <button type="reset" class="btn-action secondary">RESET</button>
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
                    <th>NO</th>
                    <th>ID BOOKING</th>
                    <th>NAMA TAMU</th>
                    <th>NO KAMAR</th>
                    <th>CHECK-IN</th>
                    <th>CHECK-OUT</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($result_orders->num_rows > 0):
                    while ($order = $result_orders->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $order['kode_booking']; ?></td>
                    <td><?php echo $order['nama_lengkap'] ?? '-'; ?></td>
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
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Belum ada booking hari ini</td>
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
