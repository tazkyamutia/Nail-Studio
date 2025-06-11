<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get order ID from URL parameter
$selected_order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;
$selected_order = null;

// Function to get orders from database
function getOrders($pdo, $limit = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                s.id,
                s.total_amount as total,
                DATE_FORMAT(s.created_at, '%d %b %Y %H:%i') as date,
                s.status,
                u.name as customer,
                a.street_address as address,
                s.created_at as order_date,
                s.payment_method
            FROM sales s
            LEFT JOIN user u ON s.user_id = u.id
            LEFT JOIN address a ON s.address_id = a.id
            ORDER BY s.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = [
                'id' => $row['id'],
                'total' => 'Rp' . number_format($row['total'], 0, ',', '.'),
                'date' => $row['date'],
                'status' => $row['status'],
                'customer' => $row['customer'],
                'address' => $row['address'],
                'order_date' => $row['order_date'],
                'payment_method' => $row['payment_method']
            ];
        }
        
        return $orders;
    } catch(PDOException $e) {
        error_log("Error fetching orders: " . $e->getMessage());
        return [];
    }
}

// Function to get single order details
function getOrderById($pdo, $order_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                s.id,
                s.total_amount as total,
                DATE_FORMAT(s.created_at, '%d %b %Y %H:%i') as date,
                s.status,
                u.name as customer,
                CONCAT(a.street_address, ', ', a.city) as address,
                s.created_at as order_date,
                s.payment_method,
                s.notes
            FROM sales s
            LEFT JOIN user u ON s.user_id = u.id
            LEFT JOIN address a ON s.address_id = a.id
            WHERE s.id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return [
                'id' => $row['id'],
                'total' => 'Rp' . number_format($row['total'], 0, ',', '.'),
                'date' => $row['date'],
                'status' => $row['status'],
                'customer' => $row['customer'],
                'address' => $row['address'],
                'order_date' => $row['order_date'],
                'payment_method' => $row['payment_method'],
                'notes' => $row['notes']
            ];
        }
        
        return null;
    } catch(PDOException $e) {
        error_log("Error fetching order: " . $e->getMessage());
        return null;
    }
}

// Function to get order items
function getOrderItems($pdo, $order_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                ci.quantity,
                ci.price,
                p.name as product_name,
                p.image_url
            FROM cart_item ci
            LEFT JOIN product p ON ci.product_id = p.id
            WHERE ci.cart_id IN (
                SELECT cart_id FROM sales WHERE id = :order_id
            )
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching order items: " . $e->getMessage());
        return [];
    }
}

// Get orders data
$orders = getOrders($pdo);

// Get selected order if order_id is provided
if ($selected_order_id) {
    $selected_order = getOrderById($pdo, $selected_order_id);
    if ($selected_order) {
        $order_items = getOrderItems($pdo, $selected_order_id);
    }
}

function getStatusSteps($current_status) {
    $steps = [
        [
            'key' => 'confirmed',
            'label' => 'Order Confirmed',
            'completed' => true
        ],
        [
            'key' => 'processing',
            'label' => 'Processing',
            'completed' => in_array($current_status, ['processing', 'shipped', 'delivered'])
        ],
        [
            'key' => 'shipped',
            'label' => 'Shipped',
            'completed' => in_array($current_status, ['shipped', 'delivered'])
        ],
        [
            'key' => 'delivered',
            'label' => 'Delivered',
            'completed' => $current_status === 'delivered'
        ]
    ];
    return $steps;
}

function getStatusText($status) {
    switch($status) {
        case 'processing': return 'Payment Confirmed';
        case 'shipped': return 'On Delivery';
        case 'delivered': return 'Delivered';
        case 'cancelled': return 'Cancelled';
        case 'pending': return 'Waiting Payment';
        default: return 'Processing';
    }
}

function getStatusColor($status) {
    switch($status) {
        case 'delivered': return '#10b981'; // green
        case 'shipped': return '#3b82f6'; // blue
        case 'processing': return '#f59e0b'; // orange
        case 'cancelled': return '#ef4444'; // red
        case 'pending': return '#6b7280'; // gray
        default: return '#ec4899'; // pink
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $selected_order ? 'Order Status' : 'Order History'; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
            min-height: 100vh;
            padding: 1rem;
        }
        
        .container {
            max-width: 28rem;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ec4899;
            margin-bottom: 1.5rem;
        }
        
        .order-item {
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        
        .order-id {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .order-total {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1f2937;
        }
        
        .status-btn {
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            color: white;
        }
        
        .status-btn:hover {
            opacity: 0.8;
        }
        
        .order-date {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .customer-info {
            color: #374151;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        /* Status page styles */
        .status-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 2rem;
        }
        
        .order-details {
            margin-bottom: 2rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            align-items: flex-start;
        }
        
        .detail-label {
            color: #6b7280;
            flex-shrink: 0;
            margin-right: 1rem;
        }
        
        .detail-value {
            font-weight: 600;
            text-align: right;
            flex-grow: 1;
        }
        
        .status-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .status-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        
        .step-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }
        
        .step-circle.completed {
            background-color: #ec4899;
            color: white;
        }
        
        .step-circle.pending {
            background-color: #d1d5db;
            color: #6b7280;
        }
        
        .step-label {
            font-size: 0.75rem;
            text-align: center;
            max-width: 5rem;
        }
        
        .step-label.completed {
            color: #ec4899;
            font-weight: 600;
        }
        
        .step-label.pending {
            color: #6b7280;
        }
        
        .progress-line {
            position: absolute;
            top: 1.5rem;
            left: 3rem;
            right: 3rem;
            height: 2px;
            background-color: #d1d5db;
            z-index: 1;
        }
        
        .progress-line.completed {
            background-color: #ec4899;
        }
        
        .current-status {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .current-status-text {
            color: #ec4899;
            font-weight: bold;
            font-size: 1.125rem;
        }
        
        .order-items {
            margin-bottom: 2rem;
        }
        
        .order-items h3 {
            margin-bottom: 1rem;
            color: #1f2937;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .item:last-child {
            border-bottom: none;
        }
        
        .item-info {
            flex-grow: 1;
        }
        
        .item-name {
            font-weight: 500;
            color: #1f2937;
        }
        
        .item-details {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }
        
        .btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }
        
        .btn-primary {
            background-color: #ec4899;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #db2777;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .checkmark, .clock {
            width: 1.5rem;
            height: 1.5rem;
        }
        
        .error-message {
            text-align: center;
            color: #ef4444;
            padding: 2rem;
        }
        
        .no-orders {
            text-align: center;
            color: #6b7280;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($selected_order): ?>
            <!-- Status Detail Page -->
            <div class="card">
                <h2 class="status-title">Order Status</h2>
                
                <div class="order-details">
                    <div class="detail-row">
                        <span class="detail-label">Order ID:</span>
                        <span class="detail-value">#<?php echo $selected_order['id']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Customer:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($selected_order['customer']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Delivery Address:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($selected_order['address']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Order Date:</span>
                        <span class="detail-value"><?php echo date('d M Y H:i', strtotime($selected_order['order_date'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Amount:</span>
                        <span class="detail-value"><?php echo $selected_order['total']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($selected_order['payment_method']); ?></span>
                    </div>
                </div>

                <?php if (isset($order_items) && !empty($order_items)): ?>
                <div class="order-items">
                    <h3>Order Items</h3>
                    <?php foreach ($order_items as $item): ?>
                    <div class="item">
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="item-details">
                                Qty: <?php echo $item['quantity']; ?> × 
                                Rp<?php echo number_format($item['price'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Status Progress -->
                <div class="status-progress">
                    <?php 
                    $steps = getStatusSteps($selected_order['status']);
                    foreach ($steps as $index => $step): 
                    ?>
                        <div class="status-step">
                            <div class="step-circle <?php echo $step['completed'] ? 'completed' : 'pending'; ?>">
                                <?php if ($step['completed']): ?>
                                    <svg class="checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="clock" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <span class="step-label <?php echo $step['completed'] ? 'completed' : 'pending'; ?>">
                                <?php echo $step['label']; ?>
                            </span>
                            <?php if ($index < count($steps) - 1): ?>
                                <div class="progress-line <?php echo $step['completed'] && $steps[$index + 1]['completed'] ? 'completed' : ''; ?>"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="current-status">
                    <span>Current Status: </span>
                    <span class="current-status-text"><?php echo getStatusText($selected_order['status']); ?></span>
                </div>

                <div class="action-buttons">
                    <a href="?" class="btn btn-primary">← Back to Orders</a>
                    <button onclick="location.reload()" class="btn btn-secondary">🔄 Refresh Status</button>
                </div>
            </div>
        <?php elseif ($selected_order_id && !$selected_order): ?>
            <!-- Order not found -->
            <div class="card">
                <div class="error-message">
                    <h2>Order Not Found</h2>
                    <p>Order ID #<?php echo $selected_order_id; ?> not found.</p>
                    <a href="?" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">← Back to Orders</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Order History Page -->
            <h1 class="title">Order History</h1>
            
            <?php if (empty($orders)): ?>
                <div class="card">
                    <div class="no-orders">
                        <h3>No Orders Found</h3>
                        <p>You don't have any orders yet.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-header">
                            <div>
                                <p class="order-id">Order ID: #<?php echo $order['id']; ?></p>
                                <p class="order-total"><?php echo $order['total']; ?></p>
                                <p class="customer-info">Customer: <?php echo htmlspecialchars($order['customer']); ?></p>
                            </div>
                            <a href="?order_id=<?php echo $order['id']; ?>" 
                               class="status-btn"
                               style="background-color: <?php echo getStatusColor($order['status']); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </a>
                        </div>
                        <p class="order-date">Date: <?php echo $order['date']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>