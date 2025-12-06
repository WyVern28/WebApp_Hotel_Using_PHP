<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
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
require_once '../../class/Tamu.php';
require_once '../../class/Kamar.php';

$date = date('Y-m-d');
$booking = new Booking();
$kasir = new Kasir();
$tamu = new Tamu();
$kamar = new Kamar();

$data = [
    'username' => $_SESSION['username'],
    'booking' => $booking->getAllBookings(),
    'rooms' => $booking->getOccupiedRooms(),
    'sisa'=>$booking->getAvailableRooms(),
    'bookingToday' => $booking->getBookingsByDate($date),
    'allKasir' => $kasir->getAllKasir(),
    'allTamu' => $tamu->getAllTamu(),
    'allKamar' => $kamar->getAllKamar()
];

include '../../view/admin/tempDashboard.php';
?>