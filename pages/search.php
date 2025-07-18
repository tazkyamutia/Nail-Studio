<?php
require_once '../configdb.php';
if (session_status() == PHP_SESSION_NONE) session_start();

// Ambil kata kunci pencarian & trim
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Pagination setup
$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Filter dasar
$where = "WHERE (status = 'published' OR status = 'low stock') AND stock > 0";
$params = [];

// Tambahkan filter pencarian jika q tidak kosong dan bukan cuma spasi
if (strlen($query) > 0) {
    $where .= " AND (namaproduct LIKE :q OR category LIKE :q)";
    $params[':q'] = '%' . $query . '%';
}

// Hitung total produk
$countSql = "SELECT COUNT(*) AS total FROM product $where";
$countStmt = $conn->prepare($countSql);
foreach ($params as $k => $v) $countStmt->bindValue($k, $v);
$countStmt->execute();
$totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$totalPages = ceil($totalRows / $limit);

// Ambil produk untuk halaman ini
$sql = "SELECT id_product, namaproduct, stock, price, status, image
        FROM product
        $where
        ORDER BY added DESC
        LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil daftar produk favorit user jika login
$favIds = [];
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $favStmt = $conn->prepare("SELECT product_id FROM favorite WHERE user_id = ?");
    $favStmt->execute([$user_id]);
    $favIds = $favStmt->fetchAll(PDO::FETCH_COLUMN, 0);
}
?>

<?php include '../views/navbar.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Pencarian Produk - NailStudio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 p-6">

  <!-- Title hasil pencarian -->
   <h1 class="text-2xl font-bold mb-8 mt-10 text-center text-pink-700">
    The results of the products you are looking for: <span class="italic"><?= htmlspecialchars($query) ?></span>
  </h2>

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
            $isFavorite = in_array($product['id_product'], $favIds);
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
                    <button
                        class="w-full sm:w-1/6 flex items-center justify-center border border-gray-300 rounded-md text-pink-600 hover:text-pink-800 transition favorite-btn"
                        data-product-id="<?= $product['id_product'] ?>"
                        aria-label="<?= $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' ?>"
                        title="<?= $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' ?>"
                    >
                        <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="col-span-full text-center text-gray-500"> No products found for your search <b><?= htmlspecialchars($query) ?></b>.</p>
    <?php endif; ?>
  </div>

<?php if ($totalPages > 1): ?>
    <div class="flex justify-center mt-8 space-x-2 mb-12">
        <?php if ($page > 1): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $page - 1 ?>" class="px-4 py-2 rounded-md border bg-white text-pink-600 border-pink-300 hover:bg-pink-50">
                &laquo; Prev
            </a>
        <?php else: ?>
            <span class="px-4 py-2 rounded-md border bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed">
                &laquo; Prev
            </span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $i ?>"
               class="px-4 py-2 rounded-md border text-sm font-semibold
                      <?= ($i == $page)
                          ? 'bg-pink-600 text-white border-pink-600'
                          : 'bg-white text-pink-600 border-pink-300 hover:bg-pink-50' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $page + 1 ?>" class="px-4 py-2 rounded-md border bg-white text-pink-600 border-pink-300 hover:bg-pink-50">
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

        fetch('../favorite_api.php', {
            method: 'POST',
            body: fd
        })
        .then(res => res.text())
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
</script>

<?php include '../pages/footer.php'; ?>

</body>
</html>
