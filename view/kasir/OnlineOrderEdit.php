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
    <title>Edit Online Order - Hotel</title>
    <link rel="stylesheet" href="../../asset/css/kasir.css">
    <style>
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #357abd;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
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
                    <li><a href="../../controller/kasir/DashboardController.php?logout=true">Logout</a></li>
                </ul>
            </div>
            <div class="sidebar-footer">
                <p>Logged in as:</p>
                <span><?php echo $data['username']; ?></span>
            </div>
        </div>
    </sidebar>

    <div class="main-content">
        <h1>Edit Online Order</h1>

        <?php if ($data['message']): ?>
            <div class="alert alert-<?php echo $data['message_type']; ?>">
                <?php echo $data['message']; ?>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 1000px;">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Kode Booking</label>
                    <input type="text" value="<?= $data['bookingData']['kode_booking']; ?>" class="form-control"
                        readonly style="background-color: #e2e8f0;">
                </div>

                <table style="width:100%; background:#f1f5f9; padding:20px; border-radius:10px;">
                    <tr>
                        <td style="width:50%; padding-right:15px; vertical-align:top;">
                            <div class="form-group">
                                <label>Nama Tamu</label>
                                <input type="text" value="<?= $data['bookingData']['nama_lengkap']; ?>"
                                    class="form-control" readonly style="background-color: #e2e8f0;">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Check-In <span style="color:red;">*</span></label>
                                <input type="date" name="tgl_check_in"
                                    value="<?= $data['bookingData']['tgl_check_in']; ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Total Harga <span style="color:red;">*</span></label>
                                <input type="number" name="total_harga"
                                    value="<?= $data['bookingData']['total_harga']; ?>" class="form-control" required
                                    min="0" step="1000">
                            </div>
                        </td>

                        <td style="width:50%; padding-left:15px; vertical-align:top;">
                            <div class="form-group">
                                <label>Pilih Kamar <span style="color:red;">*</span></label>
                                <select name="id_kamar" class="form-control" required>
                                    <option value="">-- Pilih Kamar --</option>
                                    <option value="<?= $data['bookingData']['id_kamar']; ?>" selected>
                                        <?= $data['bookingData']['nomor_kamar']; ?> -
                                        <?= $data['bookingData']['nama_tipe']; ?> (Kamar Saat Ini)
                                    </option>

                                    <?php foreach ($data['availableRooms'] as $room): ?>
                                        <option value="<?= $room['id']; ?>">
                                            <?= $room['nomor_kamar']; ?> - <?= $room['nama_tipe']; ?>
                                            (Rp <?= number_format($room['harga_per_malam'], 0, ',', '.'); ?>/malam)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Check-Out <span style="color:red;">*</span></label>
                                <input type="date" name="tgl_check_out"
                                    value="<?= $data['bookingData']['tgl_check_out']; ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" value="<?= ucfirst($data['bookingData']['status']); ?>"
                                    class="form-control" readonly style="background-color: #ffffffff;">
                            </div>
                        </td>
                    </tr>
                </table>



                <br>

                <button type="submit" name="update_booking" class="btn btn-primary">Simpan Perubahan</button>

                <a href="../../controller/kasir/OnlineOrderController.php" class="btn btn-secondary">Batal</a>

            </form>
        </div>

        <footer>
            Copyright &copy; Ivory Palace <?php echo date('Y'); ?>
        </footer>
    </div>
</body>

</html>