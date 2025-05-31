<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid product id']);
    exit;
}

require_once '../configdb.php';

// Ganti sesuai field tabel-mu ('image' atau 'foto')
$stmt = $conn->prepare("SELECT id_product, namaproduct, price, image FROM product WHERE id_product = ?");
$stmt->execute([$_POST['product_id']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = &$_SESSION['cart'];

if (isset($cart[$product['id_product']])) {
    $cart[$product['id_product']]['qty'] += 1;
} else {
    $cart[$product['id_product']] = [
        'id' => $product['id_product'],
        'name' => $product['namaproduct'],
        'price' => $product['price'],
        'qty' => 1,
        'image' => $product['image'] // Sesuaikan jika nama field gambar adalah image
    ];
}

echo json_encode([
    'success' => true,
    'cart_count' => array_sum(array_column($cart, 'qty'))
]);
