<?php
require_once 'Database.php';

class Kamar extends Database {
    public function getAllKamar() {
        try {
            $query = $this->db->prepare("SELECT * FROM kamar ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all kamar error: " . $e->getMessage());
            return [];
        }
    }
}
?>