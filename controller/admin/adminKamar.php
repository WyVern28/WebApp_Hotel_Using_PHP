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

require_once '../../class/Kamar.php';
require_once '../../class/Fasilitas.php';

$kamar = new Kamar();
$fasilitas = new Fasilitas();
$pesan = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_tipe'])) {
   
    $fotoName = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $targetDir = "../../asset/image/";
        $fileName = basename($_FILES['foto']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $newFileName = "tipe_" . uniqid() . "." . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileType, $allowed)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                $fotoName = $newFileName; 
            } else {
                $pesan = "Gagal upload gambar.";
            }
        } else {
            $pesan = "Format gambar harus JPG, PNG, atau WEBP.";
        }
    }

    $nama       = $_POST['nama_tipe'];
    $harga      = $_POST['harga'];
    $sarapan    = $_POST['harga_sarapan'];
    $kapasitas  = $_POST['kapasitas'];
    $deskripsi  = $_POST['deskripsi'];
    $fasilitas_ids = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : [];

    if (empty($pesan)) { 
        if ($kamar->addTipeKamar($nama, $harga, $sarapan, $kapasitas, $deskripsi, $fotoName, $fasilitas_ids)) {
            header("Location: adminKamar.php?msg=success_tipe");
            exit();
        } else {
            $pesan = "Gagal menyimpan data ke database.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_fasilitas'])) {
    $foto = "default_icon.png";
    
    if ($fasilitas->addFasilitas($_POST['nama_fasilitas'], $foto)) {
        header("Location: adminKamar.php?msg=success_fasilitas");
        exit();
    } else {
        $pesan = "Gagal menambah fasilitas.";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_kamar'])) {
    $res = $kamar->addKamar($_POST['nomor_kamar'], $_POST['id_tipe'], $_POST['lantai']);
    if ($res == 1) header("Location: adminKamar.php?msg=success_kamar");
    elseif ($res == -1) $pesan = "Gagal! Nomor kamar sudah ada.";
    else $pesan = "Gagal menambah kamar.";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_kamar'])) {
    $res = $kamar->updateKamar($_POST['id'], $_POST['nomor_kamar'], $_POST['id_tipe'], $_POST['lantai'], $_POST['status']);
    if ($res == 1) {
        header("Location: adminKamar.php?msg=updated");
        exit();
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    if ($kamar->deleteKamar($_GET['id']) == 1){
        header("Location: adminKamar.php?msg=deleted");
    } else{
        $pesan = "Gagal Hapus! Ada riwayat transaksi.";  
    }
}


if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'success_tipe') $pesan = "Tipe Kamar berhasil ditambahkan!";
    if ($_GET['msg'] == 'success_fasilitas') $pesan = "Fasilitas berhasil ditambahkan!";
    if ($_GET['msg'] == 'success_kamar') $pesan = "Kamar berhasil ditambahkan!";
    if ($_GET['msg'] == 'updated') $pesan = "Data berhasil diupdate!";
}


if (isset($_GET['view']) && $_GET['view'] == 'tambah_fasilitas') {
    include __DIR__ . '/../../view/admin/adminTambahFasilitas.php';
    exit();
}


if (isset($_GET['view']) && $_GET['view'] == 'tambah_tk') {
    $listFasilitas = $kamar->getAllFasilitas(); 
    include __DIR__ . '/../../view/admin/adminTambahTk.php';
    exit();
}


if (isset($_POST['trigger_edit'])) {
    $dataEdit = $kamar->getKamarById($_POST['id']);
    $listTipe = $kamar->getAllTipeKamar();
    include __DIR__ . '/../../view/admin/adminKamarEdit.php';
    exit();
}


$search = isset($_GET['q']) ? $_GET['q'] : null;
$data = [
    'username' => $_SESSION['username'],
    'allKamar' => $kamar->getAllKamar($search),
    'listTipe' => $kamar->getAllTipeKamar(),
    'search' => $search,
    'pesan' => $pesan
];

include '../../view/admin/adminKamar.php';
?>