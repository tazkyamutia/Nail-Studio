<?php
include '../configdb.php';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

include '../views/header.php';
include '../views/sidebar.php';
?>
<link rel="stylesheet" href="../css/style2.css">
<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Transaction History</a></li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="header"></div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Pembeli</th>
                        <th>Daftar Barang</th>
                        <th>Harga per Item</th>
                        <th>Total Harga</th>
                        <th>Total Pembelian</th>
                    </tr>
                </thead>
                <tbody id="transactionTable">
<?php
$sql = "SELECT c.id, u.fullname AS pembeli
        FROM cart c
        JOIN user u ON c.user_id = u.id
        WHERE c.order_status = 'Completed'
        ORDER BY c.created_at DESC
        LIMIT $limit OFFSET $offset";
$stmt = $conn->query($sql);

while ($trans = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cart_id = $trans['id'];
    $barang = [];
    $harga = [];
    $total = 0;
    $qty_total = 0;

    $stmt2 = $conn->prepare(
        "SELECT p.namaproduct, ci.price, ci.qty 
         FROM cart_item ci
         JOIN product p ON ci.product_id = p.id_product
         WHERE ci.cart_id = ?"
    );
    $stmt2->execute([$cart_id]);
    while ($item = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $barang[] = $item['namaproduct'] . " x" . $item['qty'];
        $harga[]  = 'Rp ' . number_format($item['price'], 0, ',', '.');
        $total   += $item['price'] * $item['qty'];
        $qty_total += $item['qty'];
    }

    if (count($barang) === 0) continue;

    echo "<tr>";
    echo "<td>#".str_pad($cart_id, 3, '0', STR_PAD_LEFT)."</td>";
    echo "<td>".htmlspecialchars($trans['pembeli'])."</td>";
    echo "<td><ul>";
    foreach ($barang as $b) echo "<li>".htmlspecialchars($b)."</li>";
    echo "</ul></td>";
    echo "<td><ul>";
    foreach ($harga as $h) echo "<li>$h</li>";
    echo "</ul></td>";
    echo "<td>Rp ".number_format($total, 0, ',', '.')."</td>";
    echo "<td>".$qty_total." items</td>";
    echo "</tr>";
}
?>
                </tbody>
            </table>
        </div>

<?php
// PAGINATION
$totalQuery = $conn->query("SELECT COUNT(*) FROM cart WHERE order_status = 'Completed'");
$totalRows = $totalQuery->fetchColumn();
$totalPages = ceil($totalRows / $limit);

echo "<div style='margin-top:20px;'>";
if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "' style='margin-right: 10px;'>&laquo; Previous</a>";
}
if ($page < $totalPages) {
    echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
}
echo "</div>";
?>
    </div>
</main>
</section>

<?php include '../views/footer.php'; ?>

<style>
.table-container { margin: 32px 0; }
.table-container table { width: 100%; border-collapse: collapse; }
.table-container th, .table-container td { padding: 10px 12px; border-bottom: 1px solid #eee; text-align: left; }
.table-container th { background: #8B1D3B; color: #fff; }
.table-container tr:nth-child(even) { background: #faf8fb; }

a { text-decoration: none; color: #8B1D3B; font-weight: bold; }
a:hover { text-decoration: underline; }
</style>
