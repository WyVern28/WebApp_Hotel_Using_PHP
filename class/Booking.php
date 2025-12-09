<?php

require_once 'Database.php';

class Booking extends Database {

    public function getAllBookings() {
        try {
            $query = $this->db->prepare(
                "SELECT b.*, t.nama_lengkap, k.nomor_kamar, tk.nama_tipe, p.jumlah_bayar, p.status_pembayaran
                 FROM booking b
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN kamar k ON b.id_kamar = k. id
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk. id
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
                "SELECT b. *, t.nama_lengkap, t.no_ktp, k. nomor_kamar, tk. nama_tipe, p.jumlah_bayar, p. status_pembayaran
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
            $query = $this->db->prepare(
                "SELECT b.*, t.nama_lengkap, k.nomor_kamar, tk.nama_tipe, tk.harga_per_malam
                 FROM booking b
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN kamar k ON b.id_kamar = k.id
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                 WHERE b.id = :id"
            );
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

            // Insert Tamu dengan no_ktp (untuk walk-in guest)
            $queryTamu = $this->db->prepare(
                "INSERT INTO tamu (username, nama_lengkap, no_ktp, no_hp, status)
                 VALUES (NULL, :nama_lengkap, :no_ktp, :no_hp, 1)"
            );
            $queryTamu->bindParam(":nama_lengkap", $tamuData['nama_lengkap']);
            $queryTamu->bindParam(":no_ktp", $tamuData['no_ktp']);
            $queryTamu->bindParam(":no_hp", $tamuData['no_hp']);
            
            if (!$queryTamu->execute()) {
                throw new Exception("Gagal insert tamu: " . print_r($queryTamu->errorInfo(), true));
            }

            $idTamu = $this->db->lastInsertId();

            // Generate kode booking
            $kodeBooking = 'BKG' . date('YmdHis');
            $status = 'dibayar';

            // Dapatkan id_tipe_kamar dari kamar yang dipilih
            $queryGetTipe = $this->db->prepare("SELECT id_tipe_kamar FROM kamar WHERE id = :id_kamar");
            $queryGetTipe->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
            $queryGetTipe->execute();
            $kamarInfo = $queryGetTipe->fetch();
            
            if (!$kamarInfo) {
                throw new Exception("Kamar tidak ditemukan (ID: " . $bookingData['id_kamar'] . ")");
            }
            
            $id_tipe_kamar = $kamarInfo['id_tipe_kamar'];

            // Insert Booking (DENGAN id_tipe_kamar)
            $queryBooking = $this->db->prepare(
                "INSERT INTO booking (kode_booking, id_tamu, id_tipe_kamar, id_kamar, tgl_check_in, tgl_check_out, total_harga, status)
                 VALUES (:kode_booking, :id_tamu, :id_tipe_kamar, :id_kamar, :tgl_check_in, :tgl_check_out, :total_harga, :status)"
            );
            $queryBooking->bindParam(":kode_booking", $kodeBooking);
            $queryBooking->bindParam(":id_tamu", $idTamu, PDO::PARAM_INT);
            $queryBooking->bindParam(":id_tipe_kamar", $id_tipe_kamar, PDO::PARAM_INT);
            $queryBooking->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
            $queryBooking->bindParam(":tgl_check_in", $bookingData['tgl_check_in']);
            $queryBooking->bindParam(":tgl_check_out", $bookingData['tgl_check_out']);
            $queryBooking->bindParam(":total_harga", $bookingData['total_harga']);
            $queryBooking->bindParam(":status", $status);
            
            if (!$queryBooking->execute()) {
                throw new Exception("Gagal insert booking: " .  print_r($queryBooking->errorInfo(), true));
            }

            $idBooking = $this->db->lastInsertId();

            // Insert Pembayaran
            $statusPembayaran = 'berhasil';

            $queryPembayaran = $this->db->prepare(
                "INSERT INTO pembayaran (id_booking, metode_bayar, jumlah_bayar, tgl_bayar, status_pembayaran)
                 VALUES (:id_booking, :metode_bayar, :jumlah_bayar, NOW(), :status_pembayaran)"
            );
            $queryPembayaran->bindParam(":id_booking", $idBooking, PDO::PARAM_INT);
            $queryPembayaran->bindParam(":metode_bayar", $pembayaranData['metode_bayar']);
            $queryPembayaran->bindParam(":jumlah_bayar", $bookingData['total_harga']);
            $queryPembayaran->bindParam(":status_pembayaran", $statusPembayaran);
            
            if (! $queryPembayaran->execute()) {
                throw new Exception("Gagal insert pembayaran: " . print_r($queryPembayaran->errorInfo(), true));
            }

            // Update status kamar
            $queryKamar = $this->db->prepare("UPDATE kamar SET `status_kamar` = 'terisi' WHERE id = :id_kamar");
            $queryKamar->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
            
            if (!$queryKamar->execute()) {
                throw new Exception("Gagal update kamar: " . print_r($queryKamar->errorInfo(), true));
            }

            $this->db->commit();

            return $idBooking;

        } catch (Exception $e) {
            $this->db->rollBack();
            // Simpan error message di session untuk ditampilkan
            $_SESSION['booking_error_detail'] = $e->getMessage();
            return false;
        }
    }
    public function createBookingForRegisteredUser($bookingData){
        try{
            $this->db->beginTransaction();
            $kodeBooking = 'BKG' . date('YmdHis');
            $status = 'penting';
            $queryBooking = $this->db->prepare("INSERT INTO booking (kode_booking, id_tamu, id_tipe_kamar, id_kamar, tgl_check_in, tgl_check_out, dengan_sarapan, total_harga, `status`) VALUES (:kode_booking, :id_tamu, :id_tipe_kamar, :id_kamar, :tgl_check_in, :tgl_check_out, :dengan_sarapan, :total_harga, :status)");
            $queryBooking->execute([
            ':kode_booking' => $kodeBooking,
            ':id_tamu' => $bookingData['id_tamu'],
            ':id_tipe_kamar' => $bookingData['id_tipe_kamar'],
            ':id_kamar' => $bookingData['id_kamar'],
            ':tgl_check_in' => $bookingData['tgl_check_in'],
            ':tgl_check_out' => $bookingData['tgl_check_out'],
            ':dengan_sarapan' => $bookingData['dengan_sarapan'],
            ':total_harga' => $bookingData['total_harga'],
            ':status' => $status
        ]);
        $idBooking = $this->db->lastInsertId();
        if ($bookingData['id_kamar']) {
            $queryUpdateKamar = $this->db->prepare("UPDATE kamar SET `status_kamar` = 'terisi' WHERE id = :id_kamar");
            $queryUpdateKamar->execute([':id_kamar' => $bookingData['id_kamar']]);
        }
        $queryPembayaran = $this->db->prepare(
            "INSERT INTO pembayaran (id_booking, metode_bayar, jumlah_bayar, status_pembayaran)
             VALUES (:id_booking, 'transfer', :jumlah_bayar, 'pending')"
        );
        $queryPembayaran->execute([
            ':id_booking' => $idBooking,
            ':jumlah_bayar' => $bookingData['total_harga']
        ]);
        $this->db->commit();
        return $kodeBooking;
        }catch(PDOException $e){
            $this->db->rollBack();
            error_log("Gagal menambakan booking: ". $e->getMessage());
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
                $queryKamar = $this->db->prepare("UPDATE kamar SET `status_kamar` = 'tersedia' WHERE id = :id_kamar");
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
                 WHERE k.`status_kamar` = 'tersedia'"
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
                        b.tgl_check_in, b.tgl_check_out, b.`status`
                 FROM booking b
                 JOIN kamar k ON b. id_kamar = k.id
                 JOIN tamu t ON b.id_tamu = t.id
                 JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id
                 WHERE b.`status` = 'dibayar' OR b.`status` = 'check_in'
                 ORDER BY k.nomor_kamar"
            );
            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Get occupied rooms error: " . $e->getMessage());
            return [];
        }
    }

    public function updateBooking($id, $bookingData) {
        try {
            $this->db->beginTransaction();

            $oldBooking = $this->getBookingById($id);
            if (!$oldBooking) {
                throw new Exception("Booking tidak ditemukan");
            }

            $query = $this->db->prepare(
                "UPDATE booking 
                 SET id_kamar = :id_kamar,
                     tgl_check_in = :tgl_check_in,
                     tgl_check_out = :tgl_check_out,
                     total_harga = :total_harga
                 WHERE id = :id"
            );
            
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $query->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
            $query->bindParam(":tgl_check_in", $bookingData['tgl_check_in']);
            $query->bindParam(":tgl_check_out", $bookingData['tgl_check_out']);
            $query->bindParam(":total_harga", $bookingData['total_harga']);
            
            if (!$query->execute()) {
                throw new Exception("Gagal update booking");
            }

            if ($oldBooking['id_kamar'] != $bookingData['id_kamar']) {
                $queryOldKamar = $this->db->prepare(
                    "UPDATE kamar SET `status_kamar` = 'tersedia' WHERE id = :id_kamar"
                );
                $queryOldKamar->bindParam(":id_kamar", $oldBooking['id_kamar'], PDO::PARAM_INT);
                $queryOldKamar->execute();

                $queryNewKamar = $this->db->prepare(
                    "UPDATE kamar SET `status_kamar` = 'terisi' WHERE id = :id_kamar"
                );
                $queryNewKamar->bindParam(":id_kamar", $bookingData['id_kamar'], PDO::PARAM_INT);
                $queryNewKamar->execute();
            }

            if ($oldBooking['total_harga'] != $bookingData['total_harga']) {
                $queryPembayaran = $this->db->prepare(
                    "UPDATE pembayaran 
                     SET jumlah_bayar = :jumlah_bayar 
                     WHERE id_booking = :id_booking"
                );
                $queryPembayaran->bindParam(":jumlah_bayar", $bookingData['total_harga']);
                $queryPembayaran->bindParam(":id_booking", $id, PDO::PARAM_INT);
                $queryPembayaran->execute();
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Update booking error: " . $e->getMessage());
            return false;
        }
    }
}
?>