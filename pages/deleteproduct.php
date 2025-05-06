<?php
include '../configdb.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: product.php");
    exit();
}

$id = $_GET['id'];

try {
    // First get the product details to find the image file
    $select_sql = "SELECT * FROM product WHERE id_product = :id";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bindParam(':id', $id);
    $select_stmt->execute();
    $product = $select_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        // Delete the image file if it's not the default
        if ($product['image'] != 'default.jpg') {
            $image_path = "../uploads/" . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Now delete the product from database
        $delete_sql = "DELETE FROM product WHERE id_product = :id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();
        
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Product not found.";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error deleting product: " . $e->getMessage();
}

header("Location: product.php");
exit();
?>