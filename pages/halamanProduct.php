<?php
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

// Pagination setup
$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Total products count (hanya yang stoknya > 0)
$countSql = "SELECT COUNT(*) AS total FROM _product WHERE (status = 'Published' OR status = 'Low stock') AND stock > 0";
$countResult = $conn->query($countSql);
$totalRows = ($countResult !== false) ? $countResult->fetch(PDO::FETCH_ASSOC)['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// Product data query (hanya yang stoknya > 0)
$sql = "SELECT id_product, namaproduct, stock, price, status, foto
        FROM _product
        WHERE (status = 'Published' OR status = 'Low stock') AND stock > 0
        ORDER BY added DESC
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
$products = ($result !== FALSE) ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

$conn = null;
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
        <div class="border border-gray-300 rounded-lg p-4 flex flex-col bg-white shadow-lg">
            <div class="flex justify-center mb-4 h-48">
                <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain rounded-lg"/>
            </div>

            <?php if ($productStatus == 'Low stock' && $productStock > 0) : ?>
                <span class="inline-block bg-yellow-500 text-white text-[10px] font-semibold rounded-full px-2 py-0.5 mb-2 self-start">
                    Low Stock
                </span>
            <?php endif; ?>

            <div class="mb-4 font-semibold text-gray-900 text-base leading-snug flex-grow">
                <?= $productName ?>
            </div>

            <div class="flex items-center space-x-2 mb-4 text-gray-900 text-lg font-bold">
                <span>Rp <?= $productPrice ?></span>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 mt-auto">
                <button class="w-full sm:w-5/6 font-semibold rounded-md py-2 px-3 transition bg-pink-600 hover:bg-pink-700 text-white"
                        onclick="addToCart(<?= $product['id_product'] ?>)">
                    Tambah ke Keranjang
                </button>
                <button class="w-full sm:w-1/6 flex items-center justify-center border border-gray-300 rounded-md text-pink-600 hover:text-pink-800 transition">
                    <i class="far fa-heart"></i>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <p class="col-span-full text-center text-gray-500">Tidak ada produk yang tersedia saat ini.</p>
<?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
    <div class="flex justify-center mt-8 space-x-2 mb-12">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 rounded-md border bg-white text-pink-600 border-pink-300 hover:bg-pink-50">
                &laquo; Prev
            </a>
        <?php else: ?>
            <span class="px-4 py-2 rounded-md border bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed">
                &laquo; Prev
            </span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" 
               class="px-4 py-2 rounded-md border text-sm font-semibold 
                      <?= ($i == $page) 
                          ? 'bg-pink-600 text-white border-pink-600' 
                          : 'bg-white text-pink-600 border-pink-300 hover:bg-pink-50' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 rounded-md border bg-white text-pink-600 border-pink-300 hover:bg-pink-50">
                Next &raquo;
            </a>
        <?php else: ?>
            <span class="px-4 py-2 rounded-md border bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed">
                Next &raquo;
            </span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
function addToCart(productId) {
  let fd = new FormData();
  fd.append('action','add');
  fd.append('product_id',productId);
  fetch('../cart/cart_api.php', {method:'POST',body:fd})
  .then(res=>res.json())
  .then(data=>{
    if(data.success) {
      if(typeof updateCartBadge==="function") updateCartBadge(data.cart_count);
      if(typeof openCartModal==="function") openCartModal();
    }
  });
}
</script>

</body>
</html>
<?php include '../views/footers.php'; ?>