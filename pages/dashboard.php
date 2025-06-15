<?php 
include '../views/header.php'; 
include '../views/sidebar.php'; 
include '../configdb.php';

// Update status pesanan jika admin mengubah dropdown
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $update = $conn->prepare("UPDATE cart SET order_status = ? WHERE id = ?");
    $update->execute([$_POST['new_status'], $_POST['order_id']]);
    // Setelah update, refresh halaman untuk menampilkan status terbaru
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Statistik user hari ini
$stmtRegister = $conn->prepare("SELECT COUNT(*) AS total_register FROM user WHERE DATE(created_at) = CURDATE()");
$stmtRegister->execute();
$total_register = $stmtRegister->fetch(PDO::FETCH_ASSOC)['total_register'];

$stmtLogin = $conn->prepare("SELECT COUNT(*) AS total_login FROM user WHERE DATE(last_login) = CURDATE()");
$stmtLogin->execute();
$total_login = $stmtLogin->fetch(PDO::FETCH_ASSOC)['total_login'];

// Total sales transaksi Completed
$stmtSales = $conn->prepare("
    SELECT SUM(ci.qty * ci.price) AS total_sales
    FROM cart c
    JOIN cart_item ci ON c.id = ci.cart_id
    WHERE c.order_status = 'Completed'
");
$stmtSales->execute();
$total_sales = $stmtSales->fetch(PDO::FETCH_ASSOC)['total_sales'];

// Ambil semua data order dari tabel cart 
$stmt = $conn->query("
    SELECT 
        c.id, 
        u.fullname, 
        c.order_status, 
        c.status, 
        DATE(c.created_at) as order_date, 
        b.file_path
    FROM cart c
    JOIN user u ON u.id = c.user_id
    LEFT JOIN bukti_bayar b ON b.cart_id = c.id
    ORDER BY 
        CASE 
            WHEN c.order_status = 'Processing' THEN 1
            WHEN c.order_status = 'Shipped' THEN 2
            WHEN c.order_status = 'Pending' THEN 3
            WHEN c.order_status = 'Completed' THEN 4
            ELSE 5
        END ASC,
        c.created_at ASC
    LIMIT 40
");

$orders = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $barang = [];
    $harga  = [];
    $total  = 0;

    $stmt2 = $conn->prepare("
        SELECT p.namaproduct, ci.qty, ci.price
        FROM cart_item ci
        JOIN product p ON ci.product_id = p.id_product
        WHERE ci.cart_id = ?
    ");
    $stmt2->execute([$row['id']]);

    while ($item = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $barang[] = $item['namaproduct'] . ($item['qty'] > 1 ? " x" . $item['qty'] : "");
        $harga[]  = 'Rp ' . number_format($item['price'], 0, ',', '.');
        $total   += $item['price'] * $item['qty'];
    }

    if (count($barang) === 0) continue; // skip if no items

    $row['barang'] = $barang;
    $row['harga']  = $harga;
    $row['total']  = $total;
    $orders[] = $row;
}
?>

<link rel="stylesheet" href="../css/style2.css">
<style>
    select.status-select {
        padding: 4px 10px;
        border-radius: 9999px;
        font-weight: 600;
        border: none;
        outline: none;
        cursor: pointer;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dbeafe; color: #1d4ed8; }
    .status-shipped { background: #ede9fe; color: #6b21a8; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .table-container { margin: 32px 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px 12px; border-bottom: 1px solid #eee; text-align: left; }
    th { background: #8B1D3B; color: #000000; }
    tr:nth-child(even) { background: #faf8fb; }
</style>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Order/Transaction</a></li>
            </ul>
        </div>
    </div>

    <ul class="box-info">
        <li>
            <i class='bx bxs-calendar-check'></i>
            <span class="text"><h3><?= count($orders) ?></h3><p>Recent Orders</p></span>
        </li>
        <li>
            <i class='bx bxs-group'></i>
            <span class="text"><h3><?= $total_login; ?></h3><p>Visitors Today</p></span>
        </li>
        <li>
            <i class='bx bx-wallet'></i>
            <span class="text"><h3>Rp <?= number_format($total_sales ?? 0, 0, ',', '.') ?></h3><p>Total Sales</p></span>
        </li>
        <li>
            <i class='bx bx-line-chart'></i>
            <span class="text"><h3><?= $total_register; ?></h3><p>Register</p></span>
        </li>
    </ul>

    <div class="table-container">
        <h3>Recent Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Pembeli</th>
                    <th>Tanggal Pembelian</th>
                    <th>Daftar Barang</th>
                    <th>Harga per Item</th>
                    <th>Total Harga</th>
                    <th>Bukti Bayar</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td><?= htmlspecialchars($order['fullname']) ?></td>
                        <td><?= date('d-m-Y', strtotime($order['order_date'])) ?></td>
                        <td><ul><?php foreach ($order['barang'] as $b) echo "<li>" . htmlspecialchars($b) . "</li>"; ?></ul></td>
                        <td><ul><?php foreach ($order['harga'] as $h) echo "<li>$h</li>"; ?></ul></td>
                        <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($order['file_path']): ?>
                                <a href="../uploads/<?= htmlspecialchars($order['file_path']) ?>" target="_blank" class="text-blue-600 underline">View</a>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="new_status" onchange="this.form.submit()" class="status-select status-<?= strtolower($order['order_status']) ?>">
                                    <?php
                                    $statuses = ['Pending', 'Processing', 'Shipped', 'Completed'];
                                    foreach ($statuses as $status): ?>
                                        <option value="<?= $status ?>" <?= $order['order_status'] == $status ? 'selected' : '' ?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="order_detail.php?id=<?= $order['id'] ?>" class="text-sm bg-pink-600 text-white px-3 py-1 rounded hover:bg-pink-700 transition">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../views/footer.php'; ?>
