<?php
require_once 'Database.php';

class Kamar extends Database {
    public function getAllKamar($search = null) {
        $sql = "SELECT k.*, tk.nama_tipe 
                FROM kamar k 
                JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id";
        
        if ($search) {
            $sql .= " WHERE k.nomor_kamar LIKE :k OR tk.nama_tipe LIKE :k";
        }
        
        $sql .= " ORDER BY k.lantai ASC, k.nomor_kamar ASC";
        $query = $this->db->prepare($sql);
        
        if ($search) {
            $key = "%$search%";
            $query->bindParam(':k', $key);
        }
        
        $query->execute();
        return $query->fetchAll();
    }

    public function getKamarById($id) {
        $query = $this->db->prepare("SELECT * FROM kamar WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch();
    }

    public function getAvailableRooms() {
        $query = $this->db->prepare("SELECT * FROM kamar WHERE status_kamar = 'tersedia' ORDER BY lantai ASC, nomor_kamar ASC");
        $query->execute();
        return $query->fetchAll();
    }

    public function addKamar($no_kamar, $id_tipe, $lantai) {
        try {
            $cek = $this->db->prepare("SELECT id FROM kamar WHERE nomor_kamar = :no");
            $cek->bindParam(':no', $no_kamar);
            $cek->execute();
            if ($cek->rowCount() > 0){
                return -1;
            } 

            $q = $this->db->prepare("INSERT INTO kamar (id_tipe_kamar, nomor_kamar, lantai, status_kamar) VALUES (:tipe, :no, :lantai, 'tersedia')");
            $q->bindParam(':tipe', $id_tipe);
            $q->bindParam(':no', $no_kamar);
            $q->bindParam(':lantai', $lantai);
            return $q->execute() ? 1 : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function updateKamar($id, $no_kamar, $id_tipe, $lantai, $status) {
        try {
            $cek = $this->db->prepare("SELECT id FROM kamar WHERE nomor_kamar = :nomor AND id != :id");
            $cek->bindParam(':nomor', $no_kamar);
            $cek->bindParam(':id', $id);
            $cek->execute();
            if ($cek->rowCount() > 0){
                return -1;
            }

            $q = $this->db->prepare("UPDATE kamar SET id_tipe_kamar=:tipe, nomor_kamar=:nomor, lantai=:lantai, status_kamar=:st WHERE id=:id");
            $q->bindParam(':tipe', $id_tipe);
            $q->bindParam(':nomor', $no_kamar);
            $q->bindParam(':lantai', $lantai);
            $q->bindParam(':st', $status);
            $q->bindParam(':id', $id);
            return $q->execute() ? 1 : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function deleteKamar($id) {
        try {
            $cek = $this->db->prepare("SELECT b.id FROM booking b WHERE b.id_kamar = :id LIMIT 1 and b.status_booking IN ('check_in', 'dibayar')");
            $cek->bindParam(':id', $id);
            $cek->execute();
            if ($cek->rowCount() > 0) {
                return -1;
            }

            $q = $this->db->prepare("DELETE FROM kamar WHERE id = :id");
            $q->bindParam(':id', $id);
            return $q->execute() ? 1 : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getAllFasilitas() {
        $query = $this->db->prepare("SELECT * FROM fasilitas ORDER BY nama_fasilitas ASC");
        $query->execute();
        return $query->fetchAll();
    }

    public function getAllTipeKamar() {
        $query = $this->db->prepare("SELECT * FROM tipe_kamar ORDER BY nama_tipe ASC");
        $query->execute();
        return $query->fetchAll();
    }

    //add tipe kamar
    public function addTipeKamar($nama, $harga, $sarapan, $kapasitas, $desc, $foto, $fasilitas_ids) {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO tipe_kamar (nama_tipe, deskripsi, harga_per_malam, harga_sarapan, kapasitas, foto) 
                    VALUES (:n, :d, :h, :s, :k, :f)";
            $q = $this->db->prepare($sql);
            $q->bindParam(':n', $nama);
            $q->bindParam(':d', $desc);
            $q->bindParam(':h', $harga);
            $q->bindParam(':s', $sarapan);
            $q->bindParam(':k', $kapasitas);
            $q->bindParam(':f', $foto);
            $q->execute();

            $id_tipe = $this->db->lastInsertId();

            if (!empty($fasilitas_ids) && is_array($fasilitas_ids)) {
                $sqlRelasi = "INSERT INTO fasilitas_tipe_kamar (id_tipe_kamar, id_fasilitas) VALUES (:id_tipe, :id_fas)";
                $qRelasi = $this->db->prepare($sqlRelasi);

                foreach ($fasilitas_ids as $id_fasilitas) {
                    $qRelasi->bindParam(':id_tipe', $id_tipe);
                    $qRelasi->bindParam(':id_fas', $id_fasilitas);
                    $qRelasi->execute();
                }
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Add Tipe Kamar Error: " . $e->getMessage());
            return false;
        }
    }
}
?>