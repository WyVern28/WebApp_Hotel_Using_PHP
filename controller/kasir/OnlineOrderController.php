<?php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: ../../view/login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../view/login.php');
    exit();
}

require_once '../../class/Booking.php';
require_once '../../class/Kasir.php';

$booking = new Booking();
$kasirClass = new Kasir();

// ⭐ Inisialisasi variabel message
$message = "";
$message_type = "";

// Ambil data kasir dari database
$kasirData = $kasirClass->getKasirByUsername($_SESSION['username']);

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = "Data booking berhasil diperbarui!";
    $message_type = "success";
}

// Proses POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Delete booking
    if ($action === 'delete' && isset($_POST['id_booking'])) {
        $id_booking = (int)$_POST['id_booking'];

        if ($booking->deleteBooking($id_booking)) {
            $message = "Booking berhasil dihapus!";
            $message_type = "success";
        } else {
            $message = "Gagal menghapus booking!";
            $message_type = "error";
        }
    }
    
    // Konfirmasi pembayaran
    if ($action === 'konfirmasi_bayar' && isset($_POST['id_booking'])) {
        $id_booking = (int)$_POST['id_booking'];
        $metode_bayar = $_POST['metode_bayar'];
        
        if ($booking->konfirmasiBayar($id_booking, $metode_bayar)) {
            $message = "Pembayaran berhasil dikonfirmasi!";
            $message_type = "success";
        } else {
            $message = "Gagal konfirmasi pembayaran!";
            $message_type = "error";
        }
    }
}

// Load data untuk view
$data = [
    'username' => $_SESSION['username'],
    'id_kasir' => $kasirData['id_kasir'] ?? 'N/A',
    'nama_kasir' => $kasirData['nama'] ?? $_SESSION['username'],
    'message' => $message,
    'message_type' => $message_type,
    'onlineOrders' => $booking->getAllBookings(),
    'pendingBookings' => $booking->getBookingsByStatus('pending')
];

include '../../view/kasir/OnlineOrder.php';
?>
