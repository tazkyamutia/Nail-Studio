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
    <div class="divide-y divide-gray-200">
        <?php foreach ($cart_items as $item): ?>
        <?php
            $subtotal += $item['qty'] * $item['price'];
            $imageURL = (!empty($item['image'])) ? '../uploads/' . $item['image'] : 'https://via.placeholder.com/50x50?text=No+Image';
        ?>
        <div class="flex py-4 items-center">
            <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center border rounded-lg bg-white mr-4">
                <img src="<?= htmlspecialchars($imageURL) ?>" alt="" class="object-contain h-16 w-16" />
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($item['namaproduct']) ?></div>
                    </div>
                    <button onclick="cartDelete(<?= $item['id'] ?>)" class="text-gray-400 hover:text-pink-500 transition ml-2" aria-label="Remove">
                        <span class="sr-only">Remove</span>
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center mt-3">
                    <div class="flex items-center border rounded">
                        <button onclick="cartMinus(<?= $item['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-xl text-gray-900 hover:text-pink-600">-</button>
                        <input type="text" value="<?= $item['qty'] ?>" min="1" class="w-10 h-8 border-0 text-center text-gray-900 text-sm bg-transparent focus:ring-0" onchange="cartQty(<?= $item['id'] ?>,this.value)" />
                        <button onclick="cartPlus(<?= $item['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-xl text-gray-900 hover:text-pink-600">+</button>
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
<?php endif; ?>
