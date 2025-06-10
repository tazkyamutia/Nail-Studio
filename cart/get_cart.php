<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../configdb.php';

if (!isset($_SESSION['id'])) {
    echo '<div class="text-center py-12 text-gray-500">Silakan login untuk melihat keranjang Anda.</div>';
    return;
}
$user_id = $_SESSION['id'];

// Cari cart aktif
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

if (!$cart_id) {
    echo '<div class="text-center py-12 text-gray-500">Keranjang Anda kosong.</div>';
    return;
}

// Ambil item cart
$stmt = $conn->prepare("SELECT ci.id, ci.qty, ci.price, p.namaproduct, p.image FROM cart_item ci
    JOIN product p ON ci.product_id = p.id_product
    WHERE ci.cart_id = ?");
$stmt->execute([$cart_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
$total_savings = 0;
?>

<?php if (empty($cart_items)): ?>
    <div class="text-center py-12 text-gray-500">Keranjang Anda kosong.</div>
<?php else: ?>
    <form id="cartModalForm">
    <div class="flex items-center mb-4 gap-4">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" id="modalSelectAll" class="accent-pink-500 w-5 h-5 rounded-full border-2 border-pink-400 shadow-sm transition-all duration-150">
            <span class="text-sm text-pink-700 font-semibold">Select All</span>
        </label>
        <button type="button" class="bg-transparent border-0 text-pink-600 font-semibold text-sm underline hover:text-pink-700 focus:outline-none px-0 py-0 shadow-none ml-2" id="modalDeleteSelectedBtn" style="display:none" disabled onclick="modalDeleteSelected()">Hapus Item</button>
    </div>
    <div class="divide-y divide-gray-200">
        <?php foreach ($cart_items as $item): ?>
        <?php
            $subtotal += $item['qty'] * $item['price'];
            $imageURL = (!empty($item['image'])) ? '../uploads/' . $item['image'] : 'https://via.placeholder.com/50x50?text=No+Image';
        ?>
        <div class="flex py-4 items-center hover:bg-pink-50 rounded-lg transition">
            <label class="flex items-center mr-4 cursor-pointer select-none">
                <input type="checkbox" class="modal-item-checkbox accent-pink-500 w-5 h-5 rounded-full border-2 border-pink-400 shadow-sm transition-all duration-150" value="<?= $item['id'] ?>">
            </label>
            <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center border rounded-lg bg-white mr-4">
                <img src="<?= htmlspecialchars($imageURL) ?>" alt="" class="object-contain h-16 w-16" />
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($item['namaproduct']) ?></div>
                    </div>
                    <button onclick="cartDelete(<?= $item['id'] ?>)" class="text-gray-400 hover:text-pink-500 transition ml-2" aria-label="Remove" type="button">
                        <span class="sr-only">Remove</span>
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center mt-3">
                    <div class="flex items-center border rounded">
                        <button type="button" onclick="cartMinus(<?= $item['id'] ?>, this)" class="w-8 h-8 flex items-center justify-center text-xl text-gray-900 hover:text-pink-600">-</button>
                        <input type="text" value="<?= $item['qty'] ?>" min="1" class="w-10 h-8 border-0 text-center text-gray-900 text-sm bg-transparent focus:ring-0 qty-input" onchange="cartQty(<?= $item['id'] ?>, this.value, this)">
                        <button type="button" onclick="cartPlus(<?= $item['id'] ?>, this)" class="w-8 h-8 flex items-center justify-center text-xl text-gray-900 hover:text-pink-600">+</button>
                    </div>
                    <div class="ml-auto space-x-2 flex items-center">
                        <span class="text-lg font-bold text-gray-900">Rp<?= number_format($item['price'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <hr class="my-4" />
    <div class="flex justify-between items-center mb-2">
        <span class="text-gray-800 font-medium">Total</span>
        <span class="text-gray-800 font-medium">Rp<?= number_format($subtotal, 0, ',', '.') ?></span>
    </div>
    </form>
    <script>
    // Fix: event binding for select all and item checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('modalSelectAll');
        function modalToggleSelectAll() {
            const checkboxes = document.querySelectorAll('.modal-item-checkbox');
            checkboxes.forEach(cb => { cb.checked = selectAll.checked; });
            modalUpdateDeleteBtn();
        }
        function modalUpdateDeleteBtn() {
            const checkboxes = document.querySelectorAll('.modal-item-checkbox');
            const btn = document.getElementById('modalDeleteSelectedBtn');
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            btn.disabled = !anyChecked;
            btn.style.display = anyChecked ? 'inline-block' : 'none';
            // update selectAll checked state
            if (checkboxes.length > 0) {
                selectAll.checked = Array.from(checkboxes).every(cb => cb.checked);
            } else {
                selectAll.checked = false;
            }
        }
        // Bind select all
        selectAll.addEventListener('change', modalToggleSelectAll);
        // Bind each item checkbox
        document.querySelectorAll('.modal-item-checkbox').forEach(function(cb) {
            cb.addEventListener('change', modalUpdateDeleteBtn);
        });
        // Initial state
        modalUpdateDeleteBtn();
        // Expose for inline onchange
        window.modalUpdateDeleteBtn = modalUpdateDeleteBtn;
        window.modalToggleSelectAll = modalToggleSelectAll;
    });
    // Pastikan BASE_CART sudah didefinisikan di navbar.php
    function cartPlus(id, btn) {
        var fd = new FormData();
        fd.append('action', 'plus');
        fd.append('cart_item_id', id);
        fetch((typeof BASE_CART !== "undefined" ? BASE_CART : '../cart/') + 'cart_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update qty input
                    let row = btn.closest('.flex');
                    let qtyInput = row.querySelector('.qty-input');
                    if (qtyInput) qtyInput.value = parseInt(qtyInput.value) + 1;
                    if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
                        document.getElementById('cart-count-badge').textContent = data.cart_count;
                    }
                } else {
                    alert(data.message || 'Gagal menambah qty.');
                }
            });
    }
    function cartMinus(id, btn) {
        var fd = new FormData();
        fd.append('action', 'minus');
        fd.append('cart_item_id', id);
        fetch((typeof BASE_CART !== "undefined" ? BASE_CART : '../cart/') + 'cart_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let row = btn.closest('.flex');
                    let qtyInput = row.querySelector('.qty-input');
                    if (qtyInput && parseInt(qtyInput.value) > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
                    if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
                        document.getElementById('cart-count-badge').textContent = data.cart_count;
                    }
                } else {
                    alert(data.message || 'Gagal mengurangi qty.');
                }
            });
    }
    function cartQty(id, qty, input) {
        var fd = new FormData();
        fd.append('action', 'update');
        fd.append('cart_item_id', id);
        fd.append('qty', qty);
        fetch((typeof BASE_CART !== "undefined" ? BASE_CART : '../cart/') + 'cart_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
                        document.getElementById('cart-count-badge').textContent = data.cart_count;
                    }
                } else {
                    alert(data.message || 'Gagal update qty.');
                }
            });
    }
    </script>
<?php endif; ?>
