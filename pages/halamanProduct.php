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

$products = ($result !== FALSE) ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
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

<div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12 mt-12">

<?php if (!empty($products)) : ?>
    <?php foreach ($products as $product) : ?>
        <?php
        $imageURL = !empty($product['foto']) ? htmlspecialchars($product['foto']) : 'https://via.placeholder.com/100x100.png?text=No+Image';
        $productName = htmlspecialchars($product['namaproduct']);
        $productPrice = number_format($product['price'], 0, ',', '.');
        $productStock = $product['stock'];
        $productStatus = $product['status'];
        ?>
        <div class="border border-gray-300 rounded-lg p-4 flex flex-col bg-white shadow">
            <div class="flex justify-center mb-4 h-48">
                <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain"/>
            </div>

            <?php if ($productStatus == 'Low stock' && $productStock > 0) : ?>
                <span class="inline-block bg-yellow-500 text-white text-[10px] font-semibold rounded-full px-2 py-0.5 mb-2 self-start">
                    Stok Menipis
                </span>
            <?php endif; ?>

            <div class="mb-4 font-semibold text-gray-900 text-base leading-snug flex-grow">
                <?= $productName ?>
            </div>

            <div class="flex items-center space-x-2 mb-4 text-gray-900 text-lg font-bold">
                <span>Rp <?= $productPrice ?></span>
            </div>

            <div class="flex gap-2 mt-auto">
                <!-- Add to cart button -->
                <button class="w-5/6 font-semibold rounded-md py-2 px-3 transition
                               <?= ($productStock <= 0) ? 'bg-gray-400 text-white cursor-not-allowed' : 'bg-pink-600 hover:bg-pink-700 text-white' ?>"
                        <?= ($productStock <= 0) ? 'disabled' : '' ?>>
                    <?= ($productStock <= 0) ? 'Stok Habis' : 'Tambah ke Keranjang' ?>
                </button>

                <!-- Wishlist button -->
                <button class="w-1/6 flex items-center justify-center border border-gray-300 rounded-md text-pink-600 hover:text-pink-800 transition
                               <?= ($productStock <= 0) ? 'opacity-50 cursor-not-allowed' : '' ?>"
                        <?= ($productStock <= 0) ? 'disabled' : '' ?>>
                    <i class="far fa-heart"></i>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <p class="col-span-full text-center text-gray-500">Tidak ada produk yang tersedia saat ini.</p>
<?php endif; ?>

</div>

</body>
</html>

<?php include '../views/footers.php'; ?>