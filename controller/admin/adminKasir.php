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
$kasir = new Kasir();
$pesan = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_kasir'])) {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    if ($kasir->addKasir($username, $nama, $password)) {
        $pesan = "Berhasil menambah kasir baru!";
    } else {
        $pesan = "Gagal! Username sudah digunakan.";
    }
}

if(isset($_GET['aksi']) && $_GET['aksi'] == 'status' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status_baru = $_GET['val'];
    $kasir->rubahStatus($id, $status_baru);
    header('Location: adminKasir.php');
    exit();
}

// update kasir dibagian edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_kasir'])) {
    $id_kasir       = $_POST['id_kasir'];
    $username       = trim($_POST['username']); 
    $nama           = trim($_POST['nama']);
    $status         = $_POST['status'];
    $reset_pass     = isset($_POST['reset_password']) ? true : false;

    if(empty($username) || empty($nama)){
        $pesan = "Gagal: Username dan Nama tidak boleh kosong!";
    } else {
        $resultCode = $kasir->updateKasir($id_kasir, $username, $nama, $status, $reset_pass);

        if ($resultCode === 1) {
            header("Location: adminKasir.php?msg=updated");
            exit();
        } elseif ($resultCode === -1) {
            $pesan = "Gagal Update: Username '$username' sudah digunakan user lain.";
        } elseif ($resultCode === -2) {
            $pesan = "Gagal Update: Data kasir tidak ditemukan.";
        } else {
            $pesan = "Gagal Update: Terjadi kesalahan sistem database.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trigger_edit'])) {
    $id = $_POST['id'];
    $dataKasir = $kasir->getKasirById($id);
    include __DIR__ . '/../../view/admin/adminKasirEdit.php';
    exit(); 
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated' && empty($pesan)) {
    $pesan = "Data kasir berhasil diperbarui!";
}

$search = isset($_GET['q']) ? $_GET['q'] : null;

$data = [
    'username' => $_SESSION['username'],
    'allKasir' => $kasir->getAllKasir($search),
    'search' => $search,
    'pesan' => $pesan
];

include '../../view/admin/adminKasir.php';
?>