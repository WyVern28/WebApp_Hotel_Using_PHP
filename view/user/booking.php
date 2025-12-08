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
            <a href="index.php">Booking</a>
            <a href="profile.php">Profile</a>
        </div>

        <div class="nav-right">
            <a href="index.php" class="brand">Aplikasi Saya</a>
        </div>
    </nav>

      <!-- Main content -->
    <main>
        <div class="card-container">

        <div class="hero-header">
    <div class="hero-content">
        <h1>Find the perfect hotel on Ivory Palace</h1>
        <p>From cheap hotels to luxury rooms and everything in between</p>
    </div>

  <div class="search-section">
    <div class="search-container">
        <form action="" method="GET" class="search-form">
            
            <div class="input-group location-box">
                <span class="icon">🛏️</span>
                <input type="text" name="location" placeholder="Mau ke mana?" class="form-control">
            </div>

            <div class="input-group date-box">
    <span class="icon">📅</span>
    <div class="date-inputs">
        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Check-in" class="form-control date-native">
        
        <span class="separator">—</span>
        
        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Check-out" class="form-control date-native">
    </div>
</div>

            <div class="input-group guest-box" style="position: relative;">
                <span class="icon">👤</span>
                <input type="text" id="guest-display" value="2 Dewasa · 0 Anak · 1 Kamar" class="form-control" readonly style="cursor: pointer;">
                
                <div id="guest-popup" class="guest-popup">
                    <div class="guest-row">
                        <span>Dewasa</span>
                        <div class="counter">
                            <button type="button" onclick="updateGuest('adult', -1)">-</button>
                            <span id="qty-adult">2</span>
                            <button type="button" onclick="updateGuest('adult', 1)">+</button>
                        </div>
                    </div>
                    <div class="guest-row">
                        <span>Anak</span>
                        <div class="counter">
                            <button type="button" onclick="updateGuest('child', -1)">-</button>
                            <span id="qty-child">0</span>
                            <button type="button" onclick="updateGuest('child', 1)">+</button>
                        </div>
                    </div>
                    <div class="guest-row">
                        <span>Kamar</span>
                        <div class="counter">
                            <button type="button" onclick="updateGuest('room', -1)">-</button>
                            <span id="qty-room">1</span>
                            <button type="button" onclick="updateGuest('room', 1)">+</button>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <button type="button" id="btn-selesai">Selesai</button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-search">Search</button>
            
        </form>
    </div>
</div>

<script>
    const guestInput = document.getElementById('guest-display');
    const guestPopup = document.getElementById('guest-popup');
    const btnSelesai = document.getElementById('btn-selesai');
    let dataTamu = { adult: 2, child: 0, room: 1 };

    guestInput.addEventListener('click', function(e) {
        guestPopup.style.display = (guestPopup.style.display === 'block') ? 'none' : 'block';
        e.stopPropagation();
    });

    btnSelesai.addEventListener('click', function() { guestPopup.style.display = 'none'; });

    window.addEventListener('click', function(e) {
        if (!guestInput.contains(e.target) && !guestPopup.contains(e.target)) {
            guestPopup.style.display = 'none';
        }
    });

    function updateGuest(tipe, ubah) {
        if (tipe === 'adult' && dataTamu.adult + ubah < 1) return;
        if (tipe === 'room' && dataTamu.room + ubah < 1) return;
        if (tipe === 'child' && dataTamu.child + ubah < 0) return;
        
        dataTamu[tipe] += ubah;
        document.getElementById('qty-' + tipe).innerText = dataTamu[tipe];
        guestInput.value = `${dataTamu.adult} Dewasa · ${dataTamu.child} Anak · ${dataTamu.room} Kamar`;
    }
</script>

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
