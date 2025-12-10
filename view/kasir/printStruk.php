<?php
if (!isset($data)) {
    die('Akses ditolak!');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Booking - Hotel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: #f5f5f5;
        }

        .struk-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .struk {
            border: 2px solid #000;
            padding: 20px;
            margin-bottom: 30px;
            page-break-after: always;
        }

        .struk:last-child {
            page-break-after: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
        }

        .section {
            margin: 15px 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
            padding-bottom: 3px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 13px;
        }

        .info-label {
            font-weight: bold;
            width: 40%;
        }

        .info-value {
            width: 60%;
            text-align: right;
        }

        .total-section {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            margin: 15px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #000;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-dibayar {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 30px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .print-btn:hover {
            background: #357abd;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .struk-container {
                box-shadow: none;
                padding: 0;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">🖨️ CETAK</button>

    <div class="struk-container">
        <?php foreach ($data['bookings'] as $booking): ?>
            <?php
            // Hitung durasi menginap
            $checkin = new DateTime($booking['tgl_check_in']);
            $checkout = new DateTime($booking['tgl_check_out']);
            $durasi = $checkin->diff($checkout)->days;
            ?>

            <div class="struk">
                <div class="header">
                    <h1>🏨 IVORY PALACE HOTEL</h1>
                    <p>Jl. Raya Hotel No. 123, Jakarta</p>
                    <p>Telp: (021) 1234-5678 | Email: info@ivorypalace.com</p>
                    <p style="margin-top: 10px; font-weight: bold;">BUKTI PEMBAYARAN BOOKING</p>
                </div>

                <div class="section">
                    <div class="section-title">INFORMASI BOOKING</div>
                    <div class="info-row">
                        <span class="info-label">Kode Booking</span>
                        <span class="info-value"><?php echo $booking['kode_booking']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Cetak</span>
                        <span class="info-value"><?php echo $data['tanggal_cetak']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Petugas Kasir</span>
                        <span class="info-value"><?php echo $data['kasir']; ?></span>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">DATA TAMU</div>
                    <div class="info-row">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value"><?php echo $booking['nama_lengkap']; ?></span>
                    </div>
                    <?php if (isset($booking['no_ktp']) && !empty($booking['no_ktp'])): ?>
                        <div class="info-row">
                            <span class="info-label">No. KTP</span>
                            <span class="info-value"><?php echo $booking['no_ktp']; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($booking['no_hp']) && !empty($booking['no_hp'])): ?>
                        <div class="info-row">
                            <span class="info-label">No. Telepon</span>
                            <span class="info-value"><?php echo $booking['no_hp']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="section">
                    <div class="section-title">DETAIL KAMAR</div>
                    <div class="info-row">
                        <span class="info-label">Nomor Kamar</span>
                        <span class="info-value"><?php echo $booking['nomor_kamar']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tipe Kamar</span>
                        <span class="info-value"><?php echo $booking['nama_tipe']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Harga per Malam</span>
                        <span class="info-value">Rp
                            <?php echo number_format($booking['harga_per_malam'], 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">PERIODE MENGINAP</div>
                    <div class="info-row">
                        <span class="info-label">Check-In</span>
                        <span class="info-value"><?php echo date('d/m/Y', strtotime($booking['tgl_check_in'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Check-Out</span>
                        <span class="info-value"><?php echo date('d/m/Y', strtotime($booking['tgl_check_out'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Durasi Menginap</span>
                        <span class="info-value"><?php echo $durasi; ?> Malam</span>
                    </div>
                    <?php if (isset($booking['dengan_sarapan']) && $booking['dengan_sarapan'] == 1): ?>
                        <div class="info-row">
                            <span class="info-label">Sarapan</span>
                            <span class="info-value">✓ Termasuk</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span>TOTAL PEMBAYARAN</span>
                        <span>Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">INFORMASI PEMBAYARAN</div>
                    <div class="info-row">
                        <span class="info-label">Metode Pembayaran</span>
                        <span class="info-value"><?php echo ucfirst($booking['metode_bayar'] ?? 'Cash'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jumlah Dibayar</span>
                        <span class="info-value">Rp
                            <?php echo number_format($booking['jumlah_bayar'] ?? $booking['total_harga'], 0, ',', '.'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Pembayaran</span>
                        <span class="info-value">
                            <span class="status-badge status-<?php echo $booking['status_pembayaran'] ?? 'pending'; ?>">
                                <?php echo strtoupper($booking['status_pembayaran'] ?? 'PENDING'); ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Booking</span>
                        <span class="info-value">
                            <span class="status-badge status-<?php echo $booking['status']; ?>">
                                <?php echo strtoupper($booking['status']); ?>
                            </span>
                        </span>
                    </div>
                </div>

                <div class="footer">
                    <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
                    <p style="margin-top: 10px;">Struk ini adalah bukti pembayaran yang sah</p>
                    <p>Simpan struk ini sebagai bukti transaksi</p>
                    <p style="margin-top: 15px; font-size: 10px;">
                        Dicetak pada: <?php echo $data['tanggal_cetak']; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>