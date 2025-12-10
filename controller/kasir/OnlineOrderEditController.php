<?php
session_start();

// Proteksi akses
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: ../../view/login.php');
    exit();
}

require_once '../../class/Booking.php';

$bookingClass = new Booking();
$message = '';
$message_type = '';

// Ambil ID booking dari URL
$id_booking = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_booking) {
    header('Location: ../../controller/kasir/OnlineOrderController.php');
    exit();
}

// Ambil data booking
$bookingData = $bookingClass->getBookingById($id_booking);

if (!$bookingData) {
    die('Data booking tidak ditemukan!');
}

// Proses UPDATE booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $updateData = [
        'id_kamar' => (int)$_POST['id_kamar'],
        'tgl_check_in' => $_POST['tgl_check_in'],
        'tgl_check_out' => $_POST['tgl_check_out'],
        'total_harga' => (float)$_POST['total_harga']
    ];
    
    if ($bookingClass->updateBooking($id_booking, $updateData)) {
        header('Location: ../../controller/kasir/OnlineOrderController.php?msg=updated');
        exit();
    } else {
        $message = "Gagal mengupdate booking!";
        $message_type = "error";
    }
}

// Ambil kamar tersedia
$availableRooms = $bookingClass->getAvailableRooms();

$data = [
    'username' => $_SESSION['username'],
    'bookingData' => $bookingData,
    'availableRooms' => $availableRooms,
    'message' => $message,
    'message_type' => $message_type
];

include '../../view/kasir/OnlineOrderEdit.php';
?>