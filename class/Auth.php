<?php

require_once 'Database.php';

class Auth extends Database {

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
