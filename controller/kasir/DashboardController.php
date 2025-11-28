<?php
/**
 * Dashboard Controller untuk Kasir
 * Memisahkan Logic dari UI
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

$booking = new Booking();

$data = [
    'username' => $_SESSION['username'],
    'currentDateTime' => date('d/m/Y H:i:s'),
    'totalBookingToday' => count($booking->getBookingsByDate(date('Y-m-d'))),
    'totalBookingAll' => count($booking->getAllBookings()),
    'availableRooms' => count($booking->getAvailableRooms()),
    'recentBookings' => array_slice($booking->getAllBookings(), 0, 5)
];
include '../../view/kasir/kasirPage.php';
?>
