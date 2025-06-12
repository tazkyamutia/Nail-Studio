<?php
session_start();
include '../views/headers.php';
require_once '../configdb.php';

// Autentikasi: Pastikan pengguna sudah login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// Ambil riwayat pesanan DAN nama produk yang dibeli untuk setiap pesanan
$stmt = $conn->prepare("
    SELECT 
        c.id, 
        c.order_status, 
        c.created_at, 
        SUM(ci.qty * ci.price) as total,
        GROUP_CONCAT(p.namaproduct SEPARATOR ', ') as product_names
    FROM cart c 
    JOIN cart_item ci ON c.id = ci.cart_id
    JOIN product p ON ci.product_id = p.id_product
    WHERE c.user_id = ? AND c.status != 'active'
    GROUP BY c.id, c.order_status, c.created_at
    ORDER BY c.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto pt-20 pb-20 px-4">
        <h1 class="text-2xl font-bold mb-6 text-pink-700">Order History</h1>

        <?php if (empty($orders)): ?>
            <p class="text-gray-500">You have no orders yet.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-lg shadow-md p-4 transition hover:shadow-lg">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Order ID: #<?= htmlspecialchars($order['id']) ?></p>
                                <p class="text-lg text-gray-800 font-semibold">Total: Rp<?= number_format($order['total'], 0, ',', '.') ?></p>
                                <p class="text-sm text-gray-500 mt-1">Date: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                            </div>
                            <a href="detail_order.php?order_id=<?= $order['id'] ?>" class="bg-pink-500 text-white text-sm font-bold py-2 px-4 rounded-full hover:bg-pink-600 transition-colors flex-shrink-0">
                                Details
                            </a>
                        </div>
                        <div class="border-t pt-3">
                            <p class="text-xs text-gray-500">Items: <span class="text-gray-700"><?= htmlspecialchars($order['product_names']) ?></span></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../pages/footer.php'; ?>
</body>
</html>