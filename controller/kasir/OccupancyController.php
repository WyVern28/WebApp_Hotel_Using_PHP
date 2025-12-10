<?php
/**
 * Occupancy Controller untuk Kasir
 * Menangani semua logic untuk Occupancy (status kamar yang terisi)
 */

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

$message = '';
$message_type = '';

$kasirData = $kasirClass->getKasirByUsername($_SESSION['username']);

// Proses POST request (Check-in / Check-out)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_in' && isset($_POST['id_booking'])) {
        $id_booking = (int)$_POST['id_booking'];
        if ($booking->checkIn($id_booking)) {
            $message = "Check-in berhasil!";
            $message_type = "success";
        } else {
            $message = "Gagal melakukan check-in!";
            $message_type = "error";
        }
    }
    
    if ($action === 'check_out' && isset($_POST['id_booking'])) {
        $id_booking = (int)$_POST['id_booking'];
        if ($booking->checkOut($id_booking)) {
            $message = "Check-out berhasil! Kamar tersedia kembali.";
            $message_type = "success";
        } else {
            $message = "Gagal melakukan check-out!";
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
    'occupiedRooms' => $booking->getOccupiedRooms(),
    'dibayarBookings' => $booking->getBookingsByStatus('dibayar'),
    'checkinBookings' => $booking->getBookingsByStatus('check_in')
];

include '../../view/kasir/occupancy.php';
?>
