<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: ../../view/login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../view/login.php');
    exit();
}
require_once '../../class/TipeKamar.php';
require_once '../../class/Booking.php';
require_once '../../class/Tamu.php';
$tipeKamarClass = new TipeKamar();
$bookingClass = new Booking();
$tamuClass = new Tamu();

$message = '';
$message_type = '';
$id_tipe = isset($_GET['id_tipe']) ? intval($_GET['id_tipe']) : null;

if ($id_tipe) {
    header('Location: IndexController.php');
    exit();
}

$tipeKamar = $tipeKamarClass->getTipeKamarWithAvailability();
if(!$tipeKamar){
    die('Tipe kamar tidak ditemukan.');
}
$availableRooms = $tipeKamarClass->getAvailableRoomsByTipe($id_tipe);
$fasilitas = $tipeKamarClass->getFasilitasByTipe($id_tipe);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST['action'] ?? '';
    if($action === 'booking'){
        $nama_lengkap = trim($_POST['nama']);
        $no_hp = trim($_POST['hp']);
        $tgl_check_in = $_POST['checkin'];
        $tgl_check_out = $_POST['checkout'];
        $dengan_sarapan = isset($_POST['sarapan']) ? 1 : 0;
        $id_kamar = isset($_POST['id_kamar']) ? (int)$_POST['id_kamar'] : null;
        $date1 = new DateTime($tgl_check_in);
        $date2 = new DateTime($tgl_check_out);
        $interval = $date1->diff($date2);
        $jumlah_malam = $interval->days;
        $harga_kamar = $tipeKamar['harga_per_malam'] * $jumlah_malam;
        $harga_sarapan = $dengan_sarapan ? ($tipeKamar['harga_sarapan'] * $jumlah_malam) : 0;
        $total_harga = $harga_kamar + $harga_sarapan;
        $profileTamu = $tamuClass->getProfileByUsername($_SESSION['username']);
        if(!$profileTamu){
            $message = "Profile tamu";
            $message_type = "error";
        } else {
            $bookingData = [
                'id_tamu' => $profileTamu['id'],
                'id_tipe_kamar' => $id_tipe,
                'id_kamar' => $id_kamar,
                'tgl_check_in' => $tgl_check_in,
                'tgl_check_out' => $tgl_check_out,
                'dengan_sarapan' => $dengan_sarapan,
                'total_harga' => $total_harga
            ];
            $result = $bookingClass->createBookingForRegisteredUser($bookingData);

            if($result){
                $message = "Booking berhasil dibuat!";
                $message_type = "success";
            } else {
                $message = "Gagal membuat booking!";
                $message_type = "error";
            }
        }
    }
}
$data = [
    'username' => $_SESSION['username'],
    'role' => $_SESSION['role'],
    'tipeKamar' => $tipeKamar,
    'availableRooms' => $availableRooms,
    'fasilitas' => $fasilitas,
    'message' => $message,
    'message_type' => $message_type,
    'currentYear' => date('Y')
];
include '../../view/user/booking.php';
?>