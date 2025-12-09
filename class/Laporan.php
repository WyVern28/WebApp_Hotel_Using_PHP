<?php
require_once 'Database.php';

class Laporan extends Database {

    public function getLaporan($tgl_mulai, $tgl_selesai, $status_filter = null) {
        try {
            $sql = "SELECT b.kode_booking, b.tgl_check_in, b.tgl_check_out, 
                           b.total_harga, b.status, t.nama_lengkap, k.nomor_kamar
                    FROM booking b
                    JOIN tamu t ON b.id_tamu = t.id
                    LEFT JOIN kamar k ON b.id_kamar = k.id
                    WHERE (b.tgl_check_in BETWEEN :start AND :end)";
            if ($status_filter != 'semua') {
                $sql .= " AND b.status = :status";
            } else {
                $sql .= " AND b.status IN ('dibayar', 'check_in', 'selesai')";
            }

            $sql .= " ORDER BY b.tgl_check_in DESC";

            $query = $this->db->prepare($sql);
            $query->bindParam(':start', $tgl_mulai);
            $query->bindParam(':end', $tgl_selesai);
            
            if ($status_filter != 'semua') {
                $query->bindParam(':status', $status_filter);
            }

            $query->execute();
            return $query->fetchAll();

        } catch (PDOException $e) {
            error_log("Laporan Error: " . $e->getMessage());
            return [];
        }
    }
}
?>