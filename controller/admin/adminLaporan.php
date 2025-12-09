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

require_once '../../class/Laporan.php';
$laporanModel = new Laporan();

$tgl_mulai   = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');
$status      = isset($_GET['status']) ? $_GET['status'] : 'semua';

$dataLaporan = $laporanModel->getLaporan($tgl_mulai, $tgl_selesai, $status);

$totalPendapatan = 0;
foreach ($dataLaporan as $row) {
    $totalPendapatan += $row['total_harga'];
}

$data = [
    'username'    => $_SESSION['username'],
    'laporan'     => $dataLaporan,
    'total'       => $totalPendapatan,
    'tgl_mulai'   => $tgl_mulai,
    'tgl_selesai' => $tgl_selesai,
    'status'      => $status
];

include '../../view/admin/adminLaporan.php';

?>