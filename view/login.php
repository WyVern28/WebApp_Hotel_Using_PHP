<?php

session_start();

require_once '../class/Auth.php';

$error = '';
$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];


    $auth = new Auth();

    $user = $auth->login($username, $password);

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        if ($user['role'] == 'admin') {
            header('Location: ../controller/admin/adminPage.php');
        } elseif ($user['role'] == 'kasir') {
            header('Location: ../controller/kasir/DashboardController.php');
        } else {
            header('Location: user/index.php');
        }
        exit();
    } else {
        if (! isset($_SESSION['login_error'])) {
            $error = 'Username atau password salah!';
        }else{
            $error = $_SESSION['login_error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel</title>
    <link rel="stylesheet" href="../asset/css/login.css">
</head>
<body>
    <div class="container">
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

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>