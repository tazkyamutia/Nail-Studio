<?php
require_once '../configdb.php';

if (!isset($_GET['id'])) {
    echo "<p class='text-red-500'>Order ID is missing.</p>";
    exit;
}

$order_id = $_GET['id'];

// Ambil detail order
$stmt = $conn->prepare("SELECT c.id, u.username, c.order_status, c.created_at, SUM(ci.qty * ci.price) as total, b.file_path
                        FROM cart c
                        JOIN user u ON u.id = c.user_id
                        JOIN cart_item ci ON c.id = ci.cart_id
                        LEFT JOIN bukti_bayar b ON b.cart_id = c.id
                        WHERE c.id = ?
                        GROUP BY c.id, u.username, c.order_status, c.created_at, b.file_path");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<p class='text-red-500'>Order not found.</p>";
    exit;
}

// Ambil item detail
$itemsStmt = $conn->prepare("SELECT p.namaproduct, ci.qty, ci.price
                            FROM cart_item ci
                            JOIN product p ON p.id_product = ci.product_id
                            WHERE ci.cart_id = ?");
$itemsStmt->execute([$order_id]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Detail #<?= $order['id'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-pink-700">Order Detail #<?= $order['id'] ?></h1>

        <div class="bg-white p-4 rounded shadow mb-6">
            <p><strong>User:</strong> <?= htmlspecialchars($order['username']) ?></p>
            <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Status:</strong> <?= $order['order_status'] ?></p>
            <p><strong>Total:</strong> Rp<?= number_format($order['total'], 0, ',', '.') ?></p>
            <p><strong>Proof:</strong>
                <?php if ($order['file_path']): ?>
                    <a href="../uploads/<?= $order['file_path'] ?>" target="_blank" class="text-blue-600 underline">View</a>
                <?php else: ?>
                    <span class="text-gray-500">Not uploaded</span>
                <?php endif; ?>
            </p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Items</h2>
            <table class="w-full text-sm border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">Product</th>
                        <th class="border p-2">Qty</th>
                        <th class="border p-2">Price</th>
                        <th class="border p-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="border p-2"><?= htmlspecialchars($item['namaproduct']) ?></td>
                        <td class="border p-2 text-center"><?= $item['qty'] ?></td>
                        <td class="border p-2 text-right">Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td class="border p-2 text-right">Rp<?= number_format($item['qty'] * $item['price'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
