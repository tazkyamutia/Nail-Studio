<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "nailstudio_db";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produk = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $id_kategori = $_POST['id_kategori'];

    // Query update dengan prepared statement
    $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=?, id_kategori=? WHERE id_produk=?");
    $stmt->bind_param("sdiii", $nama_produk, $harga, $stok, $id_kategori, $id_produk);

    if ($stmt->execute()) {
        echo "Produk berhasil diupdate.";
    } else {
        echo "Gagal update produk: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Form belum dikirim.";
}

$conn->close();
?>