<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid product id']);
    exit;
}

require_once '../configdb.php';
$stmt = $conn->prepare("SELECT id_product, namaproduct, price, foto FROM _product WHERE id_product = ?");
$stmt->execute([$data['product_id']]);
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
        'foto' => $product['foto']
    ];
}

echo json_encode([
    'success' => true,
    'cart_count' => array_sum(array_column($cart, 'qty'))
]);