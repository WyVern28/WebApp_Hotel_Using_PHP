<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'kasir') {
    header('Location: ../../view/login.php');
    exit();
}

require_once '../../class/Booking.php';

if (!isset($_GET['ids']) || empty($_GET['ids'])) {
    die('Tidak ada booking yang dipilih');
}

$ids = explode(',', $_GET['ids']);
$booking = new Booking();
$bookings = [];

foreach ($ids as $id) {
    $bookingData = $booking->getBookingById((int)$id);
    if ($bookingData) {
        $bookings[] = $bookingData;
    }
}

if (empty($bookings)) {
    die('Data booking tidak ditemukan');
}

$data = [
    'bookings' => $bookings,
    'kasir' => $_SESSION['username'],
    'tanggal_cetak' => date('d/m/Y H:i:s')
];

include '../../view/kasir/printStruk.php';
?>