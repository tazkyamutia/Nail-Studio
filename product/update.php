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
    $id_product = $_POST['id_product'];
    $namaproduct = $_POST['namaproduct'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $id_kategori = $_POST['id_kategori'];

    // Query update dengan prepared statement
    $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, status=?, id_kategori=? WHERE id_produk=?");
    $stmt->bind_param("sdiii", $id_product, $namaproduct, $stok, $price, $status, $added, $foto);

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