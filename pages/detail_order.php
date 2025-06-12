<?php
session_start();
include '../views/headers.php'; 
require_once '../configdb.php';

// 1. Autentikasi dan Validasi Input
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "Invalid Order ID.";
    exit;
}

$user_id = $_SESSION['id'];
$order_id = $_GET['order_id'];

// 2. Ambil Detail Utama Pesanan
$stmt = $conn->prepare("
    SELECT c.id AS order_id, c.created_at AS order_date, c.order_status, u.fullname, a.address AS shipping_address
    FROM cart c
    JOIN user u ON c.user_id = u.id
    LEFT JOIN address a ON u.id = a.user_id AND a.type = 'shipping'
    WHERE c.id = ? AND c.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found or you do not have permission to view it.";
    exit;
}

// 3. Ambil Semua Item dalam Pesanan
$stmt_items = $conn->prepare("
    SELECT p.namaproduct, p.image, ci.qty, ci.price 
    FROM cart_item ci
    JOIN product p ON ci.product_id = p.id_product
    WHERE ci.cart_id = ?
");
$stmt_items->execute([$order_id]);
$purchased_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// 4. Hitung Total Harga
$total_price = 0;
foreach ($purchased_items as $item) {
    $total_price += $item['qty'] * $item['price'];
}

// 5. Logika untuk Checklist Status
$status = $order['order_status'];
$isPacked = in_array($status, ['Processing', 'Shipped', 'Completed']);
$isInProgress = in_array($status, ['Shipped', 'Completed']);
$isComplete = ($status === 'Completed');

$status_map = ['Processing' => 'Being packed', 'Shipped' => 'In Progress', 'Completed' => 'Complete'];
$current_status_text = $status_map[$status] ?? 'Unknown';

$payment_method = "QRIS";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-4xl py-20 px-4">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">

            <div class="mb-6"><h1 class="text-3xl font-bold text-gray-800">Order Details</h1><p class="text-gray-500">Track your order status and details below.</p></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-pink-700 mb-2">Shipping Information</h3>
                    <p class="font-bold text-gray-700"><?= htmlspecialchars($order['fullname']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['shipping_address'] ?? 'No address on file') ?></p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-gray-500"><strong>Order ID:</strong> #<?= htmlspecialchars($order['order_id']) ?></p>
                    <p class="text-gray-500"><strong>Order Date:</strong> <?= date('d M Y', strtotime($order['order_date'])) ?></p>
                    <p class="text-gray-500"><strong>Payment Method:</strong> <?= htmlspecialchars($payment_method) ?></p>
                    <p class="text-xl font-bold text-gray-800 mt-2">Total: Rp<?= number_format($total_price, 0, ',', '.') ?></p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-pink-700 mb-4">Order Status</h3>
                <div class="flex items-center justify-between w-full">
                    <div class="flex-1 text-center"><div class="mx-auto h-12 w-12 rounded-full flex items-center justify-center <?= $isPacked ? 'bg-green-500' : 'bg-gray-300' ?>"><i class="fas fa-box-open text-white text-xl"></i></div><p class="mt-2 text-sm font-medium <?= $isPacked ? 'text-green-600' : 'text-gray-500' ?>">Being packed</p></div>
                    <div class="flex-1 h-1 <?= $isInProgress ? 'bg-green-500' : 'bg-gray-300' ?>"></div>
                    <div class="flex-1 text-center"><div class="mx-auto h-12 w-12 rounded-full flex items-center justify-center <?= $isInProgress ? 'bg-green-500' : 'bg-gray-300' ?>"><i class="fas fa-truck text-white text-xl"></i></div><p class="mt-2 text-sm font-medium <?= $isInProgress ? 'text-green-600' : 'text-gray-500' ?>">In Progress</p></div>
                    <div class="flex-1 h-1 <?= $isComplete ? 'bg-green-500' : 'bg-gray-300' ?>"></div>
                    <div class="flex-1 text-center"><div class="mx-auto h-12 w-12 rounded-full flex items-center justify-center <?= $isComplete ? 'bg-green-500' : 'bg-gray-300' ?>"><i class="fas fa-check-circle text-white text-xl"></i></div><p class="mt-2 text-sm font-medium <?= $isComplete ? 'text-green-600' : 'text-gray-500' ?>">Complete</p></div>
                </div>
                <p class="text-center mt-4 text-gray-600">Current status: <span class="font-bold text-gray-800"><?= htmlspecialchars($current_status_text) ?></span></p>
            </div>
            
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-pink-700 mb-4">Items Ordered</h3>
                <div class="space-y-4">
                    <?php foreach($purchased_items as $item): ?>
                        <div class="flex items-center space-x-4 p-2 rounded-lg bg-gray-50">
                            <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['namaproduct']) ?>" class="w-16 h-16 rounded-md object-cover border">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($item['namaproduct']) ?></p>
                                <p class="text-sm text-gray-500">Quantity: <?= htmlspecialchars($item['qty']) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-700 font-semibold">Rp<?= number_format($item['qty'] * $item['price'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-6 border-t">
                <a href="products.php" class="w-full md:w-auto text-center py-2 px-5 mb-2 md:mb-0 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition">Continue Shopping</a>
                <button onClick="window.location.reload()" class="w-full md:w-auto text-center py-2 px-5 rounded-full bg-pink-500 text-white font-semibold hover:bg-pink-600 transition">Refresh Status</button>
            </div>
        </div>
    </div>

    <?php include '../pages/footer.php'; ?>
</body>
</html>