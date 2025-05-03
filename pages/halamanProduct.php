<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'nailstudio_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "SELECT id_product, namaproduct, stock, price, status, foto
        FROM product
        WHERE status = 'Published' OR status = 'Low stock'
        ORDER BY added DESC";

$result = $conn->query($sql);

if ($result === FALSE) {
    echo "Error executing query: " . htmlspecialchars($conn->error);
    $products = [];
} else {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<?php include '../views/headers.php'; ?>

<!DOCTYPE html>
<html lang="id">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Produk Nail Art Polish - NailStudio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
   @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
 </head>
 <body class="bg-gray-50 p-6">

  <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">

  <div class="
   <?php
    if (!empty($products)) :
        foreach ($products as $product) :
            $imageURL = !empty($product['foto']) ? htmlspecialchars($product['foto']) : 'https://via.placeholder.com/100x100.png?text=No+Image';
            $productName = htmlspecialchars($product['namaproduct']);
            $productPrice = number_format($product['price'], 0, ',', '.');
            $productStock = $product['stock'];
            $productStatus = $product['status'];
   ?>
        <div class="border border-gray-300 rounded-lg p-4 flex flex-col bg-white shadow">
            <?php if ($productStatus == 'Low stock') : ?>
            <div>
                <span class="inline-block bg-yellow-500 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
                    Stok Menipis
                </span>
            </div>
            <?php endif; ?>

            <div class="flex justify-center mb-4 h-24">
                <img
                    alt="<?= $productName ?>"
                    class="h-full w-auto object-contain"
                    src="<?= $imageURL ?>"
                />
            </div>

            <div class="mb-4 font-semibold text-gray-900 text-base leading-snug flex-grow">
                <?= $productName ?>
            </div>

            <div class="flex items-center space-x-2 mb-4 text-gray-900 text-lg font-bold">
                <span>Rp <?= $productPrice ?></span>
            </div>

            <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition
                           <?= ($productStock <= 0) ? 'bg-gray-400 hover:bg-gray-400 cursor-not-allowed' : '' ?>"
                    <?= ($productStock <= 0) ? 'disabled' : '' ?>>
                <?= ($productStock <= 0) ? 'Stok Habis' : 'Tambah ke Keranjang' ?>
            </button>
        </div>
   <?php
        endforeach;
    else :
   ?>
        <p class="col-span-full text-center text-gray-500">Tidak ada produk yang tersedia saat ini.</p>
   <?php
    endif;

    $conn->close();
   ?>

  </div>

 </body>
</html>
<br>
<br>

<?php include '../views/footers.php'; ?>