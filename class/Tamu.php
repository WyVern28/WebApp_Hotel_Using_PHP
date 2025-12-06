<?php
require_once 'Database.php';

class Tamu extends Database {
    public function getAllTamu() {
        try {
            $query = $this->db->prepare("SELECT * FROM tamu ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all tamu error: " . $e->getMessage());
            return [];
        }
    }
}
?>