<?php
session_start();

require_once '../class/Auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $telp = trim($_POST['telp']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ini buat validasi
    if (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $auth = new Auth();

        // ngecek username udah ada apa belum
        if ($auth->usernameExists($username)) {
            $error = 'Username sudah digunakan!';
        } else {
            if ($auth->register($username, $name, $telp, $password, 'tamu')) {
                $_SESSION['success'] = 'REGISTRASI BERHASIL! Silahkan login.';
                header('Location: login.php');
                exit();
            } else {
                $error = 'Registrasi gagal! Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hotel</title>
</head>
<body>
    <h2>Register</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <p>
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" required>
        </p>

        <p>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" required>
        </p>

        <p>
            <label for="telp">Telp Number</label><br>
            <input type="text" id="telp" name="telp" required>
        </p>

        <p>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required minlength="6">
        </p>

        <p>
            <label for="confirm_password">Konfirmasi Password</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </p>

        <button type="submit" href="register.php">Register</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>
