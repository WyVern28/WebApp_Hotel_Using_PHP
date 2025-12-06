<?php
require_once 'Database.php';

class Kasir extends Database {
    public function getAllKasir($search = null) {
        $sql = "SELECT * FROM kasir";
        if ($search) {
            $sql .= " WHERE username LIKE :keyword OR nama LIKE :keyword";
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

                $updateUser = $this->db->prepare("UPDATE user SET username = :new WHERE username = :old");
                $updateUser->bindParam(':new', $new_username);
                $updateUser->bindParam(':old', $old_username);
                $updateUser->execute();
                $target_username = $new_username;
            }

            $qUpdateKasir = $this->db->prepare("UPDATE kasir SET nama = :nama, status = :status WHERE id_kasir = :id");
            $qUpdateKasir->bindParam(':nama', $nama);
            $qUpdateKasir->bindParam(':status', $status);
            $qUpdateKasir->bindParam(':id', $id_kasir);
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

    public function addKasir($username, $name, $password){
        try {
            $cekKasir = $this->db->prepare("SELECT username FROM kasir WHERE username = :u");
            $cekKasir->bindParam(':u', $username);
            $cekKasir->execute();
            if ($cekKasir->rowCount() > 0) {
                return false;
            }

            $this->db->beginTransaction();

            $password = password_hash($password, PASSWORD_DEFAULT);
            $qUser = $this->db->prepare("INSERT INTO user (username, password, role) VALUES (:u, :p, 'kasir')");
            $qUser->bindParam(':u', $username);
            $qUser->bindParam(':p', $password);
            $qUser->execute();

            $qKasir = $this->db->prepare("INSERT INTO kasir (username, nama, status) VALUES (:u, :n, 1)");
            $qKasir->bindParam(':u', $username);
            $qKasir->bindParam(':n', $name);
            $qKasir->execute();

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function rubahStatus($id_kasir, $status) {
        $query = $this->db->prepare("UPDATE kasir SET status = :status WHERE id_kasir = :id_kasir");
        $query->bindParam(':status', $status);
        $query->bindParam(':id_kasir', $id_kasir);
        return $query->execute();
    }
}
?>
