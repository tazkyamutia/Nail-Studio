<?php 
include '../views/header.php'; 
include '../views/sidebar.php'; 
include '../configdb.php';

// Update status pesanan jika admin mengubah dropdown
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $update = $conn->prepare("UPDATE cart SET order_status = ? WHERE id = ?");
    $update->execute([$_POST['new_status'], $_POST['order_id']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Hitung statistik
$stmtRegister = $conn->prepare("SELECT COUNT(*) AS total_register FROM user WHERE DATE(created_at) = CURDATE()");
$stmtRegister->execute();
$total_register = $stmtRegister->fetch(PDO::FETCH_ASSOC)['total_register'];

$stmtLogin = $conn->prepare("SELECT COUNT(*) AS total_login FROM user WHERE DATE(last_login) = CURDATE()");
$stmtLogin->execute();
$total_login = $stmtLogin->fetch(PDO::FETCH_ASSOC)['total_login'];

// Ambil data recent orders
$stmt = $conn->query("SELECT c.id, u.username, c.order_status, DATE(c.created_at) as order_date, 
                      SUM(ci.qty * ci.price) as total, b.file_path
                      FROM cart c
                      JOIN user u ON u.id = c.user_id
                      JOIN cart_item ci ON c.id = ci.cart_id
                      LEFT JOIN bukti_bayar b ON b.cart_id = c.id
                      WHERE c.status != 'active'
                      GROUP BY c.id, u.username, c.order_status, c.created_at, b.file_path
                      ORDER BY c.created_at DESC
                      LIMIT 10");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/style2.css">
<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Home</a></li>
            </ul>
        </div>
    </div>

    <ul class="box-info">
        <li>
            <i class='bx bxs-calendar-check'></i>
            <span class="text">
                <h3>30</h3>
                <p>Recent Orders</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <h3><?= $total_login; ?></h3>
                <p>Visitors Today</p>
            </span>
        </li>
        <li>
            <i class='bx bx-wallet'></i>
            <span class="text">
                <h3>Rp 5.000.000</h3>
                <p>Total Sales</p>
            </span>
        </li>
        <li>
            <i class='bx bx-line-chart'></i>
            <span class="text">
                <h3><?= $total_register; ?></h3>
                <p>Register</p>
            </span>
        </li>
    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Recent Orders</h3>
                <i class='bx bx-search'></i>
                <i class='bx bx-filter'></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Date Order</th>
                        <th>Total</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td><?= date('d-m-Y', strtotime($order['order_date'])) ?></td>
                        <td>Rp<?= number_format($order['total'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($order['file_path']): ?>
                                <a href="../uploads/<?= $order['file_path'] ?>" target="_blank" class="text-blue-600 underline">View</a>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="new_status" onchange="this.form.submit()"
                                    class="w-full px-2 py-1 text-sm rounded-md border border-gray-300 bg-white shadow-sm 
                                           focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
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
    </div>
</main>

<?php include '../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/dashboard.js"></script>