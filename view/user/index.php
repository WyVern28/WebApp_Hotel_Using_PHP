<?php
session_start();

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
    <title>Document</title>
    <link rel="stylesheet" href="../../asset/css/user.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


    <!-- CSS temporarily removed per request ("tanpa css dlu") -->
</head>
<body>
    <!-- Navbar: username+role di kiri, navigasi di tengah, brand di kanan -->
    <nav class="navbar" role="navigation" aria-label="Main navigation" >
        <div class="nav-left">
            <span class="username">
                Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                (<?php echo htmlspecialchars($_SESSION['role']); ?>)
            </span>
            <a href="index.php?logout=true" class="logout">Logout</a>
        </div>

        <div class="nav-center">
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="profile.php">Profile</a>
        </div>

        <div class="nav-right">
            <a href="index.php" class="brand">Aplikasi Saya</a>
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
      <div class="carousel-caption d-none d-md-block">
        <h5>First slide label</h5>
        <p>Some representative placeholder content for the first slide.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../../asset/image/pict2.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>Some representative placeholder content for the second slide.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../../asset/image/pict3.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Some representative placeholder content for the third slide.</p>
      </div>
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

      <!-- Main content -->
    <main>
        <h1 align="center">Selamat Datang di Aplikasi Saya</h1>
        <div class="card-container">


    <div class="card">
        <img src="../../asset/image/hemat.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">STANDARD ROOM</h5>
            <p class="card-text">Kamar Hemat</p>
            <p class="card-text">Harga : Rp300.000/malam</p>
            <a href="#" class="btn btn-primary">Book Now</a>
        </div>
    </div>

    <div class="card">
        <img src="../../asset/image/luas.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">DELUXE ROOM</h5>
            <p class="card-text">Kamar Luas</p>
            <p class="card-text">Harga : Rp500.000/malam</p>
            <a href="#" class="btn btn-primary">Book Now</a>
        </div>
    </div>

    <div class="card">
        <img src="../../asset/image/fam.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">FAMILY SUITE</h5>
            <p class="card-text">Kamar Keluarga</p>
            <p class="card-text">Harga : Rp1.200.000/malam</p>
            <a href="#" class="btn btn-primary">Book Now</a>
        </div>
    </div>
    
</div>

<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../../asset/image/pict1.jpg" class="d-block w-100" alt="...">
</div>
  </div>
</div>

    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Aplikasi Saya. Semua hak dilindungi.</p>
    </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
