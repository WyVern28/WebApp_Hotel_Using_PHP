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

$booking = new Booking();

$data = [
    'username' => $_SESSION['username'],
    'id_kasir' => 'KSR001',
    'nama_kasir' => $_SESSION['username'],
    'occupiedRooms' => $booking->getOccupiedRooms()
];

include '../../view/kasir/occupancy.php';
?>
