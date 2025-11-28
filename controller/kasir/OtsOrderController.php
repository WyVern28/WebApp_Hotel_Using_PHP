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
$message = '';
$message_type = '';

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

        elseif ($_POST['action'] === 'create') {
            $nama = trim($_POST['nama']);
            $no_ktp = trim($_POST['no_ktp']);
            $no_telp = trim($_POST['no_telp']);
            $alamat = trim($_POST['alamat']);
            $id_kamar = (int)$_POST['id_kamar'];
            $tanggal_checkin = $_POST['tanggal_checkin'];
            $tanggal_checkout = $_POST['tanggal_checkout'];
            $metode_bayar = $_POST['metode_bayar'];
            $total_harga = (float)$_POST['total_harga'];
            $tamuData = [
                'nama_lengkap' => $nama,
                'no_ktp' => $no_ktp,
                'no_hp' => $no_telp
            ];

            $bookingData = [
                'id_kamar' => $id_kamar,
                'tgl_check_in' => $tanggal_checkin,
                'tgl_check_out' => $tanggal_checkout,
                'total_harga' => $total_harga
            ];

            $pembayaranData = [
                'metode_bayar' => $metode_bayar
            ];
            $result = $booking->createBooking($tamuData, $bookingData, $pembayaranData);

            if ($result) {
                $message = "Booking berhasil dibuat!";
                $message_type = "success";
            } else {
                $message = "Gagal membuat booking!";
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
    'availableRooms' => $booking->getAvailableRooms(),
    'todayOrders' => $booking->getBookingsByDate(date('Y-m-d'))
];
include '../../view/kasir/otsOrder.php';
?>
