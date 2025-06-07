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
  COALESCE(SUM(ci.qty), 0) AS total_sold
FROM product p
LEFT JOIN cart_item ci ON p.id_product = ci.product_id
LEFT JOIN cart c ON ci.cart_id = c.id
  AND c.order_status = 'Completed'
  AND YEAR(c.created_at) = YEAR(CURDATE())
  AND MONTH(c.created_at) = MONTH(CURDATE())
GROUP BY 
  p.id_product, p.namaproduct, p.category, p.price, p.status, p.image
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
</head>
<body class="bg-[#eaf4fc]">

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-[#eaf4fc]">
  <h2 class="text-center text-black text-2xl font-normal mb-6">Top 10 Produk Terlaris Bulan Ini</h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
    <?php if (empty($topProducts)): ?>
      <p class="text-center w-full text-gray-600">Belum ada produk terlaris bulan ini.</p>
    <?php else: ?>
      <?php foreach ($topProducts as $product):
        $imagePath = '../uploads/' . $product['image'];
        $imageURL = (!empty($product['image']) && file_exists($imagePath))
          ? $imagePath
          : 'https://via.placeholder.com/220x220.png?text=No+Image';
        $productName = htmlspecialchars($product['namaproduct']);
        $productCategory = htmlspecialchars($product['category']);
        $productPrice = number_format($product['price'], 0, ',', '.');
        $isFavorite = in_array($product['id_product'], $favIds);
      ?>
      <div class="border border-gray-300 rounded-lg p-4 flex flex-col bg-white shadow-lg">
        <div class="flex justify-center mb-4 h-48">
          <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain rounded-lg"/>
        </div>
        <div class="mb-1 font-semibold text-gray-900 text-base leading-snug flex-grow"><?= $productName ?></div>
        <div class="text-sm text-gray-600 mb-2"><?= $productCategory ?></div>
        <div class="mb-4 text-gray-900 text-lg font-bold">Rp <?= $productPrice ?></div>
        <div class="flex gap-2 mt-auto">
          <button class="flex-1 bg-pink-600 hover:bg-pink-700 text-white py-2 rounded font-semibold" onclick="addToCart(<?= $product['id_product'] ?>)">Tambah ke Keranjang</button>
          <button
            class="w-12 flex items-center justify-center border border-gray-300 rounded text-pink-600 hover:text-pink-800 transition favorite-btn"
            data-product-id="<?= $product['id_product'] ?>"
            aria-label="<?= $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' ?>"
            title="<?= $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' ?>"
          >
            <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
  function addToCart(productId) {
    let fd = new FormData();
    fd.append('product_id', productId);
    fetch('../cart/add_to_cart.php', {
      method: 'POST',
      body: fd
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        if(typeof updateCartBadge === "function") updateCartBadge(data.cart_count);
        if(typeof openCartModal === "function") openCartModal();
      } else {
        alert('Gagal menambah ke keranjang! ' + (data.message || ''));
      }
    })
    .catch(err => alert('Terjadi error pada koneksi! ' + err));
  }

  // Toggle favorit
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
            // Update badge favorite jika ada
            const favBadge = document.getElementById('favorite-badge');
            if(favBadge && data.fav_count !== undefined) favBadge.textContent = data.fav_count;

            // Toggle icon favorit
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
          } else {
            alert('Gagal update favorit: ' + (data.message || ''));
          }
        } catch (e) {
          alert('Response bukan JSON valid:\n' + text);
        }
      })
      .catch(err => alert('Error jaringan: ' + err));
    });
  });
</script>

</body>
</html>
