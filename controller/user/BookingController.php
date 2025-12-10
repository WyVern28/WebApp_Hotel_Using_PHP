<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: ../../view/login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../view/login.php');
    exit();
}

require_once '../../class/TipeKamar.php';
require_once '../../class/Booking.php';
require_once '../../class/Tamu.php';
require_once '../../class/Rating.php';
require_once '../../class/Diskon.php';

$tipeKamarClass = new TipeKamar();
$bookingClass = new Booking();
$tamuClass = new Tamu();
$ratingClass = new Rating();
$diskonClass = new Diskon();

$message = '';
$message_type = '';

// Ambil id_tipe dari URL
$id_tipe = isset($_GET['id_tipe']) ? intval($_GET['id_tipe']) : null;

// Jika tidak ada id_tipe, redirect ke index
if (!$id_tipe) {
    header('Location: IndexController.php');
    exit();
}

// Ambil data tipe kamar
$tipeKamar = $tipeKamarClass->getTipeKamarById($id_tipe);
if (!$tipeKamar) {
    die('Tipe kamar tidak ditemukan.');
}

// Ambil kamar tersedia, fasilitas, dan rating
$availableRooms = $tipeKamarClass->getAvailableRoomsByTipe($id_tipe);
$fasilitas = $tipeKamarClass->getFasilitasByTipe($id_tipe);
$ratings = $ratingClass->getRatingsByTipeKamar($id_tipe);

// Hitung rata-rata rating
$avgRating = 0;
$totalReviews = count($ratings);
if ($totalReviews > 0) {
    $sumRating = array_sum(array_column($ratings, 'rating'));
    $avgRating = round($sumRating / $totalReviews, 1);
}

// Proses booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'booking') {
        $tgl_check_in = $_POST['checkin'];
        $tgl_check_out = $_POST['checkout'];
        $dengan_sarapan = isset($_POST['sarapan']) ? 1 : 0;
        $id_kamar = isset($_POST['id_kamar']) ? (int)$_POST['id_kamar'] : null;
        $preferensi = trim($_POST['preferensi'] ?? '');
        $kode_diskon = strtoupper(trim($_POST['kode_diskon'] ?? ''));
        
        // Validasi tanggal
        $date1 = new DateTime($tgl_check_in);
        $date2 = new DateTime($tgl_check_out);
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        
        if ($date1 < $today) {
            $message = "Tanggal check-in tidak boleh kurang dari hari ini!";
            $message_type = "error";
        } elseif ($date2 <= $date1) {
            $message = "Tanggal check-out harus lebih dari check-in!";
            $message_type = "error";
        } elseif (!$id_kamar) {
            $message = "Silakan pilih nomor kamar!";
            $message_type = "error";
        } else {
            $interval = $date1->diff($date2);
            $jumlah_malam = $interval->days;
            
            // Hitung total harga
            $harga_kamar = $tipeKamar['harga_per_malam'] * $jumlah_malam;
            $harga_sarapan = $dengan_sarapan ? ($tipeKamar['harga_sarapan'] * $jumlah_malam) : 0;
            $total_harga = $harga_kamar + $harga_sarapan;
            
            // Cek diskon jika ada
            $id_diskon = null;
            $diskon_info = '';
            if (!empty($kode_diskon)) {
                $diskon = $diskonClass->getDiskonByCode($kode_diskon);
                if ($diskon && $diskon['status_aktif'] == 1) {
                    $id_diskon = $diskon['id'];
                    $potongan = ($total_harga * $diskon['persentase']) / 100;
                    $total_harga -= $potongan;
                    $diskon_info = " (Diskon {$diskon['persentase']}% - Hemat Rp " . number_format($potongan, 0, ',', '.') . ")";
                } else {
                    $message = "Kode diskon tidak valid atau sudah tidak aktif!";
                    $message_type = "error";
                }
            }
            
            // Jika tidak ada error diskon, lanjutkan booking
            if (empty($message)) {
                // Ambil profile tamu
                $profileTamu = $tamuClass->getProfileByUsername($_SESSION['username']);
                
                if (!$profileTamu) {
                    $message = "Profile tamu tidak ditemukan! Silakan lengkapi profile Anda terlebih dahulu.";
                    $message_type = "error";
                } else {
                    $bookingData = [
                        'id_tamu' => $profileTamu['id'],
                        'id_tipe_kamar' => $id_tipe,
                        'id_kamar' => $id_kamar,
                        'tgl_check_in' => $tgl_check_in,
                        'tgl_check_out' => $tgl_check_out,
                        'dengan_sarapan' => $dengan_sarapan,
                        'preferensi' => $preferensi,
                        'id_diskon' => $id_diskon,
                        'total_harga' => $total_harga
                    ];
                    
                    $result = $bookingClass->createBookingForRegisteredUser($bookingData);
                    
                    if ($result) {
                        $success_msg = "Booking berhasil dibuat! Kode Booking: <strong>{$result}</strong>{$diskon_info}<br>";
                        $success_msg .= "Total Pembayaran: <strong>Rp " . number_format($total_harga, 0, ',', '.') . "</strong><br>";
                        $success_msg .= "Silakan lakukan pembayaran untuk mengkonfirmasi booking Anda.";
                        $_SESSION['success_booking'] = $success_msg;
                        header('Location: ProfileController.php');
                        exit();
                    } else {
                        $message = "Gagal membuat booking! Silakan coba lagi.";
                        $message_type = "error";
                    }
                }
            }
        }
    }
}

// Data untuk view
$data = [
    'username' => $_SESSION['username'],
    'role' => $_SESSION['role'],
    'tipeKamar' => $tipeKamar,
    'availableRooms' => $availableRooms,
    'fasilitas' => $fasilitas,
    'ratings' => $ratings,
    'avgRating' => $avgRating,
    'totalReviews' => $totalReviews,
    'message' => $message,
    'message_type' => $message_type,
    'currentYear' => date('Y')
];

include '../../view/user/booking.php';
?>