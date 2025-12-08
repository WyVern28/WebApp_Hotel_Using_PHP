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
    <title>Profile - Hotel</title>
    <link rel="stylesheet" href="../../asset/css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .profile-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            margin-right: 20px;
        }
        .profile-info h2 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .profile-info p {
            margin: 0;
            color: #666;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .booking-table {
            width: 100%;
            border-collapse: collapse;
        }
        .booking-table th,
        .booking-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .booking-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
        }
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-left">
            <span class="username">
                Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                (<?php echo htmlspecialchars($_SESSION['role']); ?>)
            </span>
            <a href="../../controller/user/ProfileController.php?logout=true" class="logout">Logout</a>
        </div>

        <div class="nav-center">
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="../../controller/user/ProfileController.php">Profile</a>
        </div>

        <div class="nav-right">
            <a href="index.php" class="brand">Hotel Management</a>
        </div>
    </nav>

    <div class="profile-container">
        <!-- Alert Messages -->
        <?php if ($data['message']): ?>
            <div class="alert alert-<?php echo $data['message_type']; ?>">
                <?php echo htmlspecialchars($data['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($data['profile']['nama_lengkap'], 0, 1)); ?>
                </div>
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($data['profile']['nama_lengkap']); ?></h2>
                    <p>@<?php echo htmlspecialchars($data['username']); ?> • Member sejak <?php echo date('d M Y', strtotime($data['profile']['dibuat_pada'])); ?></p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab('profile')">Data Profile</button>
                <button class="tab" onclick="switchTab('password')">Ubah Password</button>
                <button class="tab" onclick="switchTab('history')">Riwayat Booking</button>
            </div>

            <!-- Tab: Profile -->
            <div id="tab-profile" class="tab-content active">
                <h3>Update Profile</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($data['username']); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap *</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" 
                               value="<?php echo htmlspecialchars($data['profile']['nama_lengkap']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">No. Handphone *</label>
                        <input type="text" id="no_hp" name="no_hp" 
                               value="<?php echo htmlspecialchars($data['profile']['no_hp']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>

            <!-- Tab: Password -->
            <div id="tab-password" class="tab-content">
                <h3>Ubah Password</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="old_password">Password Lama *</label>
                        <input type="password" id="old_password" name="old_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Password Baru *</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>
            </div>

            <!-- Tab: Booking History -->
            <div id="tab-history" class="tab-content">
                <h3>Riwayat Booking</h3>
                <?php if (empty($data['bookingHistory'])): ?>
                    <p style="text-align: center; color: #999; padding: 40px 0;">
                        Belum ada riwayat booking
                    </p>
                <?php else: ?>
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Kamar</th>
                                <th>Tipe</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['bookingHistory'] as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['kode_booking']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['nomor_kamar']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['nama_tipe']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($booking['tgl_check_in'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($booking['tgl_check_out'])); ?></td>
                                    <td>Rp <?php echo number_format($booking['jumlah_bayar'] ?? $booking['total_harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?php
                                        $status = $booking['status'];
                                        $badgeClass = 'badge-warning';
                                        if ($status === 'dibayar' || $status === 'lunas') $badgeClass = 'badge-success';
                                        if ($status === 'batal') $badgeClass = 'badge-danger';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo strtoupper(htmlspecialchars($status)); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>