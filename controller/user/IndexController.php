<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu'){
        header('location:../../view/login.php');
        exit();
    }
    if(isset($_GET['logout'])){
        session_destroy();
        header('location:../../view/login.php');
        exit();
    }
    require_once '../../class/TipeKamar.php';
    $tipeKamar = new TipeKamar();
    $roomTypes = $tipeKamar->getTipeKamarWithAvailability();
    $data = ['username' => $_SESSION['username'], 'role' => $_SESSION['role'], 'roomTypes' => $roomTypes, 'tahunSekarang' => date('Y')];
    include '../../view/user/index.php';
?>