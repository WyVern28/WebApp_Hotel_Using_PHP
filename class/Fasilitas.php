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
}
?>