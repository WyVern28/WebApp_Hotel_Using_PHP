<?php
session_start();
include '../config/koneksi.php';

$error = '';
$success = '';

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if ($password === $user['password'] || password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                header('Location: admin/adminPage.php');
            } elseif ($user['role'] == 'kasir') {
                header('Location: kasir/kasirPage.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <p>
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" required>
        </p>

        <p>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
        </p>

        <button type="index.php">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>
