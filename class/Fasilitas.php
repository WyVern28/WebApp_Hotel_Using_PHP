<?php
require_once 'Database.php';

class Fasilitas extends Database {

    public function getAllFasilitas() {
        try {
            $query = $this->db->prepare("SELECT * FROM fasilitas ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all fasilitas error: " . $e->getMessage());
            return [];
        }
    }

    public function addFasilitas($nama, $foto) {
        try {
            $q = $this->db->prepare("INSERT INTO fasilitas (nama_fasilitas, foto_fasilitas) VALUES (:n, :f)");
            $q->bindParam(':n', $nama);
            $q->bindParam(':f', $foto);
            return $q->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>