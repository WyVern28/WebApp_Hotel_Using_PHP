<?php
/**
 * Profile Controller untuk User/Tamu
 * Handle profile view, update, dan change password
 */

session_start();

// Check authentication
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: ../../view/login.php');
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../view/login.php');
    exit();
}

require_once '../../class/Tamu.php';

$tamu = new Tamu();
$username = $_SESSION['username'];

$message = '';
$message_type = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Update Profile
    if ($action === 'update_profile') {
        $data = [
            'nama_lengkap' => trim($_POST['nama_lengkap']),
            'no_ktp' => trim($_POST['no_ktp']),
            'no_hp' => trim($_POST['no_hp'])
        ];

        if ($tamu->updateProfile($username, $data)) {
            $message = "Profile berhasil diupdate!";
            $message_type = "success";
        } else {
            $message = "Gagal mengupdate profile!";
            $message_type = "error";
        }
    }

    // Change Password
    elseif ($action === 'change_password') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $message = "Password baru tidak cocok!";
            $message_type = "error";
        } elseif (strlen($new_password) < 4) {
            $message = "Password minimal 4 karakter!";
            $message_type = "error";
        } else {
            if ($tamu->changePassword($username, $old_password, $new_password)) {
                $message = "Password berhasil diubah!";
                $message_type = "success";
            } else {
                $message = "Password lama salah!";
                $message_type = "error";
            }
        }
    }
}

// Get profile data
$profile = $tamu->getProfileByUsername($username);
$bookingHistory = $tamu->getBookingHistory($username);

if (!$profile) {
    die('Profile tidak ditemukan!');
}

// Prepare data for view
$data = [
    'username' => $username,
    'profile' => $profile,
    'bookingHistory' => $bookingHistory,
    'message' => $message,
    'message_type' => $message_type
];

// Load view
include '../../view/user/profile.php';
?>