<?php
require_once '../configdb.php';
header('Content-Type: application/json');
$ids = [];
if (isset($_GET['ids'])) {
  $ids = array_filter(explode(',', $_GET['ids']), function($x) { return is_numeric($x); });
}
if (!$ids) {
  echo json_encode([]);
  exit;
}
$in  = str_repeat('?,', count($ids) - 1) . '?';
$sql = "SELECT id_product, namaproduct, price, image FROM product WHERE id_product IN ($in)";
$stmt = $conn->prepare($sql);
$stmt->execute($ids);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
