<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_product'];
    $namaproduct = $_POST['namaproduct'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $status =$_POST['status'];
    $added =$_POST['added'];
    $foto =$_POST['foto'];
    

    $sql = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama_produk', '$harga', '$stok')";

    if ($conn->query($sql) === TRUE) {
        echo "Produk berhasil ditambahkan. <a href='index.php'>Kembali ke Daftar Produk</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
