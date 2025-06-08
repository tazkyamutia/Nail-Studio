<?php
session_start();
include '../views/headers.php'; 
require_once '../configdb.php';

// 1. Autentikasi
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// 2. Cek apakah user sudah punya order, kalau belum tambahkan dummy order
$check = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND status = 'checked_out'");
$check->execute([$user_id]);
$orderExists = $check->fetchColumn();

if (!$orderExists) {
    // 3. Tambah order ke `cart`
    $stmtCart = $conn->prepare("INSERT INTO cart (user_id, status, order_status, created_at, updated_at)
                                VALUES (?, 'checked_out', 'Processing', NOW(), NOW())");
    $stmtCart->execute([$user_id]);
    $cart_id = $conn->lastInsertId();

    // 4. Tambahkan produk dummy ke `cart_item`
    $items = [
        ['product_id' => 34, 'qty' => 2, 'price' => 78000],
        ['product_id' => 40, 'qty' => 1, 'price' => 89000]
    ];

    $stmtItem = $conn->prepare("INSERT INTO cart_item (cart_id, product_id, qty, price, created_at, updated_at)
                                VALUES (?, ?, ?, ?, NOW(), NOW())");
    foreach ($items as $item) {
        $stmtItem->execute([$cart_id, $item['product_id'], $item['qty'], $item['price']]);
    }
}

// 5. Ambil order history user
$stmt = $conn->prepare("SELECT c.id, c.order_status, c.created_at, SUM(ci.qty * ci.price) as total
                        FROM cart c 
                        JOIN cart_item ci ON c.id = ci.cart_id 
                        WHERE c.user_id = ? AND c.status != 'active'
                        GROUP BY c.id, c.order_status, c.created_at
                        ORDER BY c.created_at DESC");
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
            <div class="space-y-6">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded shadow p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="text-sm text-gray-500">Order ID: <?= $order['id'] ?></p>
                                <p class="text-gray-700 font-semibold">Total: Rp<?= number_format($order['total'], 0, ',', '.') ?></p>
                            </div>
                            <span class="text-sm px-3 py-1 rounded-full 
                                <?= match($order['order_status']) {
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Processing' => 'bg-blue-100 text-blue-800',
                                    'Shipped' => 'bg-purple-100 text-purple-800',
                                    'Completed' => 'bg-green-100 text-green-800',
                                } ?>">
                                <?= $order['order_status'] ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">Date: <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../pages/footer.php'; ?>
</body>
</html>
