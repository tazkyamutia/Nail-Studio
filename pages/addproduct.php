<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
<?php include '../configdb.php'; ?>
<link rel="stylesheet" href="../css/style2.css">

<?php
// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaproduct = $_POST['namaproduct'] ?? '';
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $discount = $_POST['discount'] ?? 0; // Added discount field
    $status = $_POST['status'] ?? 'draft';
    $added = date('Y-m-d'); // Current date
    
    // Debug upload information
    if (isset($_FILES['image'])) {
        $upload_errors = [
            0 => 'No error',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'A PHP extension stopped the file upload'
        ];
        
        error_log("File upload status: " . $upload_errors[$_FILES['image']['error']]);
        error_log("File name: " . $_FILES['image']['name']);
        error_log("File size: " . $_FILES['image']['size'] . " bytes");
    }
    
    // Handle file upload
    $image = 'default.jpg'; // Default image
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = "../uploads/";
        
        // Check if uploads directory exists, if not try to create it
        if (!file_exists($upload_dir)) {
            error_log("Creating directory: " . $upload_dir);
            if (!mkdir($upload_dir, 0755, true)) {
                error_log("Failed to create directory: " . $upload_dir);
                $_SESSION['error'] = "Failed to create uploads directory. Please contact administrator.";
                header("Location: addproduct.php");
                exit();
            }
        }
        
        // Check directory permissions
        if (!is_writable($upload_dir)) {
            efrror_log("Directory not writable: " . $upload_dir);
            $_SESSION['error'] = "Uploads directory is not writable. Please contact administrator.";
            header("Location: addproduct.php");
            exit();
        }
        
        $temp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $unique_image = uniqid() . '.' . $image_ext;
        $upload_path = $upload_dir . $unique_image;
        
        error_log("Temp file path: " . $temp_name);
        error_log("Destination path: " . $upload_path);
        
        // Check file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_ext, $allowed_types)) {
            // Try to move file and capture detailed error if it fails
            if (move_uploaded_file($temp_name, $upload_path)) {
                error_log("File uploaded successfully: " . $upload_path);
                $image = $unique_image;
            } else {
                $error = error_get_last();
                $error_message = "Failed to upload image. ";
                if ($error) {
                    $error_message .= "PHP Error: " . $error['message'];
                }
                error_log($error_message);
                $_SESSION['error'] = $error_message;
                header("Location: addproduct.php");
                exit();
            }
        } else {
            error_log("Invalid file type: " . $image_ext);
            $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: addproduct.php");
            exit();
        }
    }
    
    try {
        // Insert new product with discount included
        $sql = "INSERT INTO product (namaproduct, category, stock, price, discount, status, image, added) 
                VALUES (:namaproduct, :category, :stock, :price, :discount, :status, :image, :added)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':namaproduct', $namaproduct);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':discount', $discount); // Bind discount field
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':added', $added);
        
        $stmt->execute();
        
        $_SESSION['success'] = "Product added successfully!";
        header("Location: product.php");
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['error'] = "Error adding product: " . $e->getMessage();
    }
}
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Add New Product</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="product.php">Product</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Add New Product</a></li>
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
                <h3>Product Information</h3>
            </div>
            <div class="card-body">
                <form action="addproduct.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="namaproduct">Product Name</label>
                        <input type="text" id="namaproduct" name="namaproduct" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="nail polish">nail polish</option>
                            <option value="nail tools">nail tools</option>
                            <option value="nail care">nail care</option>
                            <option value="nail kit">nail kit</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (Rp)</label>
                        <input type="number" id="price" name="price" min="0" value="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Discount (%)</label>
                        <input type="number" id="discount" name="discount" min="0" max="100" step="0.01" value="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="low stock">Low Stock</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Recommended size: 220x220 pixels</small>
                    </div>
                    
                    <div class="form-actions">
                        <a href="product.php" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Add Product</button>
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
    background-color: #28a745;
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
