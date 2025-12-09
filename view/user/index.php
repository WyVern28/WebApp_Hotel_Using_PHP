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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../asset/css/user.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    </head>
<body>
    <nav class="navbar" role="navigation" aria-label="Main navigation" >
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

    <div id="carouselExampleCaptions" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../../asset/image/pict1.jpg" class="d-block w-100" alt="...">
     
    </div>
    <div class="carousel-item">
      <img src="../../asset/image/pict2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../../asset/image/pict3.jpg" class="d-block w-100" alt="...">
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
    <form action="index.php" method="GET" class="booking-form">
        
        <div class="date-group">
            <label>Check-in</label>
            <input type="date" 
                   name="checkin" 
                   class="date-input"
                   min="<?php echo date('Y-m-d'); ?>" 
                   required>
        </div>

        <div class="vertical-line"></div>

        <div class="date-group">
            <label>Check-out</label>
            <input type="date" 
                   name="checkout" 
                   class="date-input"
                   min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" 
                   required>
        </div>

        <button type="submit" class="btn-cari">Cari Ketersediaan</button>

    </form>
</div>

<div class="main-content">
    
    <aside class="sidebar-info">
        <h3>Kenapa Ivory Palace?</h3>
        
        <ul class="benefit-list">
            <li>Konfirmasi Instan</li>
            <li>Layanan 24/7</li>
            <li>Jaminan Harga Termurah</li>
            <li>Pembayaran Aman</li>
        </ul>

        <div class="promo-box">
            <h4>Diskon Spesial!</h4>
            <p>Gunakan kode <strong>IVORY25</strong> untuk diskon 10%.</p>
        </div>
    </aside>

    <section class="hotel-grid-wrapper">
        <h2 class="section-title">Rekomendasi Pilihan</h2>
        
        <div class="hotel-grid">
            <?php
            $hotels = [
                ["nama" => "Standard Room", "harga" => "Rp 300.000", "img" => "../../asset/image/hemat.jpg"],
                ["nama" => "Deluxe Room", "harga" => "Rp 500.000", "img" => "../../asset/image/luas.jpg"],
                ["nama" => "Family Suite", "harga" => "Rp 1.200.000", "img" => "../../asset/image/fam.jpg"],
                ["nama" => "Standard Room", "harga" => "Rp 300.000", "img" => "../../asset/image/hemat.jpg"],
                ["nama" => "Deluxe Room", "harga" => "Rp 500.000", "img" => "../../asset/image/luas.jpg"],
                ["nama" => "Family Suite", "harga" => "Rp 1.200.000", "img" => "../../asset/image/fam.jpg"]
            ];

            foreach ($hotels as $hotel) {
            ?>
                <div class="hotel-card">
                    <div class="card-img-wrap">
                        <img src="<?php echo $hotel['img']; ?>" alt="Hotel Image">
                    </div>
                    <div class="card-content">
                        <h4 class="hotel-name"><?php echo $hotel['nama']; ?></h4>
                        <div class="card-bottom">
                            <span class="price"><?php echo $hotel['harga']; ?></span>
                            <a href="booking.php" class="btn-book">Pilih</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

</div>

  <footer>
        <p>&copy; <?php echo date('Y'); ?> Ivory Palace. Semua hak dilindungi.</p>
    </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>