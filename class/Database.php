<?php
/**
 * koneksinya udh dibenerin biar aman dari SQL Injection
 */

class Database {
    private $host = "localhost";
    private $dbname = "db_hotel";
    private $username = "root";
    private $password = "";
    public $db;

    /**
     * Constructor - otomatis dijalankan saat objek dibuat
     * Membuat koneksi ke database menggunakan PDO
     */
    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    /**
     * Mendapatkan koneksi database
     * @return PDO
     */
    public function getConnection() {
        return $this->db;
    }
}
?>
