<?php
include '../views/navbar.php';
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['id'] ?? null;

$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Ambil total produk untuk pagination
$countSql = "SELECT COUNT(*) AS total FROM product WHERE (status = 'published' OR status = 'low stock') AND stock > 0";
$countResult = $conn->query($countSql);
$totalRows = ($countResult !== false) ? $countResult->fetch(PDO::FETCH_ASSOC)['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// Ambil produk
$sql = "SELECT id_product, namaproduct, category, stock, price, status, image
        FROM product
        WHERE (status = 'published' OR status = 'low stock')
          AND stock > 0
        ORDER BY added DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$products = ($result !== FALSE) ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

// Ambil daftar product_id favorit user jika login
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
  <title>All Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: white;
      font-family: "Poppins", sans-serif;
    }
  </style>
</head>
<body class="min-h-screen">

<div class="bg-[#d7e6fb] w-full py-4">
  <div class="max-w-5xl mx-auto px-4 rounded-lg bg-[#d7e6fb]">
    <div class="inline-block mb-6">
      <nav class="flex items-center space-x-2 text-gray-700 text-sm bg-white rounded-full px-3 py-1 select-none">
        <a href="index.php" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
          </svg>
          <span class="text-xs">Home</span>
        </a>
        <span class="text-xs">&gt;</span>
        <span class="text-gray-900 text-xs">All Products</span>
      </nav>
    </div>
    <div class="max-w-5xl mx-auto pr-6 pl-2 py-4 bg-[#d7e6fb] rounded-lg">
      <h1 class="text-3xl font-semibold text-gray-900 mb-3 text-left pl-0">All Products</h1>
      <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
        Discover all of our best collections right here! Explore every category with our full range of in-stock products, carefully curated for every style and need. Whether you're looking for everyday essentials or something special, you'll find everything available in one place—ready to shop.
      </p>
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
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
    <div class="flex justify-center mb-4 h-48">
        <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain rounded-lg"/>
    </div>
    <div class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($product['category']) ?></div>
    <div class="mb-2 font-semibold text-gray-900 text-base leading-snug flex-grow"><?= $productName ?></div>
    <?php if ($product['stock'] > 0): ?>
      <div class="mb-2 text-xs text-gray-500">Stok: <?= $product['stock'] ?></div>
    <?php else: ?>
      <div class="mb-2 text-xs text-red-400 font-semibold">Stok Habis</div>
    <?php endif; ?>
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
</div>

<!-- PAGINATION -->
<?php if ($totalPages > 1): ?>
<div class="flex justify-center py-6">
  <nav class="inline-flex items-center -space-x-px">
    <a href="?page=<?= max(1, $page-1) ?>"
      class="px-4 py-2 border border-gray-300 rounded-l <?= $page == 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-800 hover:bg-pink-100' ?>"
      <?= $page == 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
      &lt;
    </a>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i ?>"
        class="px-4 py-2 border border-gray-300 <?= $i == $page ? 'bg-pink-600 text-white' : 'bg-white text-gray-800 hover:bg-pink-100' ?> font-medium">
        <?= $i ?>
      </a>
    <?php endfor; ?>
    <a href="?page=<?= min($totalPages, $page+1) ?>"
      class="px-4 py-2 border border-gray-300 rounded-r <?= $page == $totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-800 hover:bg-pink-100' ?>"
      <?= $page == $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
      &gt;
    </a>
  </nav>
</div>
<?php endif; ?>

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
            // Icon toggle langsung (tanpa reload)
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
<?php include '../pages/footer.php'; ?>
</body>
</html>
