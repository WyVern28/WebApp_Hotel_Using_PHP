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
    <link rel="stylesheet" href="../../asset/css/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a href="../../controller/user/IndexController.php">Home</a>
            <a href="../../controller/user/BookingController.php">Booking</a>
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