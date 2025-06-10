<?php
session_start();
require_once '../configdb.php';

// Pastikan user sudah login
if (!isset($_SESSION['id'])) {
    header('Location: register.php');
    exit;
}

$user_id = $_SESSION['id'];

// Handle hapus favorite jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $remove_product_id = intval($_POST['remove_product_id']);
    if ($remove_product_id > 0) {
        $stmt = $conn->prepare("DELETE FROM favorite WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $remove_product_id]);
        // Optional: flash message atau notifikasi sukses
    }
}

// Ambil produk favorit terbaru user
$stmt = $conn->prepare("SELECT p.id_product, p.namaproduct, p.price, p.image
                        FROM product p
                        INNER JOIN favorite f ON f.product_id = p.id_product
                        WHERE f.user_id = ?");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include navbar jika perlu
include '../views/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Produk Favorit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-semibold mb-6 text-pink-700">My Wishlist</h1>

    <?php if (count($favorites) === 0): ?>
      <p class="text-center text-gray-400">Belum ada produk favorit.</p>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($favorites as $product): ?>
          <div class="bg-white border rounded-lg shadow p-4 flex flex-col">
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                 alt="<?= htmlspecialchars($product['namaproduct']) ?>"
                 class="h-40 object-contain rounded mb-4"
                 onerror="this.src='https://via.placeholder.com/220x220.png?text=No+Image'">
            <div class="font-semibold text-gray-900 mb-2"><?= htmlspecialchars($product['namaproduct']) ?></div>
            <div class="font-bold text-pink-600 text-lg mb-2">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>

            <form method="POST" action="" class="mt-auto">
              <input type="hidden" name="remove_product_id" value="<?= (int)$product['id_product'] ?>">
              <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold transition">
                Hapus dari Favorit
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
