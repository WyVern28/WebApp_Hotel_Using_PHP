<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-left">
            <span class="username">
                Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                (<?php echo htmlspecialchars($_SESSION['role']); ?>)
            </span>
            <a href="index.php?logout=true" class="logout">Logout</a>
        </div>

        <div class="nav-center">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
        </div>

        <div class="nav-right">
            <a href="index.php" class="brand">Aplikasi Saya</a>
        </div>
    </nav>

    <!-- Main content -->
    <main>
        <h1>Selamat Datang di Aplikasi Saya</h1>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Aplikasi Saya. Semua hak dilindungi.</p>
    </footer>
</body>
</html>
