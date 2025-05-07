<?php
session_start();
require_once '../configdb.php';

header('Content-Type: application/json');

if (!isset($_POST['action'])) {
    echo json_encode(['success'=>false,'message'=>'No action']);
    exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = &$_SESSION['cart'];

$action = $_POST['action'];

if ($action === 'add') {
    // Tambah 1 qty (atau produk baru)
    $id = intval($_POST['product_id']);
    $stmt = $conn->prepare("SELECT id_product, namaproduct, price, foto FROM _product WHERE id_product = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo json_encode(['success'=>false,'message'=>'Product not found']);
        exit;
    }
    if (isset($cart[$id])) {
        $cart[$id]['qty'] += 1;
    } else {
        $cart[$id] = [
            'id' => $product['id_product'],
            'name' => $product['namaproduct'],
            'price' => $product['price'],
            'qty' => 1,
            'foto' => $product['foto']
        ];
    }
    $cart_count = array_sum(array_column($cart, 'qty'));
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'update') {
    // Update qty
    $id = intval($_POST['product_id']);
    $qty = max(1, intval($_POST['qty']));
    if (isset($cart[$id])) {
        $cart[$id]['qty'] = $qty;
    }
    $cart_count = array_sum(array_column($cart, 'qty'));
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'plus') {
    // Tambah 1 qty
    $id = intval($_POST['product_id']);
    if (isset($cart[$id])) {
        $cart[$id]['qty'] += 1;
    }
    $cart_count = array_sum(array_column($cart, 'qty'));
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'minus') {
    // Kurangi qty, hapus jika 0
    $id = intval($_POST['product_id']);
    if (isset($cart[$id])) {
        $cart[$id]['qty'] -= 1;
        if ($cart[$id]['qty'] <= 0) {
            unset($cart[$id]);
        }
    }
    $cart_count = array_sum(array_column($cart, 'qty'));
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
    exit;
}

if ($action === 'delete') {
    // Hapus produk dari cart
    $id = intval($_POST['product_id']);
    if (isset($cart[$id])) {
        unset($cart[$id]);
    }
    $cart_count = array_sum(array_column($cart, 'qty'));
    echo json_encode(['success'=>true,'cart_count'=>$cart_count]);
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