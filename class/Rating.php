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

    public function getRatingsByTipeKamar($id_tipe_kamar) {
        try {
            $query = $this->db->prepare(
                "SELECT r.*, t.nama_lengkap 
                 FROM rating r
                 JOIN tamu t ON r.id_tamu = t.id
                 WHERE r.id_tipe_kamar = :id_tipe_kamar
                 ORDER BY r.dibuat_pada DESC"
            );
            $query->bindParam(":id_tipe_kamar", $id_tipe_kamar, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get ratings by tipe kamar error: " . $e->getMessage());
            return [];
        }
    }
}
?>