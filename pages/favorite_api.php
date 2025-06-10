<?php
if (session_status() == PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
require_once '../configdb.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => true, 'fav_count' => 0]);
    exit;
}

$user_id = $_SESSION['id'];
$action = $_POST['action'] ?? '';
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if (!$product_id || !in_array($action, ['add', 'remove'])) {
    echo json_encode(['success' => true, 'fav_count' => 0]);
    exit;
}

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT IGNORE INTO favorite (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("DELETE FROM favorite WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        // TIDAK PERLU CEK rowCount(), tidak perlu pesan gagal.
    }

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM favorite WHERE user_id = ?");
    $countStmt->execute([$user_id]);
    $favCount = $countStmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'fav_count' => (int)$favCount
    ]);
} catch (Exception $e) {
    // Bahkan kalau error server, tetap tidak perlu alert ke user
    echo json_encode([
        'success' => true,
        'fav_count' => 0
    ]);
}
