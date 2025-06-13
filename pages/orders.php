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

// Ambil daftar pesanan (selain status aktif/keranjang)
$stmt = $conn->prepare("
    SELECT c.id, c.order_status, c.created_at
    FROM cart c
    WHERE c.user_id = ? AND c.status != 'active'
    ORDER BY c.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Untuk setiap pesanan, ambil item produk & hitung total sesuai diskon
$orderData = [];
foreach ($orders as $order) {
    $stmtItems = $conn->prepare("
        SELECT ci.qty, ci.price, p.discount, p.namaproduct
        FROM cart_item ci
        JOIN product p ON ci.product_id = p.id_product
        WHERE ci.cart_id = ?
    ");
    $stmtItems->execute([$order['id']]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    $product_names = [];
    foreach ($items as $item) {
        $priceAfterDiscount = $item['price'] * (1 - ($item['discount'] / 100));
        $total += $item['qty'] * $priceAfterDiscount;
        $product_names[] = $item['namaproduct'];
    }
    $orderData[] = [
        'id' => $order['id'],
        'order_status' => $order['order_status'],
        'created_at' => $order['created_at'],
        'total' => $total,
        'product_names' => implode(', ', $product_names)
    ];
}
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

        <?php if (empty($orderData)): ?>
            <p class="text-gray-500">You have no orders yet.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($orderData as $order): ?>
                    <div class="bg-white rounded-lg shadow-md p-4 transition hover:shadow-lg">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Order ID: #<?= htmlspecialchars($order['id']) ?></p>
                                <p class="text-lg text-gray-800 font-semibold">
                                    Total: Rp<?= number_format($order['total'], 0, ',', '.') ?>
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Date: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?>
                                </p>
                            </div>
                            <a href="detail_order.php?order_id=<?= $order['id'] ?>"
                               class="bg-pink-500 text-white text-sm font-bold py-2 px-4 rounded-full hover:bg-pink-600 transition-colors flex-shrink-0">
                                Details
                            </a>
                        </div>
                        <div class="border-t pt-3">
                            <p class="text-xs text-gray-500">
                                Items: <span class="text-gray-700"><?= htmlspecialchars($order['product_names']) ?></span>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../pages/footer.php'; ?>
</body>
</html>
