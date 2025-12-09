<?php
// session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../asset/css/booking.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-right">
            <a href="index.php" class="brand">Ivory Palace</a>
        </div>

        <div class="nav-center">
            <a href="../../controller/user/IndexController.php">Home</a>
            <a href="../../controller/user/BookingController.php">Booking</a>
            <a href="../../controller/user/ProfileController.php">Profile</a>
        </div>

        <div class="nav-left">
            <span class="username">
                Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                (<?php echo htmlspecialchars($_SESSION['role']); ?>)
            </span>
            <a href="../../controller/user/IndexController.php?logout=true" class="logout">Logout</a>
        </div>

    </nav>

    <div class="detail-container">

        <div class="detail-content">

            <h1 class="detail-title">Family Suite</h1>
            <p class="detail-loc">Bali, Indonesia</p>

            <div class="gallery-container">
                <div class="gallery-main">
                    <img src="../../asset/image/col.jpg" alt="Main Image">
                </div>
            </div>

            <hr class="divider">

            <h3>Tentang Hotel Ini</h3>
            <p class="detail-desc">
                Nikmati pemandangan sunset pantai Kuta langsung dari balkon kamar.
                Suasana tropis yang menenangkan dengan pelayanan kelas dunia.
                Sangat cocok untuk liburan keluarga nii
            </p>

            <h3>Fasilitas Utama</h3>
            <ul class="facility-list">
                <li> Pantai Privat</li>
                <li> Kolam Renang Infinity</li>
                <li> WiFi Gratis</li>
                <li> Sarapan Buffet</li>
                <li> Spa & Massage</li>
                <li> Layanan Kamar 24 Jam</li>
            </ul>

            <hr class="divider">

            <div class="review-section">
                <h3>Ulasan Tamu</h3>
                <div class="review-score">
                    <span class="score-badge">4.8/5</span>
                    <span class="score-text">Luar Biasa &middot; Dari 120 ulasan</span>
                </div>
                <div class="review-card">
                    <div class="reviewer-info">
                        <strong>Valenta</strong>
                        <small>Januari 2025</small>
                    </div>
                    <p>"Tempatnya sangat bersih dan staf ramah. Lokasi strategis dekat pantai."</p>
                </div>
            </div>
        </div>

        <aside class="booking-sidebar">
            <div class="booking-card">
                <div class="price-header">
                    <span>Harga per malam</span>
                    <span class="big-price">Rp 1.200.000</span>
                </div>

                <form action="#" method="POST">

                    <div class="input-group-detail">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control-detail" placeholder="Nama Pemesan" required>
                    </div>

                    <div class="input-group-detail">
                        <label>Nomor HP / WhatsApp</label>
                        <input type="number" name="hp" class="form-control-detail" placeholder="08..." required>
                    </div>

                    <div class="input-group-detail">
                        <label>Check-in</label>
                        <input type="date" name="checkin" class="form-control-detail">
                    </div>

                    <div class="input-group-detail">
                        <label>Check-out</label>
                        <input type="date" name="checkout" class="form-control-detail">
                    </div>

                    <button type="button" class="btn-confirm">Lanjut ke Pembayaran</button>

                    <p class="note-text">Anda belum akan dikenakan biaya.</p>
                </form>

            </div>
        </aside>

    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Ivory Palace. Semua hak dilindungi.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>

</html>