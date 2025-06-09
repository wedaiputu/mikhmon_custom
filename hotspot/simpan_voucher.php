<?php
// Konfigurasi database
$host = "localhost"; // Sesuaikan dengan server database Anda
$dbname = "nama_database"; // Nama database
$username = "username_database"; // Username database
$password = "password_database"; // Password database

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan data voucher ada
if (isset($voucher_code) && isset($profile)) {
    $stmt = $conn->prepare("INSERT INTO voucher_transactions (voucher_code, profile, validity, price, sell_price, time_limit, data_limit, lock_user, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssiissi", $voucher_code, $profile, $validity, $price, $sell_price, $time_limit, $data_limit, $lock_user);

    if ($stmt->execute()) {
        echo "Voucher berhasil disimpan ke database.";
    } else {
        echo "Gagal menyimpan voucher: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
