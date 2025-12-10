<?php
// session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'tamu') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

if (!isset($data)) {
    header('Location: ../../controller/user/BookingController.php');
    exit();
}

$tipeKamar = $data['tipeKamar'];
$availableRooms = $data['availableRooms'];
$fasilitas = $data['fasilitas'];
$ratings = $data['ratings'];
$avgRating = $data['avgRating'];
$totalReviews = $data['totalReviews'];

$imageMapping = [
    'std.jpg' => 'hemat.jpg',
    'dlx.jpg' => 'luas.jpg',
    'suite.jpg' => 'fam.jpg'
];

$foto = $tipeKamar['foto'] ?? 'col.jpg';
if (isset($imageMapping[$foto])) {
    $foto = $imageMapping[$foto];
}
$imagePath = "../../asset/image/" . htmlspecialchars($foto);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo htmlspecialchars($tipeKamar['nama_tipe']); ?></title>
    <link rel="stylesheet" href="../../asset/css/booking.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-right">
            <a href="../../controller/user/IndexController.php" class="brand">Ivory Palace</a>
        </div>

        <div class="nav-center">
            <a href="../../controller/user/IndexController.php">Home</a>
            <a href="../../controller/user/IndexController.php#rooms">Booking</a>
            <a href="../../controller/user/ProfileController.php">Profile</a>
        </div>

        <div class="nav-left">
            <span class="username">
                Halo, <?php echo htmlspecialchars($data['username']); ?>
                (<?php echo htmlspecialchars($data['role']); ?>)
            </span>
            <a href="../../controller/user/IndexController.php?logout=true" class="logout">Logout</a>
        </div>
    </nav>

    <?php if ($data['message']): ?>
        <div class="alert alert-<?php echo $data['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show m-3"
            role="alert">
            <?php echo htmlspecialchars($data['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="detail-container">
        <div class="detail-content">
            <h1 class="detail-title"><?php echo htmlspecialchars($tipeKamar['nama_tipe']); ?></h1>
            <p class="detail-loc">Ivory Palace Hotel</p>

            <div class="gallery-container">
                <div class="gallery-main">
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($tipeKamar['nama_tipe']); ?>"
                        onerror="this.src='../../asset/image/col.jpg'">
                </div>
            </div>

            <hr class="divider">

            <h3>Tentang Kamar Ini</h3>
            <p class="detail-desc">
                <?php echo nl2br(htmlspecialchars($tipeKamar['deskripsi'])); ?>
            </p>

            <h3>Fasilitas Kamar</h3>
            <ul class="facility-list">
                <?php if (!empty($fasilitas)): ?>
                    <?php foreach ($fasilitas as $fas): ?>
                        <li>✓ <?php echo htmlspecialchars($fas['nama_fasilitas']); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Tidak ada fasilitas tersedia</li>
                <?php endif; ?>
            </ul>

            <hr class="divider">

            <div class="review-section">
                <h3>Ulasan Tamu</h3>
                <div class="review-score">
                    <span class="score-badge"><?php echo $avgRating; ?>/5</span>
                    <span class="score-text">
                        <?php
                        if ($avgRating >= 4.5)
                            echo 'Luar Biasa';
                        elseif ($avgRating >= 4.0)
                            echo 'Sangat Baik';
                        elseif ($avgRating >= 3.5)
                            echo 'Baik';
                        elseif ($avgRating >= 3.0)
                            echo 'Cukup';
                        else
                            echo 'Perlu Perbaikan';
                        ?>
                        &middot; Dari <?php echo $totalReviews; ?> ulasan
                    </span>
                </div>

                <?php if (!empty($ratings)): ?>
                    <?php foreach (array_slice($ratings, 0, 3) as $rating): ?>
                        <div class="review-card">
                            <div class="reviewer-info">
                                <strong><?php echo htmlspecialchars($rating['nama_lengkap']); ?></strong>
                                <small><?php echo date('F Y', strtotime($rating['dibuat_pada'])); ?></small>
                                <span class="ms-2">⭐ <?php echo $rating['rating']; ?>/5</span>
                            </div>
                            <p>"<?php echo htmlspecialchars($rating['deskripsi']); ?>"</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Belum ada ulasan untuk tipe kamar ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <aside class="booking-sidebar">
            <div class="booking-card">
                <div class="price-header">
                    <span>Harga per malam</span>
                    <span class="big-price">Rp
                        <?php echo number_format($tipeKamar['harga_per_malam'], 0, ',', '.'); ?></span>
                </div>

                <?php if (empty($availableRooms)): ?>
                    <div class="alert alert-warning">
                        <strong>Maaf!</strong> Kamar untuk tipe ini sedang tidak tersedia.
                    </div>
                <?php else: ?>

                    <form action="" method="POST" id="bookingForm">
                        <input type="hidden" name="action" value="booking">

                        <div class="input-group-detail">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control-detail" placeholder="Nama Pemesan"
                                value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>

                        <div class="input-group-detail">
                            <label>Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="hp" class="form-control-detail" placeholder="08..."
                                pattern="[0-9]{10,13}" required>
                        </div>

                        <div class="input-group-detail">
                            <label>Pilih Nomor Kamar <span class="text-danger">*</span></label>
                            <select name="id_kamar" class="form-control-detail" required>
                                <option value="">-- Pilih Kamar --</option>
                                <?php foreach ($availableRooms as $room): ?>
                                    <option value="<?php echo $room['id']; ?>">
                                        Kamar <?php echo htmlspecialchars($room['nomor_kamar']); ?> - Lantai
                                        <?php echo $room['lantai']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="input-group-detail">
                            <label>Check-in <span class="text-danger">*</span></label>
                            <input type="date" name="checkin" class="form-control-detail" min="<?php echo date('Y-m-d'); ?>"
                                id="checkinDate" required>
                        </div>

                        <div class="input-group-detail">
                            <label>Check-out <span class="text-danger">*</span></label>
                            <input type="date" name="checkout" class="form-control-detail"
                                min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" id="checkoutDate" required>
                        </div>

                        <div class="input-group-detail">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sarapan" id="sarapan">
                                <label class="form-check-label" for="sarapan">
                                    Dengan Sarapan (+Rp
                                    <?php echo number_format($tipeKamar['harga_sarapan'], 0, ',', '.'); ?>/malam)
                                </label>
                            </div>
                        </div>

                        <div class="input-group-detail">
                            <label>Preferensi Khusus (Opsional)</label>
                            <textarea name="preferensi" class="form-control-detail" rows="3"
                                placeholder="Contoh: Non-smoking, Lantai atas, Dekat lift, dll"></textarea>
                            <small class="text-muted">Kami akan berusaha memenuhi preferensi Anda</small>
                        </div>

                        <div class="input-group-detail">
                            <label>Kode Diskon (Opsional)</label>
                            <div class="input-group">
                                <input type="text" name="kode_diskon" class="form-control-detail"
                                    placeholder="Masukkan kode promo" id="kodeDiskon" style="text-transform: uppercase;">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkDiskon()"
                                    style="margin-left: 5px; padding: 8px 15px;">
                                    Cek Kode
                                </button>
                            </div>
                            <small class="text-muted">Contoh: PRM10, MEMBER50</small>
                            <div id="diskonFeedback" class="mt-2"></div>
                        </div>

                        <div class="total-price mb-3">
                            <strong>Estimasi Total:</strong>
                            <span id="totalPrice">Rp 0</span>
                        </div>

                        <button type="submit" class="btn-confirm">Konfirmasi Booking</button>

                        <p class="note-text">Anda akan diminta untuk melakukan pembayaran setelah konfirmasi.</p>
                    </form>

                <?php endif; ?>
            </div>
        </aside>
    </div>

    <footer>
        <p>&copy; <?php echo $data['currentYear']; ?> Ivory Palace. Semua hak dilindungi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        const hargaPerMalam = <?php echo $tipeKamar['harga_per_malam']; ?>;
        const hargaSarapan = <?php echo $tipeKamar['harga_sarapan']; ?>;
        const checkinInput = document.getElementById('checkinDate');
        const checkoutInput = document.getElementById('checkoutDate');
        const sarapanCheckbox = document.getElementById('sarapan');
        const totalPriceEl = document.getElementById('totalPrice');

        function calculateTotal() {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);

            if (checkinInput.value && checkoutInput.value && checkout > checkin) {
                const diffTime = Math.abs(checkout - checkin);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                let total = hargaPerMalam * diffDays;

                if (sarapanCheckbox.checked) {
                    total += hargaSarapan * diffDays;
                }

                totalPriceEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                totalPriceEl.textContent = 'Rp 0';
            }
        }

        checkinInput.addEventListener('change', function () {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkoutInput.min = nextDay.toISOString().split('T')[0];
            calculateTotal();
        });

        checkoutInput.addEventListener('change', calculateTotal);
        sarapanCheckbox.addEventListener('change', calculateTotal);

        document.getElementById('bookingForm').addEventListener('submit', function (e) {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);

            if (checkout <= checkin) {
                e.preventDefault();
                alert('Tanggal check-out harus lebih dari tanggal check-in!');
                return false;
            }
        });
    </script>
</body>

</html>