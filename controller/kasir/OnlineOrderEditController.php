<?php
session_start();

// Proteksi akses
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
require_once '../../class/Kamar.php';

$booking = new Booking();
$kamar = new Kamar();
$message = "";
$message_type = "";

// Ambil ID booking dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: OnlineOrderController.php');
    exit();
}

$id_booking = $_GET['id'];

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_booking'])) {
    $id_kamar = $_POST['id_kamar'];
    $tgl_check_in = $_POST['tgl_check_in'];
    $tgl_check_out = $_POST['tgl_check_out'];
    $total_harga = $_POST['total_harga'];

    if (empty($id_kamar) || empty($tgl_check_in) || empty($tgl_check_out) || empty($total_harga)) {
        $message = "Gagal: Semua field harus diisi!";
        $message_type = "danger";
    } else {
        $bookingData = [
            'id_kamar' => $id_kamar,
            'tgl_check_in' => $tgl_check_in,
            'tgl_check_out' => $tgl_check_out,
            'total_harga' => $total_harga
        ];

        if ($booking->updateBooking($id_booking, $bookingData)) {
            header("Location: OnlineOrderController.php?msg=updated");
            exit();
        } else {
            $message = "Gagal Update: Terjadi kesalahan sistem database.";
            $message_type = "danger";
        }
    }
}

// Ambil data booking
$bookingData = $booking->getBookingById($id_booking);
if (!$bookingData) {
    header('Location: OnlineOrderController.php');
    exit();
}

// Ambil semua kamar tersedia + kamar yang sedang digunakan booking ini
$availableRooms = $booking->getAvailableRooms();

$data = [
    'username' => $_SESSION['username'],
    'bookingData' => $bookingData,
    'availableRooms' => $availableRooms,
    'message' => $message,
    'message_type' => $message_type
];

include '../../view/kasir/OnlineOrderEdit.php';
?>