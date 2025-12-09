<?php
require_once 'Database.php';

class Tamu extends Database {
    public function getAllTamu($search = null) {
        $sql = "SELECT * FROM tamu";
        if ($search) {
            $sql .= " WHERE username LIKE :keyword OR nama_lengkap LIKE :keyword OR id LIKE :keyword";
        }
        
        $sql .= " ORDER BY id DESC";
        $query = $this->db->prepare($sql);
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->bindParam(':keyword', $searchTerm);
        }
        $query->execute();
        return $query->fetchAll();
    }

    public function getTamuById($id_tamu) {
        $query = $this->db->prepare("SELECT * FROM tamu WHERE id = :id_tamu");
        $query->bindParam(':id_tamu', $id_tamu);
        $query->execute();
        return $query->fetch();
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
            $query = $this->db->prepare("UPDATE tamu SET nama_lengkap = :nama_lengkap,no_ktp = :no_ktp, no_hp = :no_hp WHERE username = :username");
            $query ->bindParam(":nama_lengkap", $data['nama_lengkap']);
            $query ->bindParam(":no_ktp", $data['no_ktp']);
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

    public function addAkunTamu($username, $name, $hp){
        try {
            $cekTamu = $this->db->prepare("SELECT username FROM tamu WHERE username = :u");
            $cekTamu->bindParam(':u', $username);
            $cekTamu->execute();
            if ($cekTamu->rowCount() > 0) {
                return false;
            }

            $this->db->beginTransaction();

            $password = password_hash($username, PASSWORD_DEFAULT);
            $qUser = $this->db->prepare("INSERT INTO user (username, password, role) VALUES (:u, :p, 'tamu')");
            $qUser->bindParam(':u', $username);
            $qUser->bindParam(':p', $password);
            $qUser->execute();

            $qTamu = $this->db->prepare("INSERT INTO tamu (username, nama_lengkap, no_hp, status) VALUES (:u, :n, :hp, 1)");
            $qTamu->bindParam(':u', $username);
            $qTamu->bindParam(':n', $name);
            $qTamu->bindParam(':hp', $hp);
            $qTamu->execute();

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateTamu($id_tamu, $new_username, $nama, $hp, $status, $reset_password) {
        try {
            $this->db->beginTransaction();

            $getUsername = $this->db->prepare("SELECT username FROM tamu WHERE id = :id");
            $getUsername->bindParam(':id', $id_tamu);
            $getUsername->execute();
            $dataLama = $getUsername->fetch();

            if (!$dataLama) {
                $this->db->rollBack();
                return -2;
            }

            $old_username = $dataLama['username'];
            $target_username = $old_username;

            if ($new_username != $old_username) {
                $Cek = $this->db->prepare("SELECT username FROM user WHERE username = :new");
                $Cek->bindParam(':new', $new_username);
                $Cek->execute();
                
                if ($Cek->fetch()) {
                    $this->db->rollBack(); 
                    return -1;
                }

                $updateUser = $this->db->prepare("UPDATE user SET username = :new WHERE username = :old");
                $updateUser->bindParam(':new', $new_username);
                $updateUser->bindParam(':old', $old_username);
                $updateUser->execute();
                $target_username = $new_username;
            }

            $qUpdateKasir = $this->db->prepare("UPDATE tamu SET nama_lengkap = :nama, no_hp = :hp, status = :status WHERE id = :id");
            $qUpdateKasir->bindParam(':nama', $nama);
            $qUpdateKasir->bindParam(':hp', $hp);
            $qUpdateKasir->bindParam(':status', $status);
            $qUpdateKasir->bindParam(':id', $id_tamu);
            $qUpdateKasir->execute();

            //buat reset pake bawaan (1234) yh wil
            if ($reset_password) { 
                $password_default = '1234'; 
                $qReset = $this->db->prepare("UPDATE user SET password = :p WHERE username = :u");
                $qReset->bindParam(':u', $target_username);
                $qReset->bindParam(':p', $password_default);
                $qReset->execute();
            }

            $this->db->commit();
            return 1;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Update Kasir Error: " . $e->getMessage());
            return -3;
        }
    }
}
?>