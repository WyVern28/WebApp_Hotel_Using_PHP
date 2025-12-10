<?php
session_start();

// Proteksi akses
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: ../../view/login.php');
    exit();
}

require_once '../../class/Booking.php';
require_once '../../class/Pembayaran.php';

$bookingClass = new Booking();
$message = '';
$message_type = '';

// Konfirmasi Pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id_booking = (int)$_POST['id_booking'];
    
    if ($action === 'konfirmasi_bayar') {
        $metode_bayar = $_POST['metode_bayar'];
        $result = $bookingClass->konfirmasiBayar($id_booking, $metode_bayar);
        
        if ($result) {
            $message = "Pembayaran berhasil dikonfirmasi!";
            $message_type = "success";
        } else {
            $message = "Gagal konfirmasi pembayaran!";
            $message_type = "error";
        }
    }
}

// Load booking pending
$pendingBookings = $bookingClass->getBookingsByStatus('pending');

$data = [
    'username' => $_SESSION['username'],
    'pendingBookings' => $pendingBookings,
    'message' => $message,
    'message_type' => $message_type
];

include '../../view/kasir/OnlineOrder.php';
?>