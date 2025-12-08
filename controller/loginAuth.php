<?php
session_start();
require_once '../class/Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $auth = new Auth();
    $result = $auth->login($username, $password);
    
    if ($result['success']) {
        $_SESSION['username'] = $result['data']['username'];
        $_SESSION['role'] = $result['data']['role'];
        
        if ($result['data']['role'] === 'admin') {
            header('Location: ../view/admin/dashboard.php');
        } elseif ($result['data']['role'] === 'kasir') {
            header('Location: ../controller/kasir/DashboardController.php');
        } else {
            header('Location: ../view/user/home.php');
        }
        exit();
        
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: ../view/login.php');
        exit();
    }
}
?>