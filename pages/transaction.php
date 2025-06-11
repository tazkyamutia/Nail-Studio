<?php
include '../configdb.php';

// AJAX handler (output JSON saja saat ada ?ajax=1)
if (isset($_GET['ajax'])) {
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

    $sql = "SELECT c.id, u.fullname AS pembeli
            FROM cart c
            JOIN user u ON c.user_id = u.id
            WHERE c.order_status = 'Completed'
            ORDER BY c.created_at DESC
            LIMIT $limit OFFSET $offset";
    $stmt = $conn->query($sql);

    $result = [];
    while ($trans = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cart_id = $trans['id'];
        $barang = [];
        $harga  = [];
        $total  = 0;
        $qty_total = 0; // Total quantity per transaction

        // Get cart items
        $stmt2 = $conn->prepare(
            "SELECT p.namaproduct, ci.price, ci.qty 
             FROM cart_item ci
             JOIN product p ON ci.product_id = p.id_product
             WHERE ci.cart_id = ?"
        );
        $stmt2->execute([$cart_id]);
        while ($item = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $barang[] = $item['namaproduct'] . " x" . $item['qty']; // Show quantity with product name
            $harga[]  = 'Rp ' . number_format($item['price'], 0, ',', '.');
            $total   += $item['price'] * $item['qty']; // Multiply price by quantity to get the total
            $qty_total += $item['qty']; // Add to total quantity
        }

        $result[] = [
            'id'      => $cart_id,
            'pembeli' => $trans['pembeli'],
            'barang'  => $barang,
            'harga'   => $harga,
            'total'   => 'Rp ' . number_format($total, 0, ',', '.'), // Display total price
            'qty'     => $qty_total // Show total quantity
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Setelah blok AJAX di atas, baru include HTML lain
include '../views/header.php';
include '../views/sidebar.php';
?>
<link rel="stylesheet" href="../css/style2.css">
<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Transaction History</a>
                </li>
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
                        <th>Total Pembelian</th> <!-- New column for Quantity -->
                    </tr>
                </thead>
               <tbody id="transactionTable">
<?php
// 5 data awal (langsung tampil)
$limit = 5;
$sql = "SELECT c.id, u.fullname AS pembeli
        FROM cart c
        JOIN user u ON c.user_id = u.id
        WHERE c.order_status = 'Completed'
        ORDER BY c.created_at DESC
        LIMIT $limit OFFSET 0";
$stmt = $conn->query($sql);
while ($trans = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cart_id = $trans['id'];
    $barang = [];
    $harga  = [];
    $total  = 0;
    $qty_total = 0; // Total quantity for each transaction

    $stmt2 = $conn->prepare(
        "SELECT p.namaproduct, ci.price, ci.qty 
         FROM cart_item ci
         JOIN product p ON ci.product_id = p.id_product
         WHERE ci.cart_id = ?"
    );
    $stmt2->execute([$cart_id]);
    while ($item = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $barang[] = $item['namaproduct'] . " x" . $item['qty']; // Display quantity along with product name
        $harga[]  = 'Rp ' . number_format($item['price'], 0, ',', '.');
        $total   += $item['price'] * $item['qty']; // Accumulate total price by multiplying with quantity
        $qty_total += $item['qty']; // Accumulate total quantity
    }

    // === FILTER di sini: skip jika barang kosong ===
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
    echo "<td>Rp ".number_format($total, 0, ',', '.')."</td>"; // Display total price
    echo "<td>".$qty_total." items</td>"; // Display total quantity of items in this transaction
    echo "</tr>";
}
?>
</tbody>

            </table>
        </div>
        <button class="load-more" id="loadMore">fit more</button>
    </div>
</main>
</section>

<?php include '../views/footer.php'; ?>
<script>
let offset = 5; // Sudah tampil 5 data awal
const limit = 5;
let selesai = false;

function renderTransactions(data) {
    let html = '';
    for (let row of data) {
        html += `<tr>`;
        html += `<td>#${String(row.id).padStart(3, '0')}</td>`;
        html += `<td>${row.pembeli}</td>`;
        html += `<td><ul>${row.barang.map(b => `<li>${b}</li>`).join('')}</ul></td>`;
        html += `<td><ul>${row.harga.map(h => `<li>${h}</li>`).join('')}</ul></td>`;
        html += `<td>${row.total}</td>`;
        html += `<td>${row.qty} items</td>`; // Display total quantity of items
        html += `</tr>`;
    }
    // Hanya tambah data, tidak hapus data awal
    document.getElementById('transactionTable').innerHTML += html;
}

function loadMore() {
    if (selesai) return;
    fetch('?ajax=1&offset=' + offset + '&limit=' + limit)
        .then(res => res.json())
        .then(data => {
            if (data.length < limit) {
                selesai = true;
                document.getElementById('loadMore').style.display = 'none';
            }
            renderTransactions(data);
            offset += limit;
        })
        .catch(err => alert("Gagal load data: " + err));
}
document.getElementById('loadMore').addEventListener('click', loadMore);
</script>
<style>
.table-container { margin: 32px 0; }
.table-container table { width: 100%; border-collapse: collapse; }
.table-container th, .table-container td { padding: 10px 12px; border-bottom: 1px solid #eee; text-align: left; }
.table-container th { background: #8B1D3B; color: #fff; }
.table-container tr:nth-child(even) { background: #faf8fb; }
</style>
