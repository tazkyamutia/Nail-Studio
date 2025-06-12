<?php
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['id'] ?? null;

$salesSql = "
SELECT 
  p.id_product,
  p.namaproduct,
  p.category,
  p.price,
  p.status,
  p.image,
  p.stock,
  COALESCE(SUM(ci.qty), 0) AS total_sold
FROM product p
LEFT JOIN cart_item ci ON p.id_product = ci.product_id
LEFT JOIN cart c ON ci.cart_id = c.id
  AND c.order_status = 'Completed'
  AND YEAR(c.created_at) = YEAR(CURDATE())
  AND MONTH(c.created_at) = MONTH(CURDATE())
GROUP BY 
  p.id_product, p.namaproduct, p.category, p.price, p.status, p.image, p.stock
HAVING total_sold > 0
ORDER BY total_sold DESC
LIMIT 10

";
$salesStmt = $conn->query($salesSql);
$topProducts = $salesStmt ? $salesStmt->fetchAll(PDO::FETCH_ASSOC) : [];

$favIds = [];
if ($user_id) {
    $favStmt = $conn->prepare("SELECT product_id FROM favorite WHERE user_id = ?");
    $favStmt->execute([$user_id]);
    $favIds = $favStmt->fetchAll(PDO::FETCH_COLUMN, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Top Sellers Bulan Ini</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-[#eaf4fc] min-h-screen">

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-[#eaf4fc]">
  <h2 class="text-center text-black text-2xl font-normal mb-6">Top Seller</h2>
  <div class="relative">
    <div class="flex overflow-x-auto scrollbar-hide gap-6 pb-4" style="scroll-snap-type: x mandatory;">


      <?php if (empty($topProducts)): ?>
        <div class="flex-none text-center w-full text-gray-600 py-12">Belum ada produk terlaris bulan ini.</div>
      <?php else: ?>
        <?php foreach ($topProducts as $product):
          $imagePath = '../uploads/' . $product['image'];
          $imageURL = (!empty($product['image']) && file_exists($imagePath))
            ? $imagePath
            : 'https://via.placeholder.com/160x160.png?text=No+Image';
          $productName = htmlspecialchars($product['namaproduct']);
          $productCategory = htmlspecialchars($product['category']);
          $productPrice = number_format($product['price'], 0, ',', '.');
          $isFavorite = in_array($product['id_product'], $favIds);
        ?>
      <div class="flex-none w-[300px] md:w-[320px] bg-white border border-gray-200 rounded-xl shadow p-6 flex flex-col scroll-snap-align-start">
  <div class="flex justify-center mb-4 h-48">
    <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain rounded-lg"/>
  </div>
  <div class="mb-1 font-semibold text-gray-900 text-lg leading-snug"><?= $productName ?></div>
  <!-- Stok abu-abu di bawah nama produk -->
  <?php if ($product['stock'] > 0): ?>
    <div class="mb-1 text-xs text-gray-500">Stok: <?= $product['stock'] ?></div>
  <?php else: ?>
    <div class="mb-1 text-xs text-gray-400 font-semibold">Stok Habis</div>
  <?php endif; ?>
  <div class="mb-4 text-gray-900 text-xl font-bold">Rp <?= $productPrice ?></div>
  <div class="flex gap-2 mt-auto">
    <button class="flex-1 bg-pink-600 hover:bg-pink-700 text-white py-3 rounded-lg font-semibold text-base whitespace-nowrap"
      onclick="addToCart(<?= $product['id_product'] ?>)" <?= $product['stock'] == 0 ? 'disabled style="opacity:0.6;cursor:not-allowed"' : '' ?>>
      Tambah ke Keranjang
    </button>
    <button
      class="w-14 flex items-center justify-center border border-gray-300 rounded-lg text-pink-600 hover:text-pink-800 transition favorite-btn"
      data-product-id="<?= $product['id_product'] ?>"
      aria-label="<?= $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' ?>"
      title="<?= $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' ?>"
      <?= $product['stock'] == 0 ? 'disabled style="opacity:0.6;cursor:not-allowed"' : '' ?>
    >
      <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart text-2xl"></i>
    </button>
  </div>
</div>

        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
// Add to Cart tanpa alert apapun
function addToCart(productId) {
  let fd = new FormData();
  fd.append('product_id', productId);
  fetch('../cart/add_to_cart.php', {
    method: 'POST',
    body: fd
  })
  .then(res => res.json())
  .then(data => {
    if(typeof updateCartBadge === "function") updateCartBadge(data.cart_count);
    if(typeof openCartModal === "function") openCartModal();
    
  })
  .catch(err => {
    
  });
}

// Toggle favorit tanpa notifikasi apapun
document.querySelectorAll('.favorite-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const pid = this.getAttribute('data-product-id');
    const icon = this.querySelector('i');
    const isFavorited = icon.classList.contains('fas');
    const action = isFavorited ? 'remove' : 'add';

    let fd = new FormData();
    fd.append('action', action);
    fd.append('product_id', pid);

    fetch('favorite_api.php', {
      method: 'POST',
      body: fd
    })
    .then(res => res.text())
    .then(text => {
      try {
        const data = JSON.parse(text);
        if(data.success) {
          const favBadge = document.getElementById('favorite-badge');
          if(favBadge && data.fav_count !== undefined) favBadge.textContent = data.fav_count;
          if(action === 'add') {
            icon.classList.remove('far');
            icon.classList.add('fas');
            btn.setAttribute('title', 'Hapus dari favorit');
            btn.setAttribute('aria-label', 'Hapus dari favorit');
          } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            btn.setAttribute('title', 'Tambah ke favorit');
            btn.setAttribute('aria-label', 'Tambah ke favorit');
          }
        }
       
      } catch (e) {
       
      }
    })
    .catch(err => {
     
    });
  });
});
</script>

</body>
</html>
