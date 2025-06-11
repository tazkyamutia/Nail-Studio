<?php
include '../views/header.php';
include '../views/sidebar.php';
require_once '../configdb.php';



// --- SALES ANALYTICS (Akumulasi per bulan, chart naik) ---
$bulan = [];
$penjualan = [];
$sql_sales = "SELECT MONTH(c.created_at) AS bulan, 
                     SUM(ci.qty * ci.price) AS penjualan
              FROM cart c
              JOIN cart_item ci ON c.id = ci.cart_id
              WHERE c.order_status = 'Completed'
              GROUP BY MONTH(c.created_at)
              ORDER BY bulan";
$stmt_sales = $conn->prepare($sql_sales);
$stmt_sales->execute();
$accum = 0;
while($row = $stmt_sales->fetch(PDO::FETCH_ASSOC)) {
    $accum += (int)$row['penjualan'];
    $bulan[] = date('M', mktime(0, 0, 0, $row['bulan'], 10));
    $penjualan[] = $accum;
}

// --- VISITOR STATISTICS (Per Hari Senin-Minggu) ---
$visitor_day = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$visitor_count = array_fill(0, 7, 0);
$sql_visitor = "SELECT DAYOFWEEK(created_at) AS daynum, COUNT(*) AS jumlah
                FROM visitor_logs
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY daynum";
$stmt_visitor = $conn->prepare($sql_visitor);
$stmt_visitor->execute();
while($row = $stmt_visitor->fetch(PDO::FETCH_ASSOC)) {
    // In MySQL, DAYOFWEEK: 1=Sun, 2=Mon, ..., 7=Sat
    $index = ($row['daynum'] + 5) % 7; // agar 0=Mon, dst.
    $visitor_count[$index] = (int)$row['jumlah'];
}

// --- PRODUCT PERFORMANCE (Top 4 produk terlaris) ---
$product_label = [];
$product_qty = [];
$sql_product = "SELECT p.namaproduct, SUM(ci.qty) AS terjual
                FROM cart_item ci
                JOIN cart c ON ci.cart_id = c.id
                JOIN product p ON ci.product_id = p.id_product
                WHERE c.order_status = 'Completed'
                GROUP BY ci.product_id
                ORDER BY terjual DESC
                LIMIT 4";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->execute();
while($row = $stmt_product->fetch(PDO::FETCH_ASSOC)) {
    $product_label[] = $row['namaproduct'];
    $product_qty[] = (int)$row['terjual'];
}

// --- ORDER STATUS (Pie, 4 status utama, value 0 jika tidak ada di database) ---
$all_status = ['Completed', 'Pending', 'Processing', 'Cancelled'];
$status_count = array_fill_keys($all_status, 0);
$sql_status = "SELECT order_status, COUNT(*) AS jumlah FROM cart GROUP BY order_status";
$stmt_status = $conn->prepare($sql_status);
$stmt_status->execute();
while($row = $stmt_status->fetch(PDO::FETCH_ASSOC)) {
    $status = $row['order_status'];
    if (isset($status_count[$status])) {
        $status_count[$status] = (int)$row['jumlah'];
    }
}
?>

<link rel="stylesheet" href="../css/style2.css">
<main>
    <div class="head-title">
        <div class="left">
            <h1>Analytics</h1>
            <ul class="breadcrumb">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Analytics</a></li>
            </ul>
        </div>
    </div>

<main>
    <div class="analytics">
        <div class="chart-container">
            <div class="chart-card">
                <h3>Sales Analytics</h3>
                <canvas id="salesChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Visitor Statistics</h3>
                <canvas id="visitorChart"></canvas>
            </div>
        </div>
        <div class="chart-container">
            <div class="chart-card">
                <h3>Product Performance</h3>
                <canvas id="productChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Order Status</h3>
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>
</main>

<?php include '../views/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// --- Sales Analytics (Line Chart, akumulasi) ---
const labelsSales = <?php echo json_encode($bulan); ?>;
const dataSales = <?php echo json_encode($penjualan); ?>;
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: labelsSales,
        datasets: [{
            label: 'Sales 2024',
            data: dataSales,
            borderColor: '#8B1D3B',
            backgroundColor: 'rgba(139,29,59,0.08)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {scales: {y: {beginAtZero: true}}}
});

// --- Visitor Statistics (Bar Chart, per Hari) ---
const labelsVisitor = <?php echo json_encode($visitor_day); ?>;
const dataVisitor = <?php echo json_encode($visitor_count); ?>;
new Chart(document.getElementById('visitorChart'), {
    type: 'bar',
    data: {
        labels: labelsVisitor,
        datasets: [{
            label: 'Visitors',
            data: dataVisitor,
            backgroundColor: 'rgba(139,29,59,0.2)',
            borderColor: '#8B1D3B',
            borderWidth: 2
        }]
    },
    options: {scales: {y: {beginAtZero: true}}}
});

// --- Product Performance (Doughnut Chart, 4 warna) ---
const productLabel = <?php echo json_encode($product_label); ?>;
const productQty = <?php echo json_encode($product_qty); ?>;
const productColors = [
    '#881337', // Pink Perfections
    '#be185d', // Green Elegant
    '#e11d48', // Tropical Bloom
    '#f472b6'  // Elegant Ruby
];
new Chart(document.getElementById('productChart'), {
    type: 'doughnut',
    data: {
        labels: productLabel,
        datasets: [{
            label: 'Product Performance',
            data: productQty,
            backgroundColor: productColors,
            borderWidth: 0
        }]
    },
    options: {
        cutout: '60%',
        plugins: {
            legend: { position: 'right' }
        }
    }
});

// --- Order Status (Pie Chart, warna legend tetap) ---
const orderStatus = <?php echo json_encode(array_keys($status_count)); ?>;
const statusCount = <?php echo json_encode(array_values($status_count)); ?>;
const statusColors = [
    '#4CAF50',  // Completed (Green)
    '#FFEB3B',  // Pending (Yellow)
    '#2196F3',  // Processing (Blue)
    '#FF5252'   // Cancelled (Red)
];
new Chart(document.getElementById('orderChart'), {
    type: 'pie',
    data: {
        labels: orderStatus,
        datasets: [{
            label: 'Order Status',
            data: statusCount,
            backgroundColor: statusColors,
            borderWidth: 0
        }]
    },
    options: {
        plugins: {
            legend: { position: 'right' }
        }
    }
});
</script>
