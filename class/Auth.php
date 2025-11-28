<?php
/**
 * Class Auth
 * Menangani autentikasi user (login & register)
 * Menggunakan prepared statement untuk mencegah SQL Injection
 */

require_once 'Database.php';

class Auth extends Database {

    /**
     * Login user
     * @param string $username - Username user
     * @param string $password - Password user (plain text)
     * @return array|false - Data user jika berhasil, false jika gagal
     */
    public function login($username, $password) {
        try {

            $query = $this->db->prepare("SELECT * FROM user WHERE username = :username");

            $query->bindParam(":username", $username);

            $query->execute();

            $user = $query->fetch();

            if (!$user) {
                return false;
            }

            if ($password === $user['password'] || password_verify($password, $user['password'])) {
                return $user;
            }

            return false;

        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Register user baru
     * @param string $username - Username
     * @param string $password - Password (akan di-hash)
     * @param string $role - Role user (tamu/kasir/admin)
     * @return bool - True jika berhasil, false jika gagal
     */
    public function register($username, $password, $role = 'tamu') {
        try {
            $checkQuery = $this->db->prepare("SELECT id_user FROM user WHERE username = :username");
            $checkQuery->bindParam(":username", $username);
            $checkQuery->execute();

            if ($checkQuery->fetch()) {
                return false;
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = $this->db->prepare(
                "INSERT INTO user (username, password, role, dibuat_pada)
                 VALUES (:username, :password, :role, NOW())"
            );

            $query->bindParam(":username", $username);
            $query->bindParam(":password", $hashedPassword);
            $query->bindParam(":role", $role);

            if ($query->execute()) {
                return true;
            }

            return false;

        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek apakah username sudah ada
     * @param string $username
     * @return bool - True jika ada, false jika tidak
     */
    public function usernameExists($username) {
        try {
            $query = $this->db->prepare("SELECT id_user FROM user WHERE username = :username");
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
