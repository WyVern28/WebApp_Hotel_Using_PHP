<?php
require_once 'Database.php';
class Rating extends Database {

    public function getAllRatings() {
        try {
            $query = $this->db->prepare("SELECT * FROM rating ORDER BY id_rating DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all ratings error: " . $e->getMessage());
            return [];
        }
    }
}
?>