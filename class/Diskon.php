<?php
require_once 'Database.php';

class Diskon extends Database {

    public function getAllDiskons() {
        try {
            $query = $this->db->prepare("SELECT * FROM diskon ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all diskons error: " . $e->getMessage());
            return [];
        }
    }
}
?>