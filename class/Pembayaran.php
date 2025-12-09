<?php
require_once 'Database.php';
class Pembayaran extends Database {

    public function getAllPembayaran() {
        try {
            $query = $this->db->prepare("SELECT * FROM Pembayaran ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all pembayaran error: " . $e->getMessage());
            return [];
        }
    }
}

?>