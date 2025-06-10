<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
<?php include '../configdb.php'; ?>
<link rel="stylesheet" href="../css/style2.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Product</a></li>
            </ul>
        </div>
    </div>
</main>

<?php
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<div class="product-page">
    <div class="header-actions">
        <h2>Product Management</h2>
        <a href="addproduct.php" class="btn-add">Add Product</a>
    </div>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search products..." onkeyup="searchProducts()">
    </div>

    <div class="table-container">
        <table id="productTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Retrieve all products
                    $sql = "SELECT * FROM product ORDER BY id_product DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Format the added date
                            $formattedDate = isset($row['added']) ? date('d M Y', strtotime($row['added'])) : date('d M Y');
                            
                            // Set the status class and text
                            $statusClass = '';
                            $statusText = '';
                            
                            switch ($row['status']) {
                                case 'published':
                                    $statusClass = 'status-published';
                                    $statusText = 'Published';
                                    break;
                                case 'low stock':
                                    $statusClass = 'status-low';
                                    $statusText = 'Low Stock';
                                    break;
                                default:
                                    $statusClass = 'status-draft';
                                    $statusText = 'Draft';
                                    break;
                            }

                            // Display product row
                            echo '<tr>
                                <td>
                                    <div class="product-info">
                                        <img src="../uploads/' . $row['image'] . '" alt="' . htmlspecialchars($row['namaproduct']) . '">
                                        <span>' . htmlspecialchars($row['namaproduct']) . '</span>
                                    </div>
                                </td>
                                <td>' . htmlspecialchars($row['category']) . '</td>
                                <td>' . htmlspecialchars($row['stock']) . '</td>
                                <td>Rp' . number_format($row['price'], 0, ',', '.') . '</td>
                                <td><span class="status-badge ' . $statusClass . '">' . $statusText . '</span></td>
                                <td>' . $formattedDate . '</td>
                                <td class="action-buttons">
                                    <a href="editproduct.php?id=' . $row['id_product'] . '" class="btn-edit">Edit</a>
                                    <a href="deleteproduct.php?id=' . $row['id_product'] . '" class="btn-delete" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="no-data">No products found</td></tr>';
                    }
                } catch (PDOException $e) {
                    echo '<tr><td colspan="7" class="error-message">Database error: ' . $e->getMessage() . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Search function for products
function searchProducts() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("productTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

<style>
/* Your CSS styles for the page */
.product-page {
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-add {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
}

.search-bar {
    margin-bottom: 20px;
    text-align: right;
}

#searchInput {
    padding: 8px 12px;
    width: 250px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.table-container {
    overflow-x: auto;
}

#productTable {
    width: 100%;
    border-collapse: collapse;
}

#productTable th, #productTable td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

#productTable th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.product-info {
    display: flex;
    align-items: center;
}

.product-info img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 10px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    text-align: center;
    min-width: 100px;
}

.status-published {
    background-color: #28a745;
    color: white;
}

.status-draft {
    background-color: #6c757d;
    color: white;
}

.status-low {
    background-color: #ffc107;
    color: white;
}

.action-buttons a {
    text-decoration: none;
    margin-right: 10px;
}

.btn-edit {
    color: #007bff;
}

.btn-delete {
    color: #dc3545;
}

.no-data, .error-message {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.error-message {
    color: #dc3545;
}
</style>

<?php include '../views/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/dashboard.js"></script>

<?php
// Update product stock when order status changes to "Processing"
if (isset($_GET['cart_id'])) {
    try {
        $cart_id = $_GET['cart_id']; // Cart ID passed via URL (should be set when status changes to 'Processing')

        // Retrieve all items in the cart
        $stmtCartItems = $conn->prepare("SELECT product_id, qty FROM cart_item WHERE cart_id = ?");
        $stmtCartItems->execute([$cart_id]);

        while ($item = $stmtCartItems->fetch(PDO::FETCH_ASSOC)) {
            // Check the current stock of the product
            $stmtStock = $conn->prepare("SELECT stock FROM product WHERE id_product = ?");
            $stmtStock->execute([$item['product_id']]);
            $product = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                // Subtract the stock based on the quantity purchased
                $newStock = $product['stock'] - $item['qty'];

                // Update the product stock in the database
                $stmtUpdateStock = $conn->prepare("UPDATE product SET stock = ? WHERE id_product = ?");
                $stmtUpdateStock->execute([$newStock, $item['product_id']]);
            }
        }

        // Update cart status to "Processing" when payment is confirmed
        $stmtUpdateOrder = $conn->prepare("UPDATE cart SET status = 'Processing' WHERE id = ?");
        $stmtUpdateOrder->execute([$cart_id]);

        echo "Stok diperbarui dan status cart diubah menjadi 'Processing'.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
