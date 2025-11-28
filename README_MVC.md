# Struktur MVC - Hotel Management System

## 📁 Struktur Folder

```
TR_Pemrograman_Web/
├── class/              # MODEL - Class untuk database operations
│   ├── Database.php    # Koneksi PDO
│   ├── Auth.php        # Login & Register
│   └── Booking.php     # CRUD Booking
│
├── controller/         # CONTROLLER - Logic & business rules
│   ├── kasir/
│   │   ├── DashboardController.php
│   │   ├── OtsOrderController.php
│   │   └── OccupancyController.php
│   ├── admin/
│   └── user/
│
├── view/              # VIEW - Tampilan UI saja (HTML + PHP)
│   ├── kasir/
│   │   ├── kasirPage.php
│   │   ├── otsOrder.php
│   │   └── occupancy.php
│   ├── admin/
│   └── user/
│
├── asset/             # CSS, JS, Images
└── config/            # Konfigurasi (legacy)
```

---

## 🔄 Alur MVC

```
User Request
    ↓
Controller (Logic)
    ↓
Model/Class (Database)
    ↓
Controller (Proses Data)
    ↓
View (Tampilan)
    ↓
Response ke User
```

---

## 📝 Cara Penggunaan

### 1. **Model (Class)**
Class berisi fungsi untuk akses database menggunakan PDO

```php
// class/Booking.php
class Booking extends Database {
    public function getAllBookings() {
        // Query dengan prepared statement
    }
}
```

### 2. **Controller**
File PHP yang menangani logic dan memanggil Model

```php
// controller/kasir/DashboardController.php
require_once '../../class/Booking.php';

$booking = new Booking();
$data = [
    'bookings' => $booking->getAllBookings()
];

include '../../view/kasir/kasirPage.php';
```

### 3. **View**
File PHP yang hanya berisi HTML dan tampilkan data dari Controller

```php
// view/kasir/kasirPage.php
<h1>Dashboard</h1>
<?php foreach ($data['bookings'] as $booking): ?>
    <p><?php echo $booking['nama']; ?></p>
<?php endforeach; ?>
```

---

## 🚀 Cara Akses

### ❌ **JANGAN** langsung akses View:
```
http://localhost/pemweb/TR_Pemrograman_Web/view/kasir/kasirPage.php
```

### ✅ **HARUS** akses Controller:
```
http://localhost/pemweb/TR_Pemrograman_Web/controller/kasir/DashboardController.php
```

---

## 🔐 Keamanan

1. **PDO dengan Prepared Statement** - Aman dari SQL Injection
2. **Password Hashing** - Password di-hash dengan `password_hash()`
3. **Session Authentication** - Cek role sebelum akses controller
4. **Input Validation** - Validasi semua input user

---

## 📌 Contoh Lengkap: OTS Order

### Controller (`controller/kasir/OtsOrderController.php`)
```php
session_start();

// Cek autentikasi
if ($_SESSION['role'] !== 'kasir') {
    header('Location: ../../view/login.php');
    exit();
}

// Import model
require_once '../../class/Booking.php';
$booking = new Booking();

// Proses form
if ($_POST['action'] === 'create') {
    $result = $booking->createBooking($tamuData, $bookingData, $pembayaranData);
}

// Siapkan data untuk view
$data = [
    'availableRooms' => $booking->getAvailableRooms(),
    'todayOrders' => $booking->getBookingsByDate(date('Y-m-d'))
];

// Load view
include '../../view/kasir/otsOrder.php';
```

### View (`view/kasir/otsOrder.php`)
```php
<h1>OTS Order</h1>

<!-- Form -->
<form method="POST">
    <input type="text" name="nama" required>
    <button type="submit">Submit</button>
</form>

<!-- Tampilkan data -->
<?php foreach ($data['todayOrders'] as $order): ?>
    <p><?php echo $order['nama']; ?></p>
<?php endforeach; ?>
```

---

## ⚡ Keuntungan Struktur Ini

1. **Separation of Concerns** - Logic terpisah dari UI
2. **Reusable** - Model bisa dipakai di controller manapun
3. **Maintainable** - Mudah di-maintain dan debug
4. **Secure** - PDO prepared statement untuk keamanan
5. **Clean Code** - Kode lebih terstruktur dan rapi

---

## 🛠️ Migration dari Versi Lama

### Versi Lama (Procedural):
```php
// view/kasir/kasirPage.php
<?php
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM booking");
while ($row = mysqli_fetch_assoc($query)) {
    echo $row['nama'];
}
?>
```

### Versi Baru (MVC):
```php
// controller/kasir/DashboardController.php
require_once '../../class/Booking.php';
$booking = new Booking();
$data['bookings'] = $booking->getAllBookings();
include '../../view/kasir/kasirPage.php';

// view/kasir/kasirPage.php
<?php foreach ($data['bookings'] as $row): ?>
    <?php echo $row['nama']; ?>
<?php endforeach; ?>
```

---

## 📚 Referensi

- **PDO Documentation**: https://www.php.net/manual/en/book.pdo.php
- **MVC Pattern**: https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller
- **Prepared Statements**: https://www.php.net/manual/en/pdo.prepared-statements.php
