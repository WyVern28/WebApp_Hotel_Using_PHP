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

require_once '../../class/Kasir.php';
require_once '../../class/Tamu.php';
$kasir = new Kasir();
$tamu = new Tamu();
$pesan = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_akun'])) {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $hp = $_POST['hp'];

    if ($tamu->addAkunTamu($username, $nama, $hp)) {
        $pesan = "Berhasil menambah tamu baru!";
    } else {
        $pesan = "Gagal! Username sudah digunakan.";
    }
}

if(isset($_GET['aksi']) && $_GET['aksi'] == 'status' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status_baru = $_GET['val'];
    $kasirStatus = $kasir->rubahStatus($id, $status_baru);
    header('Location: adminTamu.php');
    exit();
}

// update tamu dibagian edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_tamu'])) {
    $id_tamu       = $_POST['id'];
    $username       = trim($_POST['username']); 
    $nama           = trim($_POST['nama']);
    $hp             = trim($_POST['hp']);
    $status         = $_POST['status'];
    $reset_pass     = isset($_POST['reset_password']) ? true : false;

    if(empty($username) || empty($nama)){
        $pesan = "Gagal: Username dan Nama tidak boleh kosong!";
    } else {
        $resultCode = $tamu->updateTamu($id_tamu, $username, $nama, $hp, $status, $reset_pass);

        if ($resultCode === 1) {
            header("Location: adminTamu.php?msg=updated");
            exit();
        } elseif ($resultCode === -1) {
            $pesan = "Gagal Update: Username '$username' sudah digunakan user lain.";
        } elseif ($resultCode === -2) {
            $pesan = "Gagal Update: Data tamu tidak ditemukan.";
        } else {
            $pesan = "Gagal Update: Terjadi kesalahan sistem database.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trigger_edit'])) {
    $id = $_POST['id'];
    $dataTamu = $tamu->getTamuById($id);
    include __DIR__ .'/../../view/admin/adminTamuEdit.php';
    exit(); 
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated' && empty($pesan)) {
    $pesan = "Data tamu berhasil diperbarui!";
}

$search = isset($_GET['q']) ? $_GET['q'] : null;

$data = [
    'username' => $_SESSION['username'],
    'allKasir' => $kasir->getAllKasir($search),
    'search' => $search,
    'pesan' => $pesan,
    'allTamu' => $tamu->getAllTamu($search),
    'allAkun' => $tamu->getAllAkun(),
    'activeAkun' => $tamu->getSTamu(1),
    'inactiveAkun' => $tamu->getSTamu(0)
];

include '../../view/admin/adminTamu.php';
?>