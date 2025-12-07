<?php

require_once 'Database.php';

class Auth extends Database {
    /**
     * Login user
     * @param string $username
     * @param string $password
     * @return array|false Returns user data array on success, false on failure
     */
    public function login($username, $password) {
        try {
            $query = "SELECT u.*, 
                      CASE 
                          WHEN u.role = 'kasir' THEN k.status
                          WHEN u.role = 'tamu' THEN t.status
                          ELSE 1
                      END as user_status
                      FROM user u
                      LEFT JOIN kasir k ON u.username = k.username AND u.role = 'kasir'
                      LEFT JOIN tamu t ON u.username = t.username AND u.role = 'tamu'
                      WHERE u.username = ? ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return false;
            }
            
            if (! password_verify($password, $user['password'])) {
                return false;
            }
            
            if ($user['user_status'] == 0) {
                $_SESSION['login_error'] = 'Akun Anda telah dinonaktifkan.  Hubungi admin. ';
                return false;
            }
            
            return $user;
            
        } catch (PDOException $e) {
            $_SESSION['login_error'] = 'Terjadi kesalahan sistem';
            return false;
        }
    }

    public function register($username, $name, $telp, $password, $role = 'tamu') {
        try {
            $checkQuery = $this->db->prepare("SELECT username FROM user WHERE username = :username");
            $checkQuery->bindParam(":username", $username);
            $checkQuery->execute();

            if ($checkQuery->fetch()) {
                return false;
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $queryUser = $this->db->prepare(
                "INSERT INTO user (username, password, role, dibuat_pada)
                 VALUES (:username, :password, :role, NOW())"
            );
            $queryUser->bindParam(":username", $username);
            $queryUser->bindParam(":password", $hashedPassword);
            $queryUser->bindParam(":role", $role);

            $queryTamu = $this->db->prepare(
                "INSERT INTO tamu (username, nama_lengkap, no_hp)
                 VALUES (:username, :name, :telp)"
            );
            $queryTamu->bindParam(":username", $username);
            $queryTamu->bindParam(":name", $name);
            $queryTamu->bindParam(":telp", $telp);

            if ($queryUser->execute() && $queryTamu->execute()) {
                return true;
            }

            return false;

        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
            return false;
        }
    }


    public function usernameExists($username) {
        try {
            $query = $this->db->prepare("SELECT username FROM user WHERE username = :username");
            $query->bindParam(":username", $username);
            $query->execute();

            return $query->fetch() !== false;

        } catch (PDOException $e) {
            error_log("Username check error: " . $e->getMessage());
            return false;
        }
    }
}
?>
