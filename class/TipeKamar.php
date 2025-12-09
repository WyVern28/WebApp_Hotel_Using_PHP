<?php
require_once 'Database.php';
class TipeKamar extends Database {

    public function getAllTipeKamar() {
        try {
            $query = $this->db->prepare("SELECT * FROM tipe_kamar ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all tipe_kamar error: " . $e->getMessage());
            return [];
        }
    }
}
?>