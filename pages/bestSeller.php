<?php
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['id'] ?? null;

// Ambil semua kategori unik
$stmt = $conn->query("SELECT DISTINCT category FROM product");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Best seller per kategori (terlaris 1 bulan terakhir)
$bulan = date('m');
$tahun = date('Y');
$tgl_awal = "$tahun-".str_pad($bulan,2,'0',STR_PAD_LEFT)."-01";
$tgl_akhir = date("Y-m-t", strtotime($tgl_awal));

$products = [];
foreach ($categories as $cat) {
    $sql = "
        SELECT 
            p.id_product, p.namaproduct, p.stock, p.price, p.status, p.image, p.category,
            IFNULL(SUM(s.quantity), 0) AS total_sold
        FROM product p
        LEFT JOIN sales s 
            ON p.id_product = s.id_product 
            AND s.sale_date BETWEEN :tgl_awal AND :tgl_akhir
        WHERE p.category = :cat AND (p.status = 'published' OR p.status = 'low stock') AND p.stock > 0
        GROUP BY p.id_product
        ORDER BY total_sold DESC, p.id_product ASC
        LIMIT 1
    ";
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute([
        ':tgl_awal' => $tgl_awal,
        ':tgl_akhir' => $tgl_akhir,
        ':cat' => $cat
    ]);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $products[] = $row;
    }
}

// Ambil daftar produk favorit user (wishlist)
$favIds = [];
$favCount = 0;
if ($user_id) {
    $favStmt = $conn->prepare("SELECT product_id FROM favorite WHERE user_id = ?");
    $favStmt->execute([$user_id]);
    $favIds = $favStmt->fetchAll(PDO::FETCH_COLUMN, 0);

    $favCount = count($favIds);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shop Our Best Sellers</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: "Poppins", sans-serif;
    }
    .badge-absolute {
      position: absolute;
      top: -8px;
      right: -10px;
    }
  </style>
</head>
<body class="min-h-screen">

<!-- Contoh badge wishlist di navbar (wajib punya id="favorite-badge") -->
<!--
<nav>
  ...
  <span id="favorite-badge" class="inline-block min-w-[20px] text-xs bg-pink-600 text-white text-center rounded-full px-2 py-1"><?= $favCount ?></span>
  ...
</nav>
-->

<div class="max-w-7xl mx-auto py-10 px-4 bg-pink-100 rounded-xl shadow">
  <div class="flex flex-col md:flex-row md:space-x-8 mb-8">
    <div class="flex flex-col justify-center mb-8 md:mb-0 md:w-1/4">
      <h2 class="text-black text-2xl font-normal leading-snug mb-6 max-w-xs">
        Shop<br/> Our Best Sellers
      </h2>
     <a href="nowShop.php" class="bg-black text-white text-sm font-semibold rounded px-5 py-3 w-max inline-block">
  Shop sale now
</a>

    </div>
    <div class="md:w-3/4">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($products as $product): ?>
            <?php
            $imagePath = '../uploads/' . $product['image'];
            $imageURL = (!empty($product['image']) && file_exists($imagePath))
                ? $imagePath
                : 'https://via.placeholder.com/220x220.png?text=No+Image';
            $productName = htmlspecialchars($product['namaproduct']);
            $productPrice = number_format($product['price'], 0, ',', '.');
            $isFavorite = in_array($product['id_product'], $favIds);
            ?>
            <div class="border border-gray-300 rounded-lg p-4 flex flex-col bg-white shadow-lg">
          <span class="inline-block bg-pink-600 text-white text-xs font-semibold mb-2 w-max" style="padding:2px 8px; border-radius:10px;">
           Best Seller
          </span>
                <div class="flex justify-center mb-4 h-40">
                    <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain rounded-lg"/>
                </div>
                <p class="text-gray-700 text-xs font-semibold mb-1"><?= htmlspecialchars($product['category']) ?></p>
                <div class="mb-2 font-semibold text-gray-900 text-base leading-snug flex-grow"><?= $productName ?></div>
                <div class="flex items-center space-x-2 mb-4 text-gray-900 text-lg font-bold">Rp <?= $productPrice ?></div>
                <div class="flex gap-2 mt-auto">
                    <button class="flex-1 bg-pink-600 hover:bg-pink-700 text-white py-2 rounded font-semibold add-to-cart-btn" data-product-id="<?= $product['id_product'] ?>">Tambah ke Keranjang</button>
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
        <?php if (count($products) == 0): ?>
          <div class="w-full text-center text-gray-400 py-12 col-span-4">Belum ada produk terlaris bulan ini.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
// Add to Cart
document.querySelectorAll('.add-to-cart-btn').forEach(function(btn){
  btn.addEventListener('click', function(){
    let productId = btn.getAttribute('data-product-id');
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
  });
});

// Wishlist/Favorite (icon & badge langsung berubah tanpa reload)
document.querySelectorAll('.favorite-btn').forEach(function(btn){
  btn.addEventListener('click', function(e){
    e.preventDefault();
    const pid = btn.getAttribute('data-product-id');
    const icon = btn.querySelector('i');
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
            // Update badge di navbar jika ada
            const favBadge = document.getElementById('favorite-badge');
            if(favBadge && data.fav_count !== undefined) favBadge.textContent = data.fav_count;
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
