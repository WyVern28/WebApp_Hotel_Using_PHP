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
    public function getAllAkun(){
        try {
            $query = $this->db->prepare("SELECT * FROM tamu where username is not null ORDER BY id DESC");
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all akun error: " . $e->getMessage());
            return [];
        }
    }
    public function getSTamu($status) {
        try {
            $query = $this->db->prepare("SELECT * FROM tamu WHERE status = :status and username is not null ORDER BY id DESC");
            $query->bindParam(":status", $status);
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get tamu by status error: " . $e->getMessage());
            return [];
        }
    }
    public function getProfileByUsername($username){
        try{
            $query = $this->db->prepare("SELECT t.*, u.username, u.dibuat_pada 
                 FROM tamu t
                 JOIN user u ON t.username = u.username
                 WHERE t.username = :username");
            $query->bindParam(":username", $username);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            error_log("Get profile by username error: " . $e->getMessage());
            return false;
        }
    }
    public function updateProfile($username,$data){
        try{
            $query = $this->db->prepare("UPDATE tamu SET nama_lengkap = :nama_lengkap, no_hp = :no_hp WHERE username = :username");
            $query ->bindParam(":nama_lengkap", $data['nama_lengkap']);
            $query ->bindParam(":no_hp", $data['no_hp']);
            $query ->bindParam(":username", $username);
            return $query->execute();

        } catch (PDOException $e) {
            error_log("Update profile error: " . $e->getMessage());
            return false;
        }
    }

    public function getBookingHistory($username){
        try{
            $query = $this->db->prepare("SELECT b.*, k.nomor_kamar, tk.nama_tipe, tk.harga_per_malam, p.jumlah_bayar, p.status_pembayaran, p.metode_bayar FROM booking b JOIN tamu t ON b.id_tamu = t.id JOIN kamar k ON b.id_kamar = k.id JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id LEFT JOIN pembayaran p ON b.id = p.id_booking WHERE t.username = :username ORDER BY b.tgl_check_in DESC");
            $query->bindParam(":username", $username);
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get booking history error: " . $e->getMessage());
            return [];
        }
    }
    public function changePassword($username, $old_password, $new_password) {
        try {
            // Verify old password
            $query = $this->db->prepare("SELECT password FROM user WHERE username = :username");
            $query->bindParam(":username", $username);
            $query->execute();
            $user = $query->fetch();

            if (!$user) {
                return false;
            }

            // Check old password
            if (!password_verify($old_password, $user['password']) && $old_password !== $user['password']) {
                return false;
            }

            // Update with new password
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $updateQuery = $this->db->prepare(
                "UPDATE user SET password = :password WHERE username = :username"
            );
            $updateQuery->bindParam(":password", $hashedPassword);
            $updateQuery->bindParam(":username", $username);
            
            return $updateQuery->execute();

        } catch (PDOException $e) {
            error_log("Change password error: " . $e->getMessage());
            return false;
        }
    }
}
?>