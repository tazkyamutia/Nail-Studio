<?php
$host = "localhost";
$user = "root";
$password = "";

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $user, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Membuat database 'toko_online'
$sql = "CREATE DATABASE IF NOT EXISTS nailstudio_db";
if ($conn->query($sql) === TRUE) {
    echo "Database 'nailstudio_db' berhasil dibuat.<br>";
} else {
    echo "Error membuat database: " . $conn->error;
}

// Menggunakan database 'toko_online'
$conn->select_db("nailstudio_db");

// Membuat tabel 'kategori_barang'
$sql = "CREATE TABLE IF NOT EXISTS kategori_barang (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabel 'kategori_barang' berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel 'kategori_barang': " . $conn->error;
}

// Membuat tabel 'produk'
$sql = "CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL,
    id_kategori INT,
    FOREIGN KEY (id_kategori) REFERENCES kategori_barang(id_kategori)
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabel 'produk' berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel 'produk': " . $conn->error;
}

// Menutup koneksi
$conn->close();
?>