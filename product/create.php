<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaproduct = $_POST['namaproduct'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $foto = $_POST['foto'];

    $sql = "INSERT INTO product (namaproduct, stock, price, status, added, foto) 
            VALUES ('$namaproduct', '$stock', '$price', '$status', NOW(), '$foto')";

    if ($conn->query($sql) === TRUE) {
        echo "Produk berhasil ditambahkan. <a href='index.php'>Kembali ke Daftar Produk</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
</head>
<body>
<h2>Tambah Produk</h2>
<form method="POST" action="">
    Nama Produk: <br>
    <input type="text" name="namaproduct" required><br>
    
    Stok: <br>
    <input type="number" name="stock" required><br>
    
    Price: <br>
    <input type="number" name="price" required><br><br>
    
    Status: <br>
    <select name="status" required>
      <option value="Low Stock">-- Low Stock --</option>
      <option value="Published">Published</option>
      <option value="Draft">Draft</option>
    </select><br><br>

    
    
    Foto: <br>
    <input type="text" name="foto" required><br><br> <!-- ubah ke text jika itu nama file -->
    
    <input type="submit" value="Tambah Produk">
</form>

</body>
</html>
