<?php
session_start();
require_once '../configdb.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$address = trim($_POST['address'] ?? '');
$type = $_POST['type'] ?? 'shipping';

if ($address) {
    $stmt = $conn->prepare("INSERT INTO address (user_id, address, type) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $address, $type]);
    // Redirect back to checkout
    header('Location: checkout.php');
    exit;
} else {
    // Redirect back with error (optional)
    header('Location: checkout.php?error=Alamat tidak boleh kosong');
    exit;
}
