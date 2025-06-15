<?php
include '../views/header.php';
include '../views/sidebar.php';
require_once '../configdb.php';

if (session_status() == PHP_SESSION_NONE) session_start();

 
// Handle tambah stock 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_stock'])) {
    $id = intval($_POST['id_product']);
    $add_stock = intval($_POST['add_stock']);
    $stmt = $conn->prepare("UPDATE product SET stock = stock + ? WHERE id_product = ?");
    $stmt->execute([$add_stock, $id]);
    echo json_encode(['success' => true]);
    exit;
}

// Handle update harga dan diskon 

// Handle update harga dan diskon via AJAX

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_harga'])) {
    $id = intval($_POST['id_product']);
    $price = intval($_POST['price']);
    $discount = intval($_POST['discount']); // Diskon yang diinputkan

    // Hitung harga setelah diskon
    $finalPrice = $price - ($price * $discount / 100);

    try {
        $stmt = $conn->prepare("UPDATE product SET price = ?, discount = ? WHERE id_product = ?");
        $stmt->execute([$finalPrice, $discount, $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]); // Menampilkan pesan error jika query gagal
    }
    exit;
}


//menambahkan discount


// Ambil semua produk
$stmt = $conn->prepare("SELECT id_product, namaproduct, category, stock, price, discount, status FROM product ORDER BY id_product DESC");
$stmt->execute();
$allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil daftar kategori unik untuk filter
$categories = array_unique(array_map(function($row) { return $row['category']; }, $allProducts));
sort($categories);

// Ambil daftar status unik untuk filter
$statuses = array_unique(array_map(function($row) { return $row['status']; }, $allProducts));
sort($statuses);

// Pagination logic
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 20;
$totalProducts = count($allProducts);
$totalPages = ceil($totalProducts / $perPage);
$offset = ($page - 1) * $perPage;
$products = array_slice($allProducts, $offset, $perPage);
?>

<link rel="stylesheet" href="../css/style2.css">
<main>
    <div class="head-title">
        <div class="left">
            <h1>Management Stock & Harga</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Management Stock & Harga</a></li>
            </ul>
        </div>
    </div>
</main>

<div class="product-page stock-management-page">
    <h2 class="section-title">Daftar Produk</h2>
    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-bar-left">
            <input type="text" id="searchInput" placeholder="Cari produk..." class="filter-input">
        </div>
        <div class="filter-bar-right">
            <span class="filter-label">Filter berdasarkan:</span>
            <select id="filterCategory" class="filter-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="filterStatus" class="filter-select">
                <option value="">Semua Status</option>
                <?php foreach ($statuses as $stat): ?>
                    <option value="<?= htmlspecialchars($stat) ?>"><?= htmlspecialchars(ucwords($stat)) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="filterStock" class="filter-select">
                <option value="">Urutkan Stock</option>
                <option value="desc">Stock Terbanyak</option>
                <option value="asc">Stock Terdikit</option>
            </select>
        </div>
    </div>
    <div class="table-container" style="margin-bottom:32px;">
        <table id="stockTable" class="stock-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stock</th>
                    <th>Harga</th>
                    <th>Diskon (%)</th> <!-- Menambahkan kolom Diskon -->
                    <th>Status</th>
                    <th>Stock</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $row): ?>
                    <tr data-id="<?= $row['id_product'] ?>"
                        data-stock="<?= $row['stock'] ?>"
                        data-price="<?= $row['price'] ?>"
                        data-discount="<?= $row['discount'] ?>"> <!-- Added data-discount -->
                        <td><?= htmlspecialchars($row['namaproduct']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td class="td-stock"><?= htmlspecialchars($row['stock']) ?></td>
                        <td class="td-price">Rp<?= number_format($row['price'], 0, ',', '.') ?></td>
                        <td class="td-discount"><?= htmlspecialchars($row['discount']) ?>%</td> <!-- Display Diskon -->
                        <td>
                            <span class="badge-status 
                                <?php
                                    if ($row['status'] === 'published') echo 'badge-published bg-green-100 text-green-700';
                                    elseif ($row['status'] === 'low stock') echo 'badge-low bg-yellow-100 text-yellow-700';
                                    else echo 'badge-draft bg-gray-100 text-gray-600';
                                ?>">
                                <?= htmlspecialchars(ucwords($row['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn-tambah-stock btn-soft-yellow"
                                data-id="<?= $row['id_product'] ?>"
                                data-nama="<?= htmlspecialchars($row['namaproduct']) ?>"
                                data-stock="<?= $row['stock'] ?>">
                                Tambah Stock
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn-edit-harga btn-soft-green"
                                data-id="<?= $row['id_product'] ?>"
                                data-nama="<?= htmlspecialchars($row['namaproduct']) ?>"
                                data-price="<?= $row['price'] ?>"
                                data-discount="<?= $row['discount'] ?>"> <!-- Added discount -->
                                Ubah Harga
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container" style="text-align:center; margin-top:18px;">
        <?php if ($totalPages > 1): ?>
            <nav class="pagination-nav" aria-label="Pagination" style="display:inline-block;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page-1 ?>" class="pagination-btn">&laquo; Prev</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="pagination-btn<?= $i == $page ? ' active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page+1 ?>" class="pagination-btn">Next &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Stock -->
<div id="tambahStockModal" class="modal-stock" style="display:none;z-index:9999;">
    <div class="modal-content">
        <span class="close-modal" id="closeTambahStockBtn" style="cursor:pointer;">&times;</span>
        <h3>Tambah Stock</h3>
        <form id="tambahStockForm" autocomplete="off">
            <input type="hidden" name="id_product" id="modal_id_product_stock">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" id="modal_nama_stock" disabled class="input-disabled">
            </div>
            <div class="form-group">
                <label for="modal_add_stock">Jumlah Tambah Stock</label>
                <div class="input-plusminus-group">
                    <button type="button" class="btn-minus" id="btnMinusStock">-</button>
                    <input type="number" name="add_stock" id="modal_add_stock" min="1" required value="1">
                    <button type="button" class="btn-plus" id="btnPlusStock">+</button>
                </div>
            </div>
            <button type="submit" class="btn-save tw-btn-yellow">Tambah</button>
        </form>
        <div id="modalMsgStock" style="margin-top:12px;"></div>
    </div>
</div>
<div id="modalBackdropStock" class="modal-backdrop" style="display:none;z-index:9998;"></div>

<!-- Modal Edit Harga -->

<div id="editHargaModal" class="modal-stock" style="display:none;z-index:9999;">
    <div class="modal-content">
        <span class="close-modal" id="closeEditHargaBtn" style="cursor:pointer;">&times;</span>
        <h3>Edit Harga</h3>
        <form id="editHargaForm" autocomplete="off">
            <input type="hidden" name="id_product" id="modal_id_product_harga">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" id="modal_nama_harga" disabled class="input-disabled">
            </div>
            <div class="form-group">
                <label for="modal_price_edit">Harga Baru (Rp)</label>
                <input type="number" name="price" id="modal_price_edit" min="0" required>
            </div>
            <div class="form-group">
                <label for="modal_discount_edit">Diskon (%)</label>
                <input type="number" name="discount" id="modal_discount_edit" min="0" max="100" required>
            </div>
            <button type="submit" class="btn-save tw-btn-green">Simpan</button>
        </form>
        <div id="modalMsgHarga" style="margin-top:12px;"></div>
    </div>
</div>

<div id="modalBackdropHarga" class="modal-backdrop" style="display:none;z-index:9998;"></div>

<style>
/* Set Helvetica as the main font for all elements */
body, html, .stock-management-page, .stock-table, .modal-content, .btn-save, .btn-tambah-stock, .btn-edit-harga, .badge-status, input, button, select, textarea {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
    letter-spacing: 0.01em;
}

.stock-management-page {
    background: #f8fafc;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    padding: 32px 24px 32px 24px;
    margin-top: 32px;
    margin-bottom: 32px;
    max-width: 1100px;
    margin-left: auto;
    margin-right: auto;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
}
.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d2d2d;
    margin-bottom: 18px;
    margin-top: 0;
    letter-spacing: 0.5px;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
}
.stock-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.stock-table th, .stock-table td {
    padding: 14px 18px;
    text-align: left;
    border-bottom: 1px solid #f1f1f1;
    font-size: 15px;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
}
.stock-table th {
    background: #f3f4f6;
    font-weight: 700;
    color: #89193d;
    border-top: 1px solid #e5e7eb;
}
.stock-table tr:last-child td {
    border-bottom: none;
}
.stock-table tr:hover {
    background: #f3f4f6;
    transition: background 0.2s;
}
.badge-status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    min-width: 90px;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
}
.badge-published {
    background: #e6fbe9;
    color: #28a745;
}
.badge-low {
    background: #fffbe6;
    color: #ffc107;
}
.badge-draft {
    background: #f3f4f6;
    color: #6c757d;
}
.dashboard-btn {
    background: var(--primary) !important;
    color: #fff !important;
    border: 1px solid #89193d !important;
    border-radius: 6px;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 6px rgba(60,145,230,0.08);
    margin-bottom: 4px;
    opacity: 1 !important;
    visibility: visible !important;
}
.dashboard-btn:hover {
    background: var(--primary-dark) !important;
    box-shadow: 0 4px 12px rgba(60,145,230,0.13);
}
.btn-save {
    background: var(--success);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 28px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(54,179,126,0.12);
    margin-top: 10px;
}
.btn-save:hover {
    background: var(--green-dark);
    box-shadow: 0 4px 16px rgba(54,179,126,0.18);
}
#modalMsg, #modalMsgStock, #modalMsgHarga {
    font-size: 14px;
    color: #28a745;
    font-weight: 500;
}

/* --- MODAL STYLING --- */
.modal-stock {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.18);
    animation: modalFadeIn 0.2s;
}
.modal-stock[style*="display: flex"] {
    display: flex !important;
}
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.18);
    z-index: 9998;
    display: none;
}
.modal-backdrop[style*="display: block"] {
    display: block !important;
}
.modal-content {
    background: #fff;
    border-radius: 18px;
    padding: 36px 32px 28px 32px;
    min-width: 340px;
    max-width: 95vw;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18);
    position: relative;
    animation: modalPopIn 0.25s cubic-bezier(.18,.89,.32,1.28);
    display: flex;
    flex-direction: column;
    align-items: stretch;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
}
@keyframes modalPopIn {
    from { transform: scale(0.92) translateY(40px); opacity: 0; }
    to { transform: scale(1) translateY(0); opacity: 1; }
}
.close-modal {
    position: absolute;
    top: 16px;
    right: 22px;
    font-size: 2.2rem;
    color: #bbb;
    cursor: pointer;
    font-weight: 700;
    transition: color 0.2s, transform 0.2s;
    z-index: 2;
}
.close-modal:hover {
    color: #e11d48;
    transform: rotate(90deg) scale(1.1);
}
.modal-content h3 {
    margin-top: 0;
    margin-bottom: 22px;
    font-size: 1.25rem;
    font-weight: 700;
    color: #89193d;
    text-align: center;
    letter-spacing: 0.5px;
}
.modal-content .form-group {
    margin-bottom: 18px;
}
.modal-content label {
    display: block;
    margin-bottom: 7px;
    font-weight: 500;
    color: #333;
    font-size: 15px;
}
.modal-content input[type="number"], .modal-content .input-disabled {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    font-size: 15px;
    background: #f9fafb;
    transition: border 0.2s;
}
.modal-content input[type="number"]:focus {
    border-color: #89193d;
    outline: none;
}
.input-disabled {
    background: #f3f4f6;
    color: #888;
}
.input-plusminus-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.input-plusminus-group input[type="number"] {
    width: 70px;
    text-align: center;
    font-size: 16px;
    padding: 8px 0;
    margin: 0 2px;
}
.btn-minus, .btn-plus {
    background: #f3f4f6;
    color: #444;
    border: 1.5px solid #e5e7eb;
    border-radius: 6px;
    width: 36px;
    height: 36px;
    font-size: 20px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.18s, border 0.18s;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
}
.btn-minus:hover, .btn-plus:hover {
    background: #fde68a;
    border-color: #facc15;
    color: #b45309;
}
.search-bar input[type="text"] {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
}
.pagination-container {
    margin-top: 18px;
}
.pagination-btn {
    display: inline-block;
    padding: 7px 16px;
    margin: 0 2px;
    border-radius: 6px;
    background: #f3f4f6;
    color: #89193d;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    border: 1px solid #e5e7eb;
    transition: background 0.18s, color 0.18s;
}
.pagination-btn.active,
.pagination-btn:hover {
    background: #89193d;
    color: #fff;
    border-color: #89193d;
}
.filter-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-end;
    gap: 10px;
    margin-bottom: 18px;
}
.filter-bar-left {
    flex: 1 1 220px;
    min-width: 180px;
}
.filter-bar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.filter-label {
    font-size: 15px;
    font-weight: 500;
    color: #89193d;
    margin-right: 4px;
    letter-spacing: 0.01em;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
}
.filter-input {
    padding: 8px 14px;
    border-radius: 7px;
    border: 1.5px solid #e5e7eb;
    font-size: 15px;
    background: #f9fafb;
    color: #2d2d2d;
    transition: border 0.18s, box-shadow 0.18s;
    outline: none;
    min-width: 180px;
    width: 220px;
}
.filter-input:focus {
    border-color: #89193d;
    box-shadow: 0 2px 8px rgba(137,25,61,0.07);
}
.filter-select {
    padding: 8px 12px;
    border-radius: 7px;
    border: 1.5px solid #e5e7eb;
    font-size: 15px;
    background: #f3f4f6;
    color: #2d2d2d;
    transition: border 0.18s, box-shadow 0.18s;
    outline: none;
    min-width: 150px;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
}
.filter-select:focus {
    border-color: #89193d;
    box-shadow: 0 2px 8px rgba(137,25,61,0.07);
}
@media (max-width: 900px) {
    .stock-management-page {
        padding: 18px 6px;
    }
    .stock-table th, .stock-table td {
        padding: 10px 6px;
        font-size: 14px;
    }
    .section-title {
        font-size: 1.2rem;
    }
    .modal-content {
        min-width: 90vw;
        padding: 18px 8px 18px 8px;
    }
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .filter-bar-right {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .filter-label {
        margin-bottom: 2px;
    }
}
@media (max-width: 600px) {
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .modal-content {
        min-width: 98vw;
        padding: 12px 4vw 12px 4vw;
    }
    .close-modal {
        top: 8px;
        right: 12px;
        font-size: 2rem;
    }
}

/* Tombol kuning untuk Tambah Stock */
.btn-tambah-stock.btn-soft-yellow {
    display: inline-block;
    min-width: 120px;
    background: #fef9c3;
    color: #b45309;
    border: 1.5px solid #fde68a;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    padding: 10px 0;
    margin: 0 auto;
    opacity: 1;
    box-shadow: 0 2px 8px rgba(253,230,138,0.10);
    cursor: pointer;
    text-align: center;
    transition: background 0.2s, box-shadow 0.2s;
}
.btn-tambah-stock.btn-soft-yellow:hover {
    background: #fde68a;
    color: #a16207;
    border-color: #facc15;
}

/* Tombol hijau untuk Ubah Harga */
.btn-edit-harga.btn-soft-green {
    display: inline-block;
    min-width: 120px;
    background: #d1fae5;
    color: #047857;
    border: 1.5px solid #6ee7b7;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    padding: 10px 0;
    margin: 0 auto;
    opacity: 1;
    box-shadow: 0 2px 8px rgba(16,185,129,0.10);
    cursor: pointer;
    text-align: center;
    transition: background 0.2s, box-shadow 0.2s;
}
.btn-edit-harga.btn-soft-green:hover {
    background: #6ee7b7;
    color: #065f46;
    border-color: #34d399;
}

/* Hapus style lama agar tidak bentrok */
.dashboard-btn, .btn-stock-action {
    background: none !important;
    color: inherit !important;
    border: none !important;
    box-shadow: none !important;
    opacity: 1 !important;
    visibility: visible !important;
    min-width: unset !important;
    padding: unset !important;
    margin: unset !important;
}

/* Tailwind-inspired button for modal Tambah Stock */
.tw-btn-yellow {
    background-color: #fde68a !important; /* yellow-200 */
    color: #b45309 !important;            /* yellow-800 */
    border: 1.5px solid #facc15 !important; /* yellow-400 */
    border-radius: 8px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    padding: 12px 28px !important;
    margin-top: 10px !important;
    box-shadow: 0 2px 8px rgba(253,230,138,0.10) !important;
    transition: background 0.2s, box-shadow 0.2s !important;
    display: block !important;
    width: 100% !important;
}
.tw-btn-yellow:hover {
    background-color: #facc15 !important; /* yellow-400 */
    color: #a16207 !important;            /* yellow-700 */
    border-color: #eab308 !important;     /* yellow-500 */
}

/* Tailwind-inspired button for modal Edit Harga */
.tw-btn-green {
    background-color: #6ee7b7 !important; /* green-300 */
    color: #065f46 !important;            /* green-800 */
    border: 1.5px solid #34d399 !important; /* green-400 */
    border-radius: 8px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    padding: 12px 28px !important;
    margin-top: 10px !important;
    box-shadow: 0 2px 8px rgba(16,185,129,0.10) !important;
    transition: background 0.2s, box-shadow 0.2s !important;
    display: block !important;
    width: 100% !important;
}
.tw-btn-green:hover {
    background-color: #34d399 !important; /* green-400 */
    color: #047857 !important;            /* green-700 */
    border-color: #059669 !important;     /* green-600 */
}
</style>

<script>
// Pastikan script dijalankan setelah DOM siap
document.addEventListener('DOMContentLoaded', function() {
    // Tambah Stock Modal
    document.querySelectorAll('.btn-tambah-stock').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('modal_id_product_stock').value = this.getAttribute('data-id');
            document.getElementById('modal_nama_stock').value = this.getAttribute('data-nama');
            document.getElementById('modal_add_stock').value = '';
            document.getElementById('tambahStockModal').style.display = 'flex';
            document.getElementById('modalBackdropStock').style.display = 'block';
            document.getElementById('modalMsgStock').textContent = '';
        });
    });
    document.getElementById('closeTambahStockBtn').onclick = closeTambahStock;
    document.getElementById('modalBackdropStock').onclick = closeTambahStock;
    function closeTambahStock() {
        document.getElementById('tambahStockModal').style.display = 'none';
        document.getElementById('modalBackdropStock').style.display = 'none';
    }
    document.getElementById('tambahStockForm').onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('modal_id_product_stock').value;
        const add_stock = document.getElementById('modal_add_stock').value;
        const formData = new FormData();
        formData.append('tambah_stock', 1);
        formData.append('id_product', id);
        formData.append('add_stock', add_stock);

        fetch('stock_management.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalMsgStock').textContent = 'Stock berhasil ditambah!';
                // Update stock di tabel
                const row = document.querySelector('tr[data-id="'+id+'"]');
                if (row) {
                    const stockTd = row.querySelector('.td-stock');
                    stockTd.textContent = parseInt(stockTd.textContent) + parseInt(add_stock);
                }
                setTimeout(closeTambahStock, 900);
            } else {
                document.getElementById('modalMsgStock').textContent = 'Berhasil tambah stock!';
            }
        })
        .catch(() => {
            document.getElementById('modalMsgStock').textContent = 'Berhasil tambah stock!';
        });
    };

    // Edit Harga Modal
document.querySelectorAll('.btn-edit-harga').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('modal_id_product_harga').value = this.getAttribute('data-id');
        document.getElementById('modal_nama_harga').value = this.getAttribute('data-nama');
        document.getElementById('modal_price_edit').value = this.getAttribute('data-price');
        document.getElementById('modal_discount_edit').value = this.getAttribute('data-discount');
        document.getElementById('editHargaModal').style.display = 'flex';
        document.getElementById('modalBackdropHarga').style.display = 'block';
        document.getElementById('modalMsgHarga').textContent = '';
    });
});

document.getElementById('closeEditHargaBtn').onclick = closeEditHarga;
document.getElementById('modalBackdropHarga').onclick = closeEditHarga;

function closeEditHarga() {
    document.getElementById('editHargaModal').style.display = 'none';
    document.getElementById('modalBackdropHarga').style.display = 'none';
}

document.getElementById('editHargaForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('modal_id_product_harga').value;
    const price = document.getElementById('modal_price_edit').value;
    const discount = document.getElementById('modal_discount_edit').value;

    console.log(`ID: ${id}, Price: ${price}, Discount: ${discount}`);  // Log data yang dikirim

    const formData = new FormData();
    formData.append('edit_harga', 1);
    formData.append('id_product', id);
    formData.append('price', price);
    formData.append('discount', discount);

    fetch('stock_management.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalMsgHarga').textContent = 'Harga berhasil diupdate!';
            const row = document.querySelector('tr[data-id="'+id+'"]');
            if (row) {
                row.querySelector('.td-price').textContent = 'Rp' + Number(price).toLocaleString('id-ID');
                row.querySelector('.td-discount').textContent = discount + '%'; // Update diskon
            }
            setTimeout(closeEditHarga, 900);
        } else {
            document.getElementById('modalMsgHarga').textContent = 'Terjadi kesalahan saat memperbarui harga.';
            console.error(data.error);  // Menampilkan error di console jika ada
        }
    })
    .catch(() => {
        document.getElementById('modalMsgHarga').textContent = ' memperbarui harga.';
    });
};



    // Plus Minus for Jumlah Tambah Stock
    var minusBtn = document.getElementById('btnMinusStock');
    var plusBtn = document.getElementById('btnPlusStock');
    var stockInput = document.getElementById('modal_add_stock');
    if (minusBtn && plusBtn && stockInput) {
        minusBtn.onclick = function() {
            let val = parseInt(stockInput.value) || 1;
            if (val > 1) stockInput.value = val - 1;
        };
        plusBtn.onclick = function() {
            let val = parseInt(stockInput.value) || 1;
            stockInput.value = val + 1;
        };
    }

    // Filter & Search functionality for table
    var searchInput = document.getElementById('searchInput');
    var filterCategory = document.getElementById('filterCategory');
    var filterStatus = document.getElementById('filterStatus');
    var filterStock = document.getElementById('filterStock');
    var table = document.getElementById('stockTable');
    var tbody = table.querySelector('tbody');

    function filterTable() {
        var searchVal = searchInput.value.toLowerCase();
        var catVal = filterCategory.value;
        var statVal = filterStatus.value;
        var trs = Array.from(tbody.querySelectorAll('tr'));

        // Filter rows
        trs.forEach(function(tr) {
            var nama = tr.querySelector('td') ? tr.querySelector('td').textContent.toLowerCase() : '';
            var cat = tr.children[1] ? tr.children[1].textContent : '';
            var stat = tr.children[4] ? tr.children[4].textContent.toLowerCase() : '';
            var show = true;
            if (searchVal && nama.indexOf(searchVal) === -1) show = false;
            if (catVal && cat !== catVal) show = false;
            if (statVal && stat.indexOf(statVal.toLowerCase()) === -1) show = false;
            tr.style.display = show ? '' : 'none';
        });

        // Stock sorting
        if (filterStock.value) {
            var rows = trs.filter(function(tr) { return tr.style.display !== 'none'; });
            rows.sort(function(a, b) {
                var stockA = parseInt(a.querySelector('.td-stock').textContent) || 0;
                var stockB = parseInt(b.querySelector('.td-stock').textContent) || 0;
                return filterStock.value === 'desc' ? stockB - stockA : stockA - stockB;
            });
            rows.forEach(function(tr) { tbody.appendChild(tr); });
        }
    }

    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (filterCategory) filterCategory.addEventListener('change', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
    if (filterStock) filterStock.addEventListener('change', filterTable);
});
</script>

<?php include '../views/footer.php'; ?>