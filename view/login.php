<?php

session_start();

require_once '../class/Auth.php';

$error = '';
$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];


    $auth = new Auth();

    $user = $auth->login($username, $password);

    // ini kalo loginnya berhasil
    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        // login berdasarkan role
        if ($user['role'] == 'admin') {
            header('Location: ../controller/admin/adminPage.php');
        } elseif ($user['role'] == 'kasir') {
            header('Location: ../controller/kasir/DashboardController.php');
        } else {
            header('Location: user/index.php');
        }
        exit();
    } else {
        $error = 'Username atau password salah!';
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
