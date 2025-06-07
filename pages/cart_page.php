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
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8 text-pink-700">Keranjang Belanja</h1>
    <?php if (empty($cart_items)): ?>
        <div class="text-center py-12 text-gray-400 bg-pink-50 rounded-lg shadow-inner">Keranjang Anda kosong.</div>
    <?php else: ?>
        <form method="post" id="cartForm">
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-pink-100">
            <div class="flex items-center mb-4 gap-4">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="selectAll" class="accent-pink-500 w-5 h-5 rounded-full border-2 border-pink-400 shadow-sm transition-all duration-150" onclick="toggleSelectAll(this)">
                    <span class="text-sm text-pink-700 font-semibold">Pilih Semua</span>
                </label>
                <button type="submit" name="delete_selected" id="deleteSelectedBtn" style="display:none"
                    class="bg-transparent border-0 text-pink-600 font-semibold text-sm underline hover:text-pink-700 focus:outline-none px-0 py-0 shadow-none ml-2">
                    Hapus Item
                </button>
            </div>
            <div class="divide-y divide-pink-100">
                <?php foreach ($cart_items as $item): ?>
                    <?php
                        $imageURL = (!empty($item['image'])) ? '../uploads/' . $item['image'] : 'https://via.placeholder.com/50x50?text=No+Image';
                    ?>
                    <div class="flex py-4 items-center hover:bg-pink-50 rounded-lg transition">
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
                                <form method="post" action="" onsubmit="return confirm('Hapus item ini dari keranjang?');" style="margin:0;">
                                    <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="text-gray-400 hover:text-pink-500 transition ml-2" aria-label="Remove">
                                        <span class="sr-only">Remove</span>
                                        <i class="fas fa-times-circle text-xl"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-center mt-3">
                                <form method="post" action="" class="flex items-center gap-2" style="margin:0;">
                                    <input type="hidden" name="update_id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="minus" class="w-8 h-8 flex items-center justify-center text-xl text-pink-700 hover:text-white hover:bg-pink-500 bg-pink-100 rounded-full transition shadow">-</button>
                                    <input type="text" name="qty" value="<?= $item['qty'] ?>" min="1" class="w-10 h-8 border-0 text-center text-pink-700 text-sm bg-pink-50 focus:ring-2 focus:ring-pink-300 rounded shadow" style="border:1px solid #fbcfe8;" />
                                    <button type="submit" name="plus" class="w-8 h-8 flex items-center justify-center text-xl text-pink-700 hover:text-white hover:bg-pink-500 bg-pink-100 rounded-full transition shadow">+</button>
                                </form>
                                <div class="ml-auto space-x-2 flex items-center">
                                    <span class="text-lg font-bold text-pink-700">Rp<?= number_format($item['price'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr class="my-4 border-pink-200" />
            <div class="flex justify-between items-center mb-2">
                <span class="text-pink-800 font-semibold">Total</span>
                <span class="text-pink-700 font-bold text-xl">Rp<?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-end mt-6">
                <a href="checkout.php" class="px-6 py-2 rounded-lg bg-pink-600 text-white font-semibold hover:bg-pink-700 transition shadow">
                    <i class="fas fa-credit-card mr-1"></i> Proceed to Checkout
                </a>
            </div>
        </div>
        </form>
    <?php endif; ?>
    <a href="nowShop.php" class="inline-block mt-4 text-pink-600 hover:underline font-semibold"><i class="fas fa-arrow-left"></i> Lanjut Belanja</a>
</div>
<script>
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
</script>
<?php
// Handle update qty & delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $del_id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM cart_item WHERE id = ? AND cart_id = ?");
        $stmt->execute([$del_id, $cart_id]);
        echo "<script>location.href='cart_page.php';</script>";
        exit;
    }
    if (isset($_POST['update_id'])) {
        $item_id = intval($_POST['update_id']);
        $qty = isset($_POST['qty']) ? max(1, intval($_POST['qty'])) : 1;
        if (isset($_POST['plus'])) $qty++;
        if (isset($_POST['minus'])) $qty = max(1, $qty-1);
        $stmt = $conn->prepare("UPDATE cart_item SET qty = ? WHERE id = ? AND cart_id = ?");
        $stmt->execute([$qty, $item_id, $cart_id]);
        echo "<script>location.href='cart_page.php';</script>";
        exit;
    }
    if (isset($_POST['delete_selected']) && isset($_POST['selected_items'])) {
        $selected_ids = array_map('intval', $_POST['selected_items']);
        $in_query = implode(',', array_fill(0, count($selected_ids), '?'));
        $stmt = $conn->prepare("DELETE FROM cart_item WHERE id IN ($in_query) AND cart_id = ?");
        $stmt->execute(array_merge($selected_ids, [$cart_id]));
        echo "<script>location.href='cart_page.php';</script>";
        exit;
    }
}
?>
</body>
</html>
