<?php
$host = 'localhost';  // Host database (biasanya localhost)
$user = 'root';       // Username database (untuk XAMPP biasanya 'root')
$password = '';       // Password database (untuk XAMPP biasanya kosong)
$dbname = 'nailstudio_db';  // Nama database yang sudah dibuat

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
