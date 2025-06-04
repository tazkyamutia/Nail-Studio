<?php
if (session_status() == PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
require_once '../configdb.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

$user_id = $_SESSION['id'];
$action = $_POST['action'] ?? '';
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if (!$product_id || !in_array($action, ['add', 'remove'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
    exit;
}

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT IGNORE INTO favorite (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
        $success = $stmt->rowCount() > 0;
        $message = $success ? 'Produk ditambahkan ke favorit.' : 'Produk sudah ada di favorit.';
    } else {
        $stmt = $conn->prepare("DELETE FROM favorite WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $success = $stmt->rowCount() > 0;
        $message = $success ? 'Produk dihapus dari favorit.' : 'Produk tidak ditemukan di favorit.';
    }

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM favorite WHERE user_id = ?");
    $countStmt->execute([$user_id]);
    $favCount = $countStmt->fetchColumn();

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'fav_count' => (int)$favCount
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ]);
}
