<?php
// Database configuration
$host = 'localhost';
$dbname = 'order_tracking';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Sample data - In real application, this would come from database
$orders = [
    37 => [
        'id' => 37,
        'order_id' => 'ORD-2025-037',
        'customer' => 'John Doe',
        'total' => 94000,
        'status' => 'processing',
        'date' => '2025-06-11 08:25:00',
        'payment' => 'Credit Card',
        'address' => 'Jl. Sudirman, Jakarta',
        'items' => [
            ['name' => 'Premium T-Shirt', 'qty' => 2, 'price' => 47000]
        ]
    ],
    36 => [
        'id' => 36,
        'order_id' => 'ORD-2025-036',
        'customer' => 'Jane Smith',
        'total' => 40000,
        'status' => 'processing',
        'date' => '2025-06-11 00:37:00',
        'payment' => 'Bank Transfer',
        'address' => 'Jl. Thamrin, Jakarta',
        'items' => [
            ['name' => 'Cotton Shirt', 'qty' => 1, 'price' => 40000]
        ]
    ],
    35 => [
        'id' => 35,
        'order_id' => 'ORD-2025-035',
        'customer' => 'Mike Johnson',
        'total' => 174000,
        'status' => 'shipped',
        'date' => '2025-06-11 00:34:00',
        'payment' => 'E-Wallet',
        'address' => 'Jl. Gatot Subroto, Jakarta',
        'items' => [
            ['name' => 'Designer Hoodie', 'qty' => 1, 'price' => 120000],
            ['name' => 'Casual Pants', 'qty' => 1, 'price' => 54000]
        ]
    ]
];

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'get_order':
            $order_id = (int)$_GET['id'];
            if (isset($orders[$order_id])) {
                echo json_encode(['success' => true, 'order' => $orders[$order_id]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
            }
            exit;
            
        case 'refresh_status':
            $order_id = (int)$_GET['id'];
            if (isset($orders[$order_id])) {
                // Simulate status update
                $statuses = ['confirmed', 'processing', 'shipped', 'delivered'];
                $current_status = $orders[$order_id]['status'];
                $current_index = array_search($current_status, $statuses);
                
                // Randomly advance status (for demo)
                if ($current_index < count($statuses) - 1 && rand(1, 3) == 1) {
                    $orders[$order_id]['status'] = $statuses[$current_index + 1];
                }
                
                echo json_encode(['success' => true, 'status' => $orders[$order_id]['status']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
            }
            exit;
    }
}

function formatCurrency($amount) {
    return 'Rp' . number_format($amount, 0, ',', '.');
}

function formatDate($date) {
    return date('d M Y H:i', strtotime($date));
}

function getStatusClass($status) {
    switch ($status) {
        case 'confirmed': return 'status-confirmed';
        case 'processing': return 'status-processing';
        case 'shipped': return 'status-shipped';
        case 'delivered': return 'status-delivered';
        default: return 'status-processing';
    }
}

function getStatusText($status) {
    switch ($status) {
        case 'confirmed': return 'Confirmed';
        case 'processing': return 'Processing';
        case 'shipped': return 'Shipped';
        case 'delivered': return 'Delivered';
        default: return 'Processing';
    }
}

function getProgressSteps($status) {
    $steps = ['confirmed', 'processing', 'shipped', 'delivered'];
    $current_index = array_search($status, $steps);
    
    $progress = [];
    foreach ($steps as $index => $step) {
        if ($index < $current_index) {
            $progress[$step] = 'completed';
        } elseif ($index == $current_index) {
            $progress[$step] = 'current';
        } else {
            $progress[$step] = 'pending';
        }
    }
    
    return $progress;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Pink Theme</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 50%, #fdf2f8 100%);
            min-height: 100vh;
            padding: 1rem;
        }
        
        .container {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(236, 72, 153, 0.2);
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.15);
        }
        
        .header h1 {
            background: linear-gradient(135deg, #ec4899, #be185d, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            color: #be185d;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(236, 72, 153, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(236, 72, 153, 0.15), 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #f97316, #be185d);
            border-radius: 1.5rem 1.5rem 0 0;
        }
        
        .card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.25), 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .order-item {
            padding: 2rem;
            position: relative;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .order-info h3 {
            color: #831843;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .order-id {
            color: #be185d;
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.8;
        }
        
        .order-total {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .status-badge {
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .status-confirmed {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }
        
        .status-processing {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }
        
        .status-shipped {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .order-details {
            border-top: 2px solid rgba(236, 72, 153, 0.1);
            padding-top: 1.5rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .detail-label {
            color: #be185d;
            font-weight: 600;
        }
        
        .detail-value {
            color: #831843;
            font-weight: 700;
        }
        
        .customer-name {
            background: linear-gradient(135deg, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }
        
        /* Status Detail Page */
        .status-page {
            padding: 2.5rem 2rem;
        }
        
        .status-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #ec4899, #f97316, #be185d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
        }
        
        .order-summary {
            background: linear-gradient(135deg, #fdf2f8, #fce7f3);
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.1);
        }
        
        .progress-container {
            margin: 3rem 0;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 1rem;
        }
        
        .progress-line {
            position: absolute;
            top: 1.5rem;
            left: 2rem;
            right: 2rem;
            height: 4px;
            background: linear-gradient(90deg, #fce7f3, #f3e8ff);
            border-radius: 2px;
            z-index: 1;
        }
        
        .progress-line.active {
            background: linear-gradient(90deg, #ec4899, #f97316);
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-circle {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: all 0.4s ease;
            border: 3px solid transparent;
        }
        
        .step-circle.completed {
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: white;
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }
        
        .step-circle.pending {
            background: #fce7f3;
            color: #be185d;
            border-color: #f3e8ff;
        }
        
        .step-circle.current {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { 
                box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 8px 30px rgba(249, 115, 22, 0.7);
                transform: scale(1.05);
            }
            100% { 
                box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
                transform: scale(1);
            }
        }
        
        .step-label {
            font-size: 0.9rem;
            text-align: center;
            font-weight: 700;
            max-width: 4rem;
        }
        
        .step-label.completed {
            color: #ec4899;
        }
        
        .step-label.current {
            color: #f97316;
        }
        
        .step-label.pending {
            color: #be185d;
            opacity: 0.6;
        }
        
        .current-status {
            text-align: center;
            margin: 2.5rem 0;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(249, 115, 22, 0.1));
            border-radius: 1.5rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.1);
        }
        
        .status-text {
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn {
            flex: 1;
            padding: 1.25rem 2rem;
            border-radius: 1rem;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.4);
        }
        
        .icon {
            width: 1.5rem;
            height: 1.5rem;
        }
        
        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(30px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }
        
        .order-items {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid rgba(236, 72, 153, 0.1);
        }
        
        .order-items h4 {
            color: #831843;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
        }
        
        .item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: 600;
            color: #831843;
            font-size: 1.1rem;
        }
        
        .item-details {
            font-size: 0.9rem;
            color: #be185d;
            font-weight: 500;
        }
        
        .item-price {
            font-weight: 700;
            background: linear-gradient(135deg, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header fade-in">
            <h1>🌸 Order Tracking</h1>
            <p>Track your orders in real-time with style</p>
        </div>

        <!-- Order History View -->
        <div id="orderHistory">
            <?php foreach ($orders as $order): ?>
            <div class="card fade-in">
                <div class="order-item">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>Order #<?php echo $order['id']; ?></h3>
                            <p class="order-id">ID: <?php echo $order['order_id']; ?></p>
                        </div>
                        <div class="order-total"><?php echo formatCurrency($order['total']); ?></div>
                    </div>
                    <a href="#" onclick="showOrderDetail(<?php echo $order['id']; ?>)" 
                       class="status-badge <?php echo getStatusClass($order['status']); ?>">
                        <?php echo getStatusText($order['status']); ?>
                    </a>
                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Customer:</span>
                            <span class="detail-value customer-name"><?php echo htmlspecialchars($order['customer']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Date:</span>
                            <span class="detail-value"><?php echo formatDate($order['date']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment:</span>
                            <span class="detail-value"><?php echo $order['payment']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Order Detail View -->
        <div id="orderDetail" style="display: none;">
            <div class="card fade-in">
                <div class="status-page">
                    <h2 class="status-title">Order Status</h2>
                    
                    <div class="order-summary" id="orderSummary">
                        <!-- Content will be loaded dynamically -->
                    </div>

                    <div class="progress-container">
                        <div class="progress-steps" id="progressSteps">
                            <!-- Progress will be loaded dynamically -->
                        </div>
                    </div>

                    <div class="current-status">
                        <div style="margin-bottom: 0.5rem; color: #be185d; font-weight: 600;">Current Status</div>
                        <div class="status-text" id="currentStatusText">Loading...</div>
                    </div>

                    <div class="action-buttons">
                        <button onclick="showOrderHistory()" class="btn btn-primary">← Back to Orders</button>
                        <button onclick="refreshStatus()" class="btn btn-secondary" id="refreshBtn">🔄 Refresh</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentOrderId = null;
        
        function showOrderDetail(orderId) {
            currentOrderId = orderId;
            document.getElementById('orderHistory').style.display = 'none';
            document.getElementById('orderDetail').style.display = 'block';
            
            // Load order details via AJAX
            fetch(`?action=get_order&id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadOrderDetails(data.order);
                    } else {
                        alert('Error loading order details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading order details');
                });
            
            // Add fade-in animation
            const detailElement = document.getElementById('orderDetail');
            detailElement.classList.remove('fade-in');
            setTimeout(() => {
                detailElement.classList.add('fade-in');
            }, 10);
        }

        function loadOrderDetails(order) {
            // Load order summary
            const summaryHtml = `
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value">#${order.id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Customer:</span>
                    <span class="detail-value customer-name">${order.customer}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">${order.address}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">${formatDate(order.date)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total:</span>
                    <span class="detail-value" style="font-size: 1.2rem; background: linear-gradient(135deg, #ec4899, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 800;">${formatCurrency(order.total)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment:</span>
                    <span class="detail-value">${order.payment}</span>
                </div>
                
                <div class="order-items">
                    <h4>Order Items</h4>
                    ${order.items.map(item => `
                        <div class="item">
                            <div>
                                <div class="item-name">${item.name}</div>
                                <div class="item-details">Qty: ${item.qty}</div>
                            </div>
                            <div class="item-price">${formatCurrency(item.price)}</div>
                        </div>
                    `).join('')}
                </div>
            `;
            
            document.getElementById('orderSummary').innerHTML = summaryHtml;
            
            // Load progress steps
            loadProgressSteps(order.status);
            
            // Update current status text
            updateStatusText(order.status);
        }

        function loadProgressSteps(status) {
            const steps = ['confirmed', 'processing', 'shipped', 'delivered'];
            const stepLabels = ['Confirmed', 'Processing', 'Shipped', 'Delivered'];
            const stepIcons = [
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>'
            ];
            
            const currentIndex = steps.indexOf(status);
            
            let stepsHtml = `<div class="progress-line ${currentIndex >= 0 ? 'active' : ''}"></div>`;
            
            steps.forEach((step, index) => {
                let stepClass = 'pending';
                if (index < currentIndex) {
                    stepClass = 'completed';
                } else if (index === currentIndex) {
                    stepClass = 'current';
                }
                
                stepsHtml += `
                    <div class="step">
                        <div class="step-circle ${stepClass}">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${stepIcons[index]}
                            </svg>
                        </div>
                        <span class="step-label ${stepClass}">${stepLabels[index]}</span>
                    </div>
                `;
            });
            
            document.getElementById('progressSteps').innerHTML = stepsHtml;
        }

        function updateStatusText(status) {
            const statusTexts = {
                'confirmed': 'Order Confirmed - Preparing for Processing',
                'processing': 'Payment Confirmed - Processing Order',
                'shipped': 'Order Shipped - On the Way to You',
                'delivered': 'Order Delivered - Thank You!'
            };
            
            document.getElementById('currentStatusText').textContent = statusTexts[status] || 'Processing Order';
        }

        function showOrderHistory() {
            document.getElementById('orderDetail').style.display = 'none';
            document.getElementById('orderHistory').style.display = 'block';
            
            // Add fade-in animation
            const historyElement = document.getElementById('orderHistory');
            historyElement.classList.remove('fade-in');
            setTimeout(() => {
                historyElement.classList.add('fade-in');
            }, 10);
        }

        function refreshStatus() {
            if (!currentOrderId) return;
            
            const refreshBtn = document.getElementById('refreshBtn');
            const originalText = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '⏳ Refreshing...';
            refreshBtn.disabled = true;
            refreshBtn.classList.add('loading');
            
            // Make AJAX call to refresh status
            fetch(`?action=refresh_status&id=${currentOrderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload order details with new status
                        return fetch(`?action=get_order&id=${currentOrderId}`);
                    } else {
                        throw new Error(data.message || 'Failed to refresh status');
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadOrderDetails(data.order);
                        
                        // Show success feedback
                        refreshBtn.innerHTML = '✅ Updated!';
                        refreshBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                        
                        setTimeout(() => {
                            refreshBtn.innerHTML = originalText;
                            refreshBtn.style.background = 'linear-gradient(135deg, #f97316, #ea580c)';
                            refreshBtn.disabled = false;
                            refreshBtn.classList.remove('loading');
                        }, 2000);
                    } else {
                        throw new Error('Failed to load updated order details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    refreshBtn.innerHTML = '❌ Error';
                    setTimeout(() => {
                        refreshBtn.innerHTML = originalText;
                        refreshBtn.disabled = false;
                        refreshBtn.classList.remove('loading');
                    }, 2000);
                });
        }

        // Utility functions
        function formatCurrency(amount) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(amount);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            };
            return date.toLocaleDateString('en-GB', options);
        }

        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add stagger animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
            
            // Add click ripple effect
            document.querySelectorAll('.btn, .status-badge').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add hover effects to cards
            document.querySelectorAll('.card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
            
            // Auto-refresh every 30 seconds when viewing order details
            setInterval(() => {
                if (currentOrderId && document.getElementById('orderDetail').style.display !== 'none') {
                    refreshStatus();
                }
            }, 30000);
        });
    </script>
</body>
</html>