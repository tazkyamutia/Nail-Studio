<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
<?php include '../configdb.php'; ?>
<link rel="stylesheet" href="../css/style2.css">

<?php
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

// Fetch product data
try {
    $sql = "SELECT * FROM product WHERE id_product = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        $_SESSION['error'] = "Product not found.";
        header("Location: product.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error retrieving product: " . $e->getMessage();
    header("Location: product.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaproduct = $_POST['namaproduct'] ?? '';
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $status = $_POST['status'] ?? 'draft';
    $image = $product['image']; // Keep current image by default
    
    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = "../uploads/";
        $temp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $unique_image = uniqid() . '.' . $image_ext;
        $upload_path = $upload_dir . $unique_image;
        
        // Check file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_ext, $allowed_types)) {
            if (move_uploaded_file($temp_name, $upload_path)) {
                // Delete old image if it's not the default image
                if ($product['image'] !== 'default.jpg' && file_exists($upload_dir . $product['image'])) {
                    unlink($upload_dir . $product['image']);
                }
                $image = $unique_image;
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: editproduct.php?id=" . $id);
                exit();
            }
        } else {
            $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: editproduct.php?id=" . $id);
            exit();
        }
    }
    
    try {
        // Update product
        $sql = "UPDATE product SET 
                namaproduct = :namaproduct, 
                category = :category, 
                stock = :stock, 
                price = :price, 
                status = :status, 
                image = :image 
                WHERE id_product = :id";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':namaproduct', $namaproduct);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();
        
        $_SESSION['success'] = "Product updated successfully!";
        header("Location: product.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating product: " . $e->getMessage();
    }
}
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Product</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="product.php">Product</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Edit Product</a></li>
            </ul>
        </div>
    </div>
    
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    
    <div class="product-form-container">
        <div class="card">
            <div class="card-header">
                <h3>Edit Product Information</h3>
            </div>
            <div class="card-body">
                <form action="editproduct.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="namaproduct">Product Name</label>
                        <input type="text" id="namaproduct" name="namaproduct" value="<?php echo htmlspecialchars($product['namaproduct']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="nail polish" <?php echo ($product['category'] == 'nail polish') ? 'selected' : ''; ?>>nail polish</option>
                            <option value="nail tools" <?php echo ($product['category'] == 'nail tools') ? 'selected' : ''; ?>>nail tools</option>
                            <option value="nail care" <?php echo ($product['category'] == 'nail care') ? 'selected' : ''; ?>>nail care</option>
                            <option value="nail kit" <?php echo ($product['category'] == 'nail kit') ? 'selected' : ''; ?>>nail kit</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (Rp)</label>
                        <input type="number" id="price" name="price" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="draft" <?php echo ($product['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($product['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                            <option value="low stock" <?php echo ($product['status'] == 'low stock') ? 'selected' : ''; ?>>Low Stock</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Current Image</label>
                        <div class="current-image">
                            <img src="../uploads/<?php echo $product['image']; ?>" alt="Current Product Image">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Upload New Image (leave empty to keep current image)</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Recommended size: 400x400 pixels</small>
                    </div>
                    
                    <div class="form-actions">
                        <a href="product.php" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
.product-form-container {
    padding: 20px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #ddd;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.current-image {
    margin: 10px 0;
}

.current-image img {
    max-width: 150px;
    max-height: 150px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 12px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 30px;
}

.btn-cancel {
    padding: 10px 20px;
    background-color: #f8f9fa;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    margin-right: 10px;
}

.btn-submit {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php include '../views/footer.php'; ?>