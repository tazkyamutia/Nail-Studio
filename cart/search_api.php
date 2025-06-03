<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nailstudio_db';

$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $products = [];
    if ($q !== '' && strlen($q) >= 2) {
        $stmt = $pdo->prepare("SELECT id_product, namaproduct, image, price FROM product WHERE status = 'published' AND LOWER(namaproduct) LIKE ?");
        $stmt->execute(['%' . $q . '%']);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search: <?= htmlspecialchars($q) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-pink-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-8">Products</h1>
        <?php if (empty($products)): ?>
            <div class="text-gray-500 bg-white rounded p-6">No products found.</div>
        <?php else: ?>
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <?php foreach ($products as $item): ?>
                <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition flex flex-col relative">
                    <div class="absolute left-4 top-4 bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-semibold">On sale</div>
                    <img src="../assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['namaproduct']) ?>"
                        class="w-32 h-32 object-contain mx-auto mb-4 mt-8" />
                    <div class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($item['namaproduct']) ?></div>
                    <div class="text-pink-500 font-bold text-lg mb-3">Rp<?= number_format($item['price'], 0, ',', '.') ?></div>
                    <a href="product.php?id=<?= $item['id_product'] ?>" class="mt-auto inline-block text-center rounded-lg bg-pink-500 text-white py-2 px-4 font-semibold hover:bg-pink-600 transition">View Product</a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
