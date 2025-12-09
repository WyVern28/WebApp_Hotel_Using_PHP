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

$booking = new Booking();
$message = "";
$message_type = "";

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = "Data booking berhasil diperbarui!";
    $message_type = "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete' && isset($_POST['id_booking'])) {
            $id_booking = (int)$_POST['id_booking'];

            if ($booking->deleteBooking($id_booking)) {
                $message = "Booking berhasil dihapus!";
                $message_type = "success";
            } else {
                $message = "Gagal menghapus booking!";
                $message_type = "error";
            }
        }
    }
}
$data = [
    'username' => $_SESSION['username'],
    'id_kasir' => 'KSR001',
    'nama_kasir' => $_SESSION['username'],
    'message' => $message,
    'message_type' => $message_type,

    'onlineOrders' => $booking->getAllBookings()
];

include '../../view/kasir/OnlineOrder.php';
?>
