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
    public function getTipeKamarById($id){
        try{
            $query = $this->db->prepare("SELECT * FROM tipe_kamar WHERE id = :id");
            $query -> bindParam(":id", $id, PDO::PARAM_INT);
            $query -> execute();
            return $query->fetch();
        }catch(PDOException $e){
            error_log("Get tipe kamar by ID error:". $e->getMessage());
            return false;
        }
    }
    public function getAvailableRoomsCount($id_tipe_kamar){
        try{
            $query = $this->db->prepare("SELECT COUNT(*) as total FROM kamar WHERE id_tipe_kamar = :id_tipe_kamar AND status_kamar = 'tersedia'");
            $query ->bindParam(":id_tipe_kamar", $id_tipe_kamar, PDO::PARAM_INT);
            $query ->execute();
            $result = $query->fetch();
            return $result['total']?? 0;
        }catch(PDOException $e){ 
            error_log("Get available rooms count error:". $e->getMessage());
            return 0;
        }
    }
    public function getTipeKamarWithAvailability(){
        try{
            $query = $this->db->prepare("SELECT tk.*, COUNT(k.id) as total_kamar, SUM(CASE WHEN k.status_kamar = 'tersedia' THEN 1 ELSE 0 END) as kamar_tersedia FROM tipe_kamar tk LEFT JOIN kamar k ON tk.id = k.id_tipe_kamar GROUP BY tk.id ORDER BY tk.harga_per_malam ASC");
            $query ->execute();
            return $query->fetchAll();
        }catch(PDOException $e){
            error_log("Get tipe kamar with availability error:". $e->getMessage());
            return [];
        }
    }
    public function getFasilitasByTipe($id_tipe_kamar) {
        try {
            $query = $this->db->prepare("SELECT f.* FROM fasilitas f JOIN fasilitas_tipe_kamar ftk ON f.id = ftk.id_fasilitas WHERE ftk.id_tipe_kamar = :id_tipe_kamar"
            );
            $query->bindParam(":id_tipe_kamar", $id_tipe_kamar, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get fasilitas error: " . $e->getMessage());
            return [];
        }
    }
    public function getAvailableRoomsByTipe($id_tipe_kamar) {
        try {
            $query = $this->db->prepare("SELECT * FROM kamar WHERE id_tipe_kamar = :id_tipe_kamar AND status_kamar = 'tersedia'ORDER BY nomor_kamar ASC"
            );
            $query->bindParam(":id_tipe_kamar", $id_tipe_kamar, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get available rooms error: " . $e->getMessage());
            return [];
        }
    }
}
?>