<?php
// Include database configuration (asumsikan file configdb.php ada di folder utama)
require_once '../configdb.php';

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

// Status functions
function getStatusSteps($current_status) {
    $steps = [
        ['key' => 'confirmed', 'label' => 'Order Confirmed', 'completed' => true],
        ['key' => 'processing', 'label' => 'Processing', 'completed' => in_array($current_status, ['processing', 'shipped', 'delivered'])],
        ['key' => 'shipped', 'label' => 'Shipped', 'completed' => in_array($current_status, ['shipped', 'delivered'])],
        ['key' => 'delivered', 'label' => 'Delivered', 'completed' => $current_status === 'delivered']
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
        case 'shipped': return '#3b82f6';   // blue
        case 'processing': return '#f59e0b'; // orange
        case 'cancelled': return '#ef4444'; // red
        case 'pending': return '#6b7280';   // gray
        default: return '#ec4899';          // pink
    }
}
?>
