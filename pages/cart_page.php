<?php
session_start();
require_once '../configdb.php';

// Redirect jika belum login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// Cari cart aktif
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

if (!$cart_id) {
    $cart_items = [];
    $subtotal = 0;
} else {
    // Ambil item cart
    $stmt = $conn->prepare("SELECT ci.id, ci.qty, ci.price, p.namaproduct, p.image FROM cart_item ci
        JOIN product p ON ci.product_id = p.id_product
        WHERE ci.cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $subtotal = 0;
    foreach ($cart_items as $item) {
        $subtotal += $item['qty'] * $item['price'];
    }
}

include '../views/navbar.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8 text-pink-700">Shopping Cart</h1>
    <?php if (empty($cart_items)): ?>
        <div class="text-center py-12 text-gray-400 bg-pink-50 rounded-lg shadow-inner">Keranjang Anda kosong.</div>
    <?php else: ?>
        <form method="post" id="cartForm">
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-pink-100">
            <div class="flex items-center mb-4 gap-4">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="selectAll" class="accent-pink-500 w-5 h-5 rounded-full border-2 border-pink-400 shadow-sm transition-all duration-150" onclick="toggleSelectAll(this)">
                    <span class="text-sm text-pink-700 font-semibold">Select All</span>
                </label>
                <button type="button" id="deleteSelectedBtn" style="display:none"
                    class="bg-transparent border-0 text-pink-600 font-semibold text-sm underline hover:text-pink-700 focus:outline-none px-0 py-0 shadow-none ml-2"
                    onclick="deleteSelectedItems()" disabled>
                    Hapus Item
                </button>
            </div>
            <div class="divide-y divide-pink-100">
                <?php foreach ($cart_items as $item): ?>
                    <?php
                        $imageURL = (!empty($item['image'])) ? '../uploads/' . $item['image'] : 'https://via.placeholder.com/50x50?text=No+Image';
                    ?>
                    <div class="flex py-4 items-center hover:bg-pink-50 rounded-lg transition" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                        <label class="flex items-center mr-4 cursor-pointer select-none">
                            <input type="checkbox" name="selected_items[]" value="<?= $item['id'] ?>" class="item-checkbox accent-pink-500 w-5 h-5 rounded-full border-2 border-pink-400 shadow-sm transition-all duration-150" onchange="updateDeleteBtn()">
                        </label>
                        <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center border border-pink-100 rounded-lg bg-white mr-4 shadow-sm">
                            <img src="<?= htmlspecialchars($imageURL) ?>" alt="" class="object-contain h-16 w-16 rounded-md" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-semibold text-pink-800 text-base"><?= htmlspecialchars($item['namaproduct']) ?></div>
                                </div>
                                <button class="text-gray-400 hover:text-pink-500 transition ml-2 remove-btn" aria-label="Remove" data-id="<?= $item['id'] ?>">
                                    <span class="sr-only">Remove</span>
                                    <i class="fas fa-times-circle text-xl"></i>
                                </button>
                            </div>
                            <div class="flex items-center mt-3">
                                <div class="flex items-center border rounded-lg bg-pink-50 px-3 py-1" style="min-width:110px;">
                                    <button type="button" class="text-xl text-gray-700 hover:text-pink-600 px-2 minus-btn" data-id="<?= $item['id'] ?>" style="background:none;border:none;">-</button>
                                    <input type="text" value="<?= $item['qty'] ?>" min="1" readonly class="w-8 text-center bg-transparent border-0 text-gray-900 font-semibold qty-input" data-id="<?= $item['id'] ?>" style="outline:none;" />
                                    <button type="button" class="text-xl text-gray-700 hover:text-pink-600 px-2 plus-btn" data-id="<?= $item['id'] ?>" style="background:none;border:none;">+</button>
                                </div>
                                <div class="ml-auto space-x-2 flex items-center">
                                    <span class="text-lg font-bold text-pink-700 item-total" data-id="<?= $item['id'] ?>">Rp<?= number_format($item['qty'] * $item['price'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr class="my-4 border-pink-200" />
            <div class="flex justify-between items-center mb-2">
                <span class="text-pink-800 font-semibold">Total</span>
                <span class="text-pink-700 font-bold text-xl" id="cart-subtotal">Rp<?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" id="proceedCheckoutBtn" class="px-6 py-2 rounded-lg bg-pink-600 text-white font-semibold hover:bg-pink-700 transition shadow">
                    <i class="fas fa-credit-card mr-1"></i> Proceed to Checkout
                </button>
            </div>
        </div>
        </form>
    <?php endif; ?>
    <a href="nowShop.php" class="inline-block mt-4 text-pink-600 hover:underline font-semibold"><i class="fas fa-arrow-left"></i>Continue Shopping</a>
</div>
<script>
document.querySelectorAll('.plus-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        updateQty(id, 'plus');
    });
});
document.querySelectorAll('.minus-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        updateQty(id, 'minus');
    });
});
document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        if (confirm('Hapus item ini dari keranjang?')) {
            removeCartItem(id);
        }
    });
});

function removeCartItem(id) {
    var fd = new FormData();
    fd.append('action', 'delete');
    fd.append('cart_item_id', id);
    fetch('../cart/cart_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Hapus baris dari DOM
                const row = document.querySelector('[data-id="'+id+'"]');
                if (row) row.remove();
                // Jika sudah tidak ada item, reload halaman/cart
                if (document.querySelectorAll('.remove-btn').length === 0) {
                    location.reload();
                    return;
                }
                // Update subtotal
                updateSubtotal();
                // Update badge jika ada
                if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
                    document.getElementById('cart-count-badge').textContent = data.cart_count;
                }
            } else {
                alert(data.message || 'Gagal menghapus item.');
            }
        });
}

function updateQty(id, action) {
    var fd = new FormData();
    fd.append('action', action);
    fd.append('cart_item_id', id);
    fetch('../cart/cart_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (action === 'minus' && data.cart_count === 0) {
                    location.reload();
                    return;
                } else {
                    let qtyInput = document.querySelector('.qty-input[data-id="'+id+'"]');
                    let itemDiv = document.querySelector('[data-id="'+id+'"]');
                    let price = parseInt(itemDiv.getAttribute('data-price'));
                    if (action === 'plus') {
                        qtyInput.value = parseInt(qtyInput.value) + 1;
                    } else if (action === 'minus' && parseInt(qtyInput.value) > 1) {
                        qtyInput.value = parseInt(qtyInput.value) - 1;
                    }
                    // Update item total
                    let itemTotal = document.querySelector('.item-total[data-id="'+id+'"]');
                    itemTotal.textContent = 'Rp' + numberWithSeparator(qtyInput.value * price);
                }
                // Update subtotal
                updateSubtotal();
                // Update badge jika ada
                if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
                    document.getElementById('cart-count-badge').textContent = data.cart_count;
                }
            } else {
                alert(data.message || 'Gagal update keranjang.');
            }
        });
}

function updateSubtotal() {
    let total = 0;
    document.querySelectorAll('.qty-input').forEach(function(input) {
        let qty = parseInt(input.value);
        let itemDiv = document.querySelector('[data-id="'+input.dataset.id+'"]');
        let price = parseInt(itemDiv.getAttribute('data-price'));
        total += qty * price;
    });
    document.getElementById('cart-subtotal').textContent = 'Rp' + numberWithSeparator(total);
}

function numberWithSeparator(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Checkbox logic
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateDeleteBtn();
}
function updateDeleteBtn() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const btn = document.getElementById('deleteSelectedBtn');
    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
    btn.disabled = !anyChecked;
    btn.style.display = anyChecked ? 'inline-block' : 'none';
    document.getElementById('selectAll').checked = Array.from(checkboxes).every(cb => cb.checked);
}

// Proceed to checkout: jika ada item yang di-select (1 atau lebih), checkout hanya item yang di-select
// jika tidak ada yang di-select, checkout semua item
document.getElementById('proceedCheckoutBtn')?.addEventListener('click', function() {
    const checked = Array.from(document.querySelectorAll('.item-checkbox:checked'));
    if (checked.length > 0) {
        // Ada satu atau lebih item yang di-select, checkout hanya item yang dipilih
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = 'checkout.php';
        checked.forEach(cb => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_items[]';
            input.value = cb.value;
            form.appendChild(input);
        });
        document.body.appendChild(form);
        form.submit();
    } else {
        // Tidak ada yang di-select, checkout semua item
        window.location.href = 'checkout.php';
    }
});

function deleteSelectedItems() {
    // Ambil semua checkbox yang dicentang
    const checked = Array.from(document.querySelectorAll('.item-checkbox:checked'));
    if (checked.length === 0) return;
    if (!confirm('Hapus item yang dipilih dari keranjang?')) return;

    // Ambil id item yang dipilih
    const ids = checked.map(cb => cb.value);

    var fd = new FormData();
    fd.append('action', 'delete_selected');
    ids.forEach(id => fd.append('selected_items[]', id));

    fetch('../cart/cart_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Hapus baris dari DOM
                ids.forEach(id => {
                    const row = document.querySelector('[data-id="'+id+'"]');
                    if (row) row.remove();
                });
                // Jika sudah tidak ada item, reload halaman/cart
                if (document.querySelectorAll('.remove-btn').length === 0) {
                    location.reload();
                    return;
                }
                // Update subtotal
                updateSubtotal();
                // Update badge jika ada
                if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
                    document.getElementById('cart-count-badge').textContent = data.cart_count;
                }
                // Reset select all
                document.getElementById('selectAll').checked = false;
                updateDeleteBtn();
            } else {
                alert(data.message || 'Gagal menghapus item.');
            }
        });
}
</script>
</body>
</html>
