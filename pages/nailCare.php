<?php include '../views/navbar.php'; ?>
<?php
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nail Care | Nail Studio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Slab&display=swap" rel="stylesheet"/>
  <style>
    html, body { height: 100%; margin: 0; background-color: white; font-family: "Poppins", sans-serif; }
  </style>
</head>
<body class="min-h-screen">

<!-- ====== LANDING PAGE NAIL CARE ====== -->
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
        <span class="text-gray-900 text-xs">Nail Care</span>
      </nav>
    </div>
    <div class="max-w-5xl mx-auto pr-6 pl-2 py-4 bg-[#d7e6fb] rounded-lg">
      <h1 class="text-3xl font-semibold text-gray-900 mb-3 text-left pl-0">Nail Care</h1>
      <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
        Healthy, beautiful nails start with the right care. Explore our Nail Care collection for everything you need to keep your nails and cuticles strong, smooth, and naturally radiant.
      </p>
      <p class="text-gray-900 mb-3 text-base max-w-full text-left pl-0">
        From nourishing oils and strengthening treatments to gentle removers and hydrating creams, our products are designed for all nail types—helping you prevent breakage, promote growth, and maintain salon-quality nails at home.
      </p>
      <div id="moreContent" class="text-gray-900 text-base max-w-full hidden mb-4 text-left pl-0">
        Say goodbye to brittle nails and dry cuticles! Our expert formulas deliver the nutrients your nails need for long-lasting health and shine. Whether you love a natural look or wear polish daily, proper nail care is the foundation of any stunning manicure.<br><br>
        Discover your new nail care essentials and give your hands the care and attention they deserve. Beautiful nails start here!
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

<!-- ====== PRODUCT GRID NAIL CARE ====== -->
<?php
$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;
$countSql = "SELECT COUNT(*) AS total FROM product WHERE category = 'nail care' AND (status = 'published' OR status = 'low stock') AND stock > 0";
$countResult = $conn->query($countSql);
$totalRows = ($countResult !== false) ? $countResult->fetch(PDO::FETCH_ASSOC)['total'] : 0;
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT id_product, namaproduct, stock, price, status, image
        FROM product
        WHERE category = 'nail care'
          AND (status = 'published' OR status = 'low stock')
          AND stock > 0
        ORDER BY added DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$products = ($result !== FALSE) ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
?>

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
            Add to Cart
          </button>
          <button class="w-full sm:w-1/6 flex items-center justify-center border border-gray-300 rounded-md text-pink-600 hover:text-pink-800 transition">
            <i class="far fa-heart"></i>
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <p class="col-span-full text-center text-gray-500">No products available at the moment.</p>
  <?php endif; ?>
</div>

<!-- Pagination if needed -->
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

<!-- SCRIPT -->
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
        alert('Failed to add to cart! ' + (data.message || ''));
      }
    })
    .catch(err => {
      alert('Connection error! ' + err);
    });
  }
</script>

<?php include '../pages/footer.php'; ?>
</body>
</html>
