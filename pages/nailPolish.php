<?php include '../views/navbar.php'; ?>
<?php
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;
$countSql = "SELECT COUNT(*) AS total FROM product WHERE category = 'nail polish' AND (status = 'published' OR status = 'low stock') AND stock > 0";
$countResult = $conn->query($countSql);
$totalRows = ($countResult !== false) ? $countResult->fetch(PDO::FETCH_ASSOC)['total'] : 0;
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT id_product, namaproduct, stock, price, status, image
        FROM product
        WHERE category = 'nail polish' 
          AND (status = 'published' OR status = 'low stock') 
          AND stock > 0
        ORDER BY added DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$products = ($result !== FALSE) ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nail Art</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Slab&display=swap" rel="stylesheet"/>
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

  <!-- ====== BAGIAN LANDING PAGE ====== -->
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
          <span class="text-gray-900 text-xs">Nail Art</span>
        </nav>
      </div>
      <div class="max-w-5xl mx-auto pr-6 pl-2 py-4 bg-[#d7e6fb] rounded-lg">
        <h1 class="text-3xl font-semibold text-gray-900 mb-3 text-left pl-0">Nail Art</h1>
        <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
          Nail polish. A splash of color and creativity at your fingertips!
        </p>
        <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
          If you’re searching for the perfect nail polish to express your style,
          you’ve come to the right place! At
          <a href="#" class="underline decoration-gray-900 decoration-1 underline-offset-2">
            Nail Art Studio
          </a>, we’re all about offering you the
        </p>
        <div id="moreContent" class="text-gray-900 text-base max-w-full hidden mb-4 text-left pl-0">
          perfect color, texture, and formula for every occasion. Whether you prefer a subtle nude, a bold red, or something sparkly and festive, nail art polish helps you show your personality in a fun and fashionable way.
          <br><br>
          With hundreds of styles to explore — from minimalist lines to extravagant rhinestones — nail art is more than just a trend; it's a form of self-expression. It boosts confidence, sparks creativity, and completes your overall look. Discover vibrant collections that last long, dry fast, and shine bright, making every manicure a masterpiece.
        </div>
        <div id="buttons" class="flex gap-4 justify-start pl-0">
          <button onclick="showMore()" class="flex items-center space-x-1 text-pink-600 text-sm font-medium"
            aria-label="Read more" id="readMoreBtn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
            <span>Read more</span>
          </button>
          <button onclick="showLess()" class="hidden items-center space-x-1 text-blue-600 text-sm font-medium"
            aria-label="Show less" id="showLessBtn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
            <span>Show less</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ====== GRID PRODUK NAIL POLISH ====== -->
  <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12 mt-12">
    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
            <?php
            $imagePath = '../uploads/' . $product['image'];
            $imageURL = (!empty($product['image']) && file_exists($imagePath)) 
                ? $imagePath 
                : 'https://via.placeholder.com/220x220.png?text=No+Image';
            $productName = htmlspecialchars($product['namaproduct']);
            $productPrice = number_format($product['price'], 0, ',', '.');
            $productStock = $product['stock'];
            $productStatus = $product['status'];
            ?>
            <div class="border border-gray-300 rounded-lg p-4 flex flex-col bg-white shadow-lg">
                <div class="flex justify-center mb-4 h-48">
                    <img src="<?= $imageURL ?>" alt="<?= $productName ?>" class="h-full w-auto object-contain rounded-lg"/>
                </div>
                <?php if ($productStatus == 'low stock' && $productStock > 0) : ?>
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
                    <button
                      class="w-full sm:w-1/6 flex items-center justify-center border border-gray-300 rounded-md text-pink-600 hover:text-pink-800 transition favorite-btn"
                      data-product-id="<?= $product['id_product'] ?>"
                      aria-label="Favorite"
                    >
                      <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="col-span-full text-center text-gray-500">Tidak ada produk yang tersedia saat ini.</p>
    <?php endif; ?>
  </div>
  <!-- /GRID PRODUK -->

  <!-- SCRIPT untuk read more/less, add to cart, dan wishlist tanpa login -->
  <script>
    function showMore() {
      document.getElementById('moreContent').classList.remove('hidden');
      document.getElementById('readMoreBtn').classList.add('hidden');
      document.getElementById('showLessBtn').classList.remove('hidden');
    }
    function showLess() {
      document.getElementById('moreContent').classList.add('hidden');
      document.getElementById('readMoreBtn').classList.remove('hidden');
      document.getElementById('showLessBtn').classList.add('hidden');
    }
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
      .catch(err => {
        alert('Terjadi error pada koneksi! ' + err);
      });
    }

    // ========== WISHLIST TANPA LOGIN ==========
    function loadWishlist() {
      return JSON.parse(localStorage.getItem('wishlist') || '[]');
    }
    function saveWishlist(arr) {
      localStorage.setItem('wishlist', JSON.stringify(arr));
    }
    function updateFavoriteBadge(count) {
      let badge = document.getElementById('favorite-badge');
      if (badge) badge.innerText = count;
    }
    function renderWishlist() {
      let arr = loadWishlist();
      document.querySelectorAll('.favorite-btn').forEach(btn => {
        const pid = btn.getAttribute('data-product-id');
        const icon = btn.querySelector('i');
        if (arr.includes(pid)) {
          icon.classList.remove('far');
          icon.classList.add('fas');
          btn.setAttribute('aria-label', 'Unfavorite');
        } else {
          icon.classList.remove('fas');
          icon.classList.add('far');
          btn.setAttribute('aria-label', 'Favorite');
        }
      });
      updateFavoriteBadge(arr.length);
    }
    document.querySelectorAll('.favorite-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const pid = this.getAttribute('data-product-id');
        let arr = loadWishlist();
        if (arr.includes(pid)) {
          arr = arr.filter(x => x !== pid);
        } else {
          arr.push(pid);
        }
        saveWishlist(arr);
        renderWishlist();
      });
    });
    document.addEventListener('DOMContentLoaded', renderWishlist);

  </script>
</body>
<?php include '../pages/footer.php'; ?>
</html>
