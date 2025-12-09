<?php

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

// Pastikan data dari controller tersedia
if (!isset($data)) {
    header('Location: ../../controller/user/IndexController.php');
    exit();
}

$roomTypes = $data['roomTypes'] ?? [];

// Mapping foto fallback
$imageMapping = [
    'std.jpg' => 'hemat.jpg',
    'dlx.jpg' => 'luas.jpg',
    'suite.jpg' => 'fam.jpg'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Ivory Palace</title>
    <link rel="stylesheet" href="../../asset/css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-right">
            <a href="../../controller/user/IndexController.php" class="brand">Ivory Palace</a>
        </div>
       
        <div class="nav-center">
            <a href="../../controller/user/IndexController.php">Home</a>
            <a href="#rooms" class="nav-booking">Booking</a>
            <a href="../../controller/user/ProfileController.php">Profile</a>
        </div>

        <div class="nav-left">
            <span class="username">
                Halo, <?php echo htmlspecialchars($data['username']); ?>
                (<?php echo htmlspecialchars($data['role']); ?>)
            </span>
            <a href="../../controller/user/IndexController.php?logout=true" class="logout">Logout</a>
        </div>
    </nav>

    <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../../asset/image/pict1.jpg" class="d-block w-100" alt="Ivory Palace Hotel">
            </div>
            <div class="carousel-item">
                <img src="../../asset/image/pict2.jpg" class="d-block w-100" alt="Luxury Room">
            </div>
            <div class="carousel-item">
                <img src="../../asset/image/pict3.jpg" class="d-block w-100" alt="Hotel Facilities">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="booking-wrapper">
        <div class="booking-form">
            <div class="date-group">
                <label>Check-in</label>
                <input type="date" 
                       name="checkin" 
                       class="date-input"
                       id="checkinFilter"
                       min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="vertical-line"></div>

            <div class="date-group">
                <label>Check-out</label>
                <input type="date" 
                       name="checkout" 
                       class="date-input"
                       id="checkoutFilter"
                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <button type="button" class="btn-cari" onclick="filterRooms()">Cari Ketersediaan</button>
        </div>
    </div>

    <div class="main-content">
        
        <aside class="sidebar-info">
            <h3>Kenapa Ivory Palace?</h3>
            
            <ul class="benefit-list">
                <li>✓ Konfirmasi Instan</li>
                <li>✓ Layanan 24/7</li>
                <li>✓ Jaminan Harga Termurah</li>
                <li>✓ Pembayaran Aman</li>
            </ul>

            <div class="promo-box">
                <h4>Diskon Spesial!</h4>
                <p>Gunakan kode <strong>IVORY25</strong> untuk diskon 10%.</p>
            </div>
        </aside>

        <section class="hotel-grid-wrapper" id="rooms">
            <h2 class="section-title">Pilihan Kamar Tersedia</h2>
            
            <?php if (empty($roomTypes)): ?>
                <div class="alert alert-warning">
                    <strong>Maaf!</strong> Saat ini tidak ada tipe kamar yang tersedia.
                </div>
            <?php else: ?>
            
            <div class="hotel-grid">
                <?php foreach ($roomTypes as $room): 
                    // Get image dengan fallback mapping
                    $foto = $room['foto'] ?? 'col.jpg';
                    if (isset($imageMapping[$foto])) {
                        $foto = $imageMapping[$foto];
                    }
                    $imagePath = "../../asset/image/" . htmlspecialchars($foto);
                ?>
                    <div class="hotel-card" data-available="<?php echo $room['kamar_tersedia']; ?>">
                        <div class="card-img-wrap">
                            <img src="<?php echo $imagePath; ?>" 
                                 alt="<?php echo htmlspecialchars($room['nama_tipe']); ?>"
                                 onerror="this.src='../../asset/image/col.jpg'">
                            <?php if ($room['kamar_tersedia'] > 0): ?>
                                <span class="badge-available"><?php echo $room['kamar_tersedia']; ?> kamar tersedia</span>
                            <?php else: ?>
                                <span class="badge-unavailable">Penuh</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h4 class="hotel-name"><?php echo htmlspecialchars($room['nama_tipe']); ?></h4>
                            <p class="room-desc"><?php echo htmlspecialchars(substr($room['deskripsi'], 0, 50)); ?>...</p>
                            <p class="room-capacity">
                                <small>👥 Kapasitas: <?php echo $room['kapasitas']; ?> orang</small>
                            </p>
                            <div class="card-bottom">
                                <div class="price-info">
                                    <span class="price">Rp <?php echo number_format($room['harga_per_malam'], 0, ',', '.'); ?></span>
                                    <small class="per-night">/malam</small>
                                </div>
                                <?php if ($room['kamar_tersedia'] > 0): ?>
                                    <a href="../../controller/user/BookingController.php?id_tipe=<?php echo $room['id']; ?>" 
                                       class="btn-book">Booking</a>
                                <?php else: ?>
                                    <button class="btn-book" disabled style="opacity: 0.5; cursor: not-allowed;">
                                        Tidak Tersedia
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>
        </section>

    </div>

    <footer>
        <p>&copy; <?php echo $data['tahunSekarang']; ?> Ivory Palace. Semua hak dilindungi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>

    <script>
        // Filter rooms berdasarkan ketersediaan (optional feature)
        function filterRooms() {
            const checkin = document.getElementById('checkinFilter').value;
            const checkout = document.getElementById('checkoutFilter').value;
            
            if (!checkin || !checkout) {
                alert('Silakan pilih tanggal check-in dan check-out');
                return;
            }
            
            if (new Date(checkout) <= new Date(checkin)) {
                alert('Tanggal check-out harus lebih dari check-in');
                return;
            }
            
            // Optional: Bisa ditambahkan AJAX untuk filter real-time
            // Untuk saat ini, hanya tampilkan semua kamar tersedia
            const cards = document.querySelectorAll('.hotel-card');
            cards.forEach(card => {
                const available = parseInt(card.getAttribute('data-available'));
                if (available > 0) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Update minimum checkout date when checkin changes
        document.getElementById('checkinFilter')?.addEventListener('change', function() {
            const checkin = new Date(this.value);
            checkin.setDate(checkin.getDate() + 1);
            document.getElementById('checkoutFilter').min = checkin.toISOString().split('T')[0];
        });

        // Smooth scroll untuk link booking
        document.addEventListener('DOMContentLoaded', function() {
            const bookingLink = document.querySelector('.nav-booking');
            if (bookingLink) {
                bookingLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const roomsSection = document.getElementById('rooms');
                    if (roomsSection) {
                        roomsSection.scrollIntoView({ 
                            behavior: 'smooth' 
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>