<?php
$host = "localhost";
$user = "root";
$password = "";

// Membuat Koneksi ke database
$conn = new mysqli($host, $user, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ID produk yang mau dihapus (contoh: id = 1)
$id_produk = 1; // Kamu bisa ganti nilainya manual atau ambil dari input

// Query DELETE
$sql = "DELETE FROM product WHERE id_product = $id_produk";

if ($conn->query($sql) === TRUE) {
    echo "Produk dengan ID $id_produk berhasil dihapus.";
} else {
    echo "Gagal menghapus produk: " . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
