<?php
session_start();
include '../views/headers.php'; 
require_once '../configdb.php';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Validate order ID
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "Invalid Order ID.";
    exit;
}

$user_id = $_SESSION['id'];
$order_id = $_GET['order_id'];

// Update order status to 'Processing' after payment success
$stmt = $conn->prepare("UPDATE cart SET order_status = 'Processing' WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);

// Check if the order status was updated successfully
if ($stmt->rowCount() > 0) {
    $status_update_message = "Your order is now being processed.";
} else {
    $status_update_message = "There was an issue updating your order status.";
}

// Fetch order details
$stmt = $conn->prepare("
    SELECT c.id AS order_id, c.created_at AS order_date, c.order_status, u.fullname, a.address AS shipping_address
    FROM cart c
    JOIN user u ON c.user_id = u.id
    LEFT JOIN address a ON c.user_id = a.user_id AND a.type = 'shipping'
    WHERE c.id = ? AND c.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found or you do not have permission to view it.";
    exit;
}

// Fetch all purchased items
$stmt_items = $conn->prepare("
    SELECT p.namaproduct, p.image, ci.qty, ci.price, p.discount 
    FROM cart_item ci
    JOIN product p ON ci.product_id = p.id_product
    WHERE ci.cart_id = ?
");
$stmt_items->execute([$order_id]);
$purchased_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// Calculate total price before and after discount
$total_price_before_discount = 0;
$total_price_after_discount = 0;
$total_discount = 0;

foreach ($purchased_items as $item) {
    $productPrice = $item['price'];
    $productDiscount = $item['discount'];
    $priceAfterDiscount = $productPrice * (1 - $productDiscount / 100);
    
    $total_price_before_discount += $item['qty'] * $productPrice;
    $total_price_after_discount += $item['qty'] * $priceAfterDiscount;
    $total_discount += $item['qty'] * $productPrice * $productDiscount / 100;  // Total discount for this item
}

// Order status logic
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="../css/detailorder.css" rel="stylesheet">
</head>
<body class="bg-gray-100" style="font-family: 'Poppins', sans-serif;">

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
                    <p class="text-gray-500">Order ID: #<?= htmlspecialchars($order['order_id']) ?></p>
                    <p class="text-gray-500">Order Date: <?= date('d M Y', strtotime($order['order_date'])) ?></p>
                    <p class="text-gray-500">Payment Method: <?= htmlspecialchars($payment_method) ?></p>
                    <p class="text-sm text-gray-600 line-through mt-2">Total Pesanan : Rp<?= number_format($total_price_before_discount, 0, ',', '.') ?></p>
                    <p class="text-sm text-gray-600 mt-2">Total Discount: Rp<?= number_format($total_discount, 0, ',', '.') ?></p>
                     <p class="text-xl font-bold text-gray-800 mt-2">Total Pembayaran: Rp<?= number_format($total_price_after_discount, 0, ',', '.') ?></p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-pink-700 mb-6">Order Status</h3>
                <div class="flex items-start justify-between">
                    <div class="flex-1 text-center">
                        <div id="statusIcon1" class="status-icon mx-auto h-12 w-12 rounded-full flex items-center justify-center"><i class="fas fa-box-open text-white text-xl"></i></div>
                        <p class="mt-2 text-sm font-medium">Being packed</p>
                    </div>
                    <div class="flex-1 px-2 pt-5"><div class="progress-line"><div id="progressBarFill1" class="progress-line-fill"></div></div></div>
                    <div class="flex-1 text-center">
                        <div id="statusIcon2" class="status-icon mx-auto h-12 w-12 rounded-full flex items-center justify-center"><i class="fas fa-truck text-white text-xl"></i></div>
                        <p class="mt-2 text-sm font-medium">In Progress</p>
                    </div>
                    <div class="flex-1 px-2 pt-5"><div class="progress-line"><div id="progressBarFill2" class="progress-line-fill"></div></div></div>
                    <div class="flex-1 text-center">
                        <div id="statusIcon3" class="status-icon mx-auto h-12 w-12 rounded-full flex items-center justify-center"><i class="fas fa-check-circle text-white text-xl"></i></div>
                        <p class="mt-2 text-sm font-medium">Complete</p>
                    </div>
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
                                <?php
                                $productPrice = $item['price'];
                                $productDiscount = $item['discount'];
                                $priceAfterDiscount = $productPrice * (1 - $productDiscount / 100);
                                $priceFormatted = number_format($productPrice, 0, ',', '.');
                                $priceAfterDiscountFormatted = number_format($priceAfterDiscount, 0, ',', '.');
                                ?>
                                <?php if ($productDiscount > 0): ?>
                                    <p class="text-gray-700 font-semibold line-through">Rp<?= $priceFormatted ?></p>
                                    <p class="text-green-700 font-semibold">Rp<?= $priceAfterDiscountFormatted ?></p>
                                <?php else: ?>
                                    <p class="text-gray-700 font-semibold">Rp<?= $priceFormatted ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-6 border-t">
                <a href="index.php" class="w-full md:w-auto text-center py-2 px-5 mb-2 md:mb-0 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition">Continue Shopping</a>
                <button onClick="window.location.reload()" class="w-full md:w-auto text-center py-2 px-5 rounded-full bg-pink-500 text-white font-semibold hover:bg-pink-600 transition">Refresh Status</button>
            </div>
        </div>
    </div>

    <?php include '../pages/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isPacked = <?= json_encode($isPacked) ?>;
            const isInProgress = <?= json_encode($isInProgress) ?>;
            const isComplete = <?= json_encode($isComplete) ?>;

            const icon1 = document.getElementById('statusIcon1');
            const icon2 = document.getElementById('statusIcon2');
            const icon3 = document.getElementById('statusIcon3');
            const progressBarFill1 = document.getElementById('progressBarFill1');
            const progressBarFill2 = document.getElementById('progressBarFill2');

            function activateStatus() {
                setTimeout(() => {
                    if (isPacked) icon1.classList.add('active');
                }, 100);

                setTimeout(() => {
                    if (isInProgress) {
                        progressBarFill1.style.width = '100%';
                        setTimeout(() => icon2.classList.add('active'), 300);
                    }
                }, 600); 

                setTimeout(() => {
                    if (isComplete) {
                        progressBarFill2.style.width = '100%';
                        setTimeout(() => icon3.classList.add('active'), 300);
                    }
                }, 1100);
            }

            activateStatus();
        });
    </script>
</body>
</html>
