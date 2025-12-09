<?php
require_once 'Database.php';

class Diskon extends Database {
    
    public function getAllDiskon() {
        try {
            $query = $this->db->prepare("SELECT * FROM diskon ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            error_log("Get all diskon error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getDiskonByCode($kode_promo) {
        try {
            $query = $this->db->prepare(
                "SELECT * FROM diskon 
                 WHERE kode_promo = :kode_promo 
                 AND status_aktif = 1"
            );
            $query->bindParam(":kode_promo", $kode_promo);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            error_log("Get diskon by code error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getDiskonById($id) {
        try {
            $query = $this->db->prepare("SELECT * FROM diskon WHERE id = :id");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            error_log("Get diskon by id error: " . $e->getMessage());
            return false;
        }
    }
}
?>