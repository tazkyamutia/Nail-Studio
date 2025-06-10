<?php
include '../views/navbar.php';
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['id'] ?? null;

$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Ambil total produk untuk pagination
$countSql = "SELECT COUNT(*) AS total FROM product WHERE category = 'nail tools' AND (status = 'published' OR status = 'low stock') AND stock > 0";
$countResult = $conn->query($countSql);
$totalRows = ($countResult !== false) ? $countResult->fetch(PDO::FETCH_ASSOC)['total'] : 0;
$totalPages = ceil($totalRows / $limit);

// Ambil produk
$sql = "SELECT id_product, namaproduct, stock, price, status, image
        FROM product
        WHERE category = 'nail tools' 
          AND (status = 'published' OR status = 'low stock') 
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
  <title>Nail tools</title>
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

<!-- ====== LANDING PAGE SECTION FOR NAIL POLISH ====== -->
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
          <span class="text-gray-900 text-xs">Nail Tools</span>
        </nav>
      </div>
      <div class="max-w-5xl mx-auto pr-6 pl-2 py-4 bg-[#d7e6fb] rounded-lg">
        <h1 class="text-3xl font-semibold text-gray-900 mb-3 text-left pl-0">Nail Tools</h1>
        <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
          Add a splash of color and personality to your look with our premium nail Tools collection! From timeless nudes and soft pastels to bold reds and dazzling glitter, Nail Art Studio brings you quality formulas that are easy to apply, quick to dry, and long-lasting.
        </p>
        <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
          Our nail Toolshes are perfect for both beginners and professionals, giving you flawless results at home or in the salon. Express your style, mood, and creativity with every manicure.
        </p>
        <div id="moreContent" class="text-gray-900 text-base max-w-full hidden mb-4 text-left pl-0">
          Discover vibrant colors, high-shine finishes, and chip-resistant formulas that keep your nails looking stunning day after day. Whether you’re preparing for a special occasion or just want to brighten up your week, there’s a shade for every moment.<br><br>
          Explore our collections—matte, glossy, shimmer, or classic—and let your nails be your ultimate fashion statement!
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
  <!-- ====== END LANDING PAGE ====== -->

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
    <div class="mb-2 font-semibold text-gray-900 text-base leading-snug flex-grow"><?= $productName ?></div>
    <?php if ($product['stock'] > 0): ?>
      <div class="mb-2 text-xs text-gray-500">Stok: <?= $product['stock'] ?></div>
    <?php else: ?>
      <div class="mb-2 text-xs text-red-400 font-semibold">Stok Habis</div>
    <?php endif; ?>
    <div class="flex items-center space-x-2 mb-4 text-gray-900 text-lg font-bold">Rp <?= $productPrice ?></div>
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

// Toggle favorit dengan debugging response
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

        fetch('favorite_api.php', {  // pastikan path ini benar ya, kalau satu folder
            method: 'POST',
            body: fd
        })
        .then(res => res.text())  // Ambil text dulu supaya kita tahu kalau error HTML
        .then(text => {
          try {
            const data = JSON.parse(text);
            if(data.success) {
                // Update badge
                const favBadge = document.getElementById('favorite-badge');
                if(favBadge && data.fav_count !== undefined) favBadge.textContent = data.fav_count;

                // Toggle icon
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
          alert('Failed to add to cart! ' + (data.message || ''));
        }
      })
      .catch(err => {
        alert('Connection error! ' + err);
      });
    }
  </script>
</script>
<?php include '../pages/footer.php'; ?>
</body>
</html>
