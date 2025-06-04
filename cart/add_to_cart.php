<?php
session_start();
header('Content-Type: application/json');
require_once '../configdb.php';

// Cek login
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login untuk menambah ke keranjang.']);
    exit;
}

$user_id = $_SESSION['id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product id']);
    exit;
}

// Ambil produk
$stmt = $conn->prepare("SELECT id_product, namaproduct, price, image FROM product WHERE id_product = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// 1. Cari cart aktif user
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

// 2. Jika belum ada, buat cart baru
if (!$cart_id) {
    $stmt = $conn->prepare("INSERT INTO cart (user_id, status, created_at, updated_at) VALUES (?, 'active', NOW(), NOW())");
    $stmt->execute([$user_id]);
    $cart_id = $conn->lastInsertId();
}

// 3. Cek apakah produk sudah ada di cart_item
$stmt = $conn->prepare("SELECT id, qty FROM cart_item WHERE cart_id = ? AND product_id = ?");
$stmt->execute([$cart_id, $product_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    // Sudah ada, update qty
    $stmt = $conn->prepare("UPDATE cart_item SET qty = qty + 1, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$item['id']]);
} else {
    // Belum ada, insert baru
    $stmt = $conn->prepare("INSERT INTO cart_item (cart_id, product_id, qty, price, created_at, updated_at) VALUES (?, ?, 1, ?, NOW(), NOW())");
    $stmt->execute([$cart_id, $product_id, $product['price']]);
}

// Hitung total item di cart
$stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
$stmt->execute([$cart_id]);
$cart_count = (int)$stmt->fetchColumn();

echo json_encode([
    'success' => true,
    'cart_count' => $cart_count
]);
