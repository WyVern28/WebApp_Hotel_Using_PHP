<?php

require_once 'Database.php';

class Booking extends Database {

    public function getAllBookings() {
        try {
            $query = $this->db->prepare(
                "SELECT b.*, t.nama_lengkap, k.nomor_kamar, tk.nama_tipe, p.jumlah_bayar, p.status_pembayaran
                 FROM booking b
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN kamar k ON b.id_kamar = k.id
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                 LEFT JOIN pembayaran p ON b.id = p.id_booking
                 ORDER BY b.id DESC"
            );
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get all bookings error: " . $e->getMessage());
            return [];
        }
    }


    public function getBookingsByDate($date) {
        try {
            $query = $this->db->prepare(
                "SELECT b.*, t.nama_lengkap, k.nomor_kamar, tk.nama_tipe, p.jumlah_bayar, p.status_pembayaran
                 FROM booking b
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN kamar k ON b.id_kamar = k.id
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                 LEFT JOIN pembayaran p ON b.id = p.id_booking
                 WHERE DATE(b.tgl_check_in) = :date
                 ORDER BY b.id DESC"
            );
            $query->bindParam(":date", $date);
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get bookings by date error: " . $e->getMessage());
            return [];
        }
    }


    public function getBookingById($id) {
        try {
            $query = $this->db->prepare("SELECT * FROM booking WHERE id = :id");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();

        } catch (PDOException $e) {
            error_log("Get booking by ID error: " . $e->getMessage());
            return false;
        }
    }

    public function createBooking($tamuData, $bookingData, $pembayaranData) {
        try {
            $this->db->beginTransaction();

            $queryTamu = $this->db->prepare(
                "INSERT INTO tamu (username, nama_lengkap, no_hp, status)
                 VALUES (:username, :nama_lengkap, :no_hp, 1)"
            );
            $queryTamu->bindParam(":username", $tamuData['no_ktp']);
            $queryTamu->bindParam(":nama_lengkap", $tamuData['nama_lengkap']);
            $queryTamu->bindParam(":no_hp", $tamuData['no_hp']);
            $queryTamu->execute();

            $idTamu = $this->db->lastInsertId();

            $kodeBooking = 'BKG' . date('YmdHis');
            $status = 'dibayar';

            $queryBooking = $this->db->prepare(
                "INSERT INTO booking (kode_booking, id_tamu, id_kamar, tgl_check_in, tgl_check_out, total_harga, status)
                 VALUES (:kode_booking, :id_tamu, :id_kamar, :tgl_check_in, :tgl_check_out, :total_harga, :status)"
            );
            $queryBooking->bindParam(":kode_booking", $kodeBooking);
            $queryBooking->bindParam(":id_tamu", $idTamu, PDO::PARAM_INT);
            $queryBooking->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
            $queryBooking->bindParam(":tgl_check_in", $bookingData['tgl_check_in']);
            $queryBooking->bindParam(":tgl_check_out", $bookingData['tgl_check_out']);
            $queryBooking->bindParam(":total_harga", $bookingData['total_harga']);
            $queryBooking->bindParam(":status", $status);
            $queryBooking->execute();

            $idBooking = $this->db->lastInsertId();

            $statusPembayaran = 'berhasil';

            $queryPembayaran = $this->db->prepare(
                "INSERT INTO pembayaran (id_booking, metode_bayar, jumlah_bayar, tgl_bayar, status_pembayaran)
                 VALUES (:id_booking, :metode_bayar, :jumlah_bayar, NOW(), :status_pembayaran)"
            );
            $queryPembayaran->bindParam(":id_booking", $idBooking, PDO::PARAM_INT);
            $queryPembayaran->bindParam(":metode_bayar", $pembayaranData['metode_bayar']);
            $queryPembayaran->bindParam(":jumlah_bayar", $bookingData['total_harga']);
            $queryPembayaran->bindParam(":status_pembayaran", $statusPembayaran);
            $queryPembayaran->execute();

            $queryKamar = $this->db->prepare("UPDATE kamar SET status = 'terisi' WHERE id = :id_kamar");
            $queryKamar->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
            $queryKamar->execute();

            $this->db->commit();

            return $idBooking;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Create booking error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBooking($id) {
        try {
            $this->db->beginTransaction();

            $queryGetKamar = $this->db->prepare("SELECT id_kamar FROM booking WHERE id = :id");
            $queryGetKamar->bindParam(":id", $id, PDO::PARAM_INT);
            $queryGetKamar->execute();
            $kamarData = $queryGetKamar->fetch();

            $queryDelPembayaran = $this->db->prepare("DELETE FROM pembayaran WHERE id_booking = :id_booking");
            $queryDelPembayaran->bindParam(":id_booking", $id, PDO::PARAM_INT);
            $queryDelPembayaran->execute();

            $queryDelBooking = $this->db->prepare("DELETE FROM booking WHERE id = :id");
            $queryDelBooking->bindParam(":id", $id, PDO::PARAM_INT);
            $queryDelBooking->execute();

            if ($kamarData) {
                $queryKamar = $this->db->prepare("UPDATE kamar SET status = 'tersedia' WHERE id = :id_kamar");
                $queryKamar->bindParam(":id_kamar", $kamarData['id_kamar'], PDO::PARAM_INT);
                $queryKamar->execute();
            }

            $this->db->commit();

            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Delete booking error: " . $e->getMessage());
            return false;
        }
    }

    public function getAvailableRooms() {
        try {
            $query = $this->db->prepare(
                "SELECT k.id, k.nomor_kamar, k.lantai, k.id_tipe_kamar,
                        tk.nama_tipe, tk.harga_per_malam
                 FROM kamar k
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                 WHERE k.status = 'tersedia'"
            );
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get available rooms error: " . $e->getMessage());
            return [];
        }
    }

    public function getOccupiedRooms() {
        try {
            $query = $this->db->prepare(
                "SELECT b.id, k.nomor_kamar, t.nama_lengkap, tk.nama_tipe,
                        b.tgl_check_in, b.tgl_check_out, b.status
                 FROM booking b
                 JOIN kamar k ON b.id_kamar = k.id
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                 WHERE b.status = 'dibayar' OR b.status = 'checkin'
                 ORDER BY k.nomor_kamar"
            );
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get occupied rooms error: " . $e->getMessage());
            return [];
        }
    }
}
?>
