<?php
session_start();
require_once '../configdb.php';

header('Content-Type: application/json');

if (!isset($_POST['action'])) {
    echo json_encode(['success'=>false,'message'=>'No action']);
    exit;
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['success'=>false,'message'=>'Anda harus login']);
    exit;
}
$user_id = $_SESSION['id'];

// Cari cart aktif
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();
if (!$cart_id) {
    // Buat cart baru jika belum ada
    $stmt = $conn->prepare("INSERT INTO cart (user_id, status, created_at, updated_at) VALUES (?, 'active', NOW(), NOW())");
    $stmt->execute([$user_id]);
    $cart_id = $conn->lastInsertId();
}

$action = $_POST['action'];

if ($action === 'add') {
    $product_id = intval($_POST['product_id']);
    // Cek produk
    $stmt = $conn->prepare("SELECT price FROM product WHERE id_product = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo json_encode(['success'=>false,'message'=>'Product not found']);
        exit;
    }
    // Cek item
    $stmt = $conn->prepare("SELECT id FROM cart_item WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cart_id, $product_id]);
    $item_id = $stmt->fetchColumn();
    if ($item_id) {
        $stmt = $conn->prepare("UPDATE cart_item SET qty = qty + 1, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$item_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart_item (cart_id, product_id, qty, price, created_at, updated_at) VALUES (?, ?, 1, ?, NOW(), NOW())");
        $stmt->execute([$cart_id, $product_id, $product['price']]);
    }
    // Hitung total
    $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_count = (int)$stmt->fetchColumn();
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'plus') {
    $item_id = intval($_POST['cart_item_id']);
    $stmt = $conn->prepare("UPDATE cart_item SET qty = qty + 1, updated_at = NOW() WHERE id = ? AND cart_id = ?");
    $stmt->execute([$item_id, $cart_id]);
    // Hitung total
    $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_count = (int)$stmt->fetchColumn();
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'minus') {
    $item_id = intval($_POST['cart_item_id']);
    // Kurangi qty, hapus jika 0
    $stmt = $conn->prepare("SELECT qty FROM cart_item WHERE id = ? AND cart_id = ?");
    $stmt->execute([$item_id, $cart_id]);
    $qty = $stmt->fetchColumn();
    if ($qty > 1) {
        $stmt = $conn->prepare("UPDATE cart_item SET qty = qty - 1, updated_at = NOW() WHERE id = ? AND cart_id = ?");
        $stmt->execute([$item_id, $cart_id]);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart_item WHERE id = ? AND cart_id = ?");
        $stmt->execute([$item_id, $cart_id]);
    }
    $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_count = (int)$stmt->fetchColumn();
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'delete') {
    $item_id = intval($_POST['cart_item_id']);
    $stmt = $conn->prepare("DELETE FROM cart_item WHERE id = ? AND cart_id = ?");
    $stmt->execute([$item_id, $cart_id]);
    $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_count = (int)$stmt->fetchColumn();
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'update') {
    $item_id = intval($_POST['cart_item_id']);
    $qty = max(1, intval($_POST['qty']));
    $stmt = $conn->prepare("UPDATE cart_item SET qty = ?, updated_at = NOW() WHERE id = ? AND cart_id = ?");
    $stmt->execute([$qty, $item_id, $cart_id]);
    $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_count = (int)$stmt->fetchColumn();
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

// Handler untuk hapus banyak item sekaligus (select all/hapus beberapa)
if ($action === 'delete_selected' && isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
    $ids = array_map('intval', $_POST['selected_items']);
    if (!empty($ids)) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        // Hapus hanya item yang milik cart user ini
        $sql = "DELETE FROM cart_item WHERE id IN ($in) AND cart_id = ?";
        $stmt = $conn->prepare($sql);
        // PDO bind param harus urut, array_merge ids + cart_id
        $params = array_merge($ids, [$cart_id]);
        $stmt->execute($params);
    }
    // Hitung ulang cart_count
    $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_count = (int)$stmt->fetchColumn();
    echo json_encode(['success' => true, 'cart_count' => $cart_count]);
    exit;
}

// Read Cart (optional, for refresh)
if ($action === 'list') {
    ob_start();
    include 'get_cart.php';
    $cart_html = ob_get_clean();
    echo json_encode(['success'=>true,'html'=>$cart_html]);
    exit;
}

echo json_encode(['success'=>false,'message'=>'Unknown action']);