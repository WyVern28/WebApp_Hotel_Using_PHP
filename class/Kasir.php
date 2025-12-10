<?php
require_once 'Database.php';

class Kasir extends Database {
    
    public function getKasirByUsername($username) {
        try {
            $query = $this->db->prepare("SELECT * FROM kasir WHERE username = :username");
            $query->bindParam(':username', $username);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            error_log("Get kasir by username error: " . $e->getMessage());
            return false;
        }
    }
    
    public function addKasir($username, $nama, $password) {
        try {
            $this->db->beginTransaction();
            
            // Cek apakah username sudah ada
            $cekUser = $this->db->prepare("SELECT username FROM user WHERE username = :username");
            $cekUser->bindParam(':username', $username);
            $cekUser->execute();
            
            if ($cekUser->fetch()) {
                $this->db->rollBack();
                return false; // Username sudah ada
            }
            
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert ke tabel user
            $insertUser = $this->db->prepare(
                "INSERT INTO user (username, password, role, dibuat_pada) 
                 VALUES (:username, :password, 'kasir', NOW())"
            );
            $insertUser->bindParam(':username', $username);
            $insertUser->bindParam(':password', $hashed_password);
            $insertUser->execute();
            
            // Insert ke tabel kasir
            $insertKasir = $this->db->prepare(
                "INSERT INTO kasir (username, nama, status) 
                 VALUES (:username, :nama, 1)"
            );
            $insertKasir->bindParam(':username', $username);
            $insertKasir->bindParam(':nama', $nama);
            $insertKasir->execute();
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Add kasir error: " . $e->getMessage());
            return false;
        }
    }
    
    public function rubahStatus($id_kasir, $status_baru) {
        try {
            $query = $this->db->prepare("UPDATE kasir SET status = :status WHERE id_kasir = :id");
            $query->bindParam(':status', $status_baru);
            $query->bindParam(':id', $id_kasir);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Rubah status error: " . $e->getMessage());
            return false;
        }
    }
    
    public function toggleStatus($id_kasir) {
        try {
            $query = "UPDATE kasir 
                      SET status = NOT status 
                      WHERE id_kasir = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_kasir]);
            
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function setStatus($id_kasir, $status) {
        try {
            $query = "UPDATE kasir 
                      SET status = ? 
                      WHERE id_kasir = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$status, $id_kasir]);
            
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getAllKasir($search = null) {
        $sql = "SELECT * FROM kasir";
        if ($search) {
            $sql .= " WHERE username LIKE :keyword OR nama LIKE :keyword OR id_kasir LIKE :keyword";
        }
        
        $sql .= " ORDER BY id_kasir DESC";
        $query = $this->db->prepare($sql);
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->bindParam(':keyword', $searchTerm);
        }
        $query->execute();
        return $query->fetchAll();
    }

    public function getKasirById($id_kasir) {
        $query = $this->db->prepare("SELECT * FROM kasir WHERE id_kasir = :id_kasir");
        $query->bindParam(':id_kasir', $id_kasir);
        $query->execute();
        return $query->fetch();
    }

    public function updateKasir($id_kasir, $new_username, $nama, $status, $reset_password) {
        try {
            $this->db->beginTransaction();

            $getUsername = $this->db->prepare("SELECT username FROM kasir WHERE id_kasir = :id");
            $getUsername->bindParam(':id', $id_kasir);
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

                $upUser = $this->db->prepare("UPDATE user SET username = :new WHERE username = :old AND role='kasir'");
                $upUser->bindParam(':new', $new_username);
                $upUser->bindParam(':old', $old_username);
                $upUser->execute();

                $target_username = $new_username;
            }

            if ($reset_password) {
                $hashed_password = password_hash($reset_password, PASSWORD_DEFAULT);
                $upPass = $this->db->prepare("UPDATE user SET password = :pass WHERE username = :usr");
                $upPass->bindParam(':pass', $hashed_password);
                $upPass->bindParam(':usr', $target_username);
                $upPass->execute();
            }

            $qKasir = $this->db->prepare("UPDATE kasir SET username=:username, nama=:nama, status=:st WHERE id_kasir=:id");
            $qKasir->bindParam(':username', $target_username);
            $qKasir->bindParam(':nama', $nama);
            $qKasir->bindParam(':st', $status);
            $qKasir->bindParam(':id', $id_kasir);

            if (!$qKasir->execute()) {
                $this->db->rollBack();
                return 0;
            }

            $this->db->commit();
            return 1;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Update kasir error: " . $e->getMessage());
            return 0;
        }
    }

    public function deleteKasir($id_kasir) {
        try {
            $this->db->beginTransaction();

            $getUsername = $this->db->prepare("SELECT username FROM kasir WHERE id_kasir = :id");
            $getUsername->bindParam(':id', $id_kasir);
            $getUsername->execute();
            $result = $getUsername->fetch();

            if (!$result) {
                $this->db->rollBack();
                return 0;
            }

            $username = $result['username'];

            $delKasir = $this->db->prepare("DELETE FROM kasir WHERE id_kasir = :id");
            $delKasir->bindParam(':id', $id_kasir);
            $delKasir->execute();

            $delUser = $this->db->prepare("DELETE FROM user WHERE username = :usr AND role='kasir'");
            $delUser->bindParam(':usr', $username);
            $delUser->execute();

            $this->db->commit();
            return 1;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Delete kasir error: " . $e->getMessage());
            return 0;
        }
    }
}
?>
