<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

function format_rupiah($angka) {
    return "Rp" . number_format($angka, 0, ',', '.');
}
$subtotal = 0;
$total_savings = 0;
?>

<?php if (empty($cart)): ?>
    <div class="text-center py-12 text-gray-500">Keranjang Anda kosong.</div>
<?php else: ?>
    <div class="divide-y divide-gray-200">
        <?php foreach ($cart as $item): ?>
        <?php
            $harga_asli = isset($item['original_price']) ? $item['original_price'] : $item['price'];
            $subtotal += $item['qty'] * $item['price'];
            $total_savings += $item['qty'] * max(0, $harga_asli - $item['price']);
            // Ambil path gambar, sesuai yang disimpan
            $gambar = isset($item['image']) ? $item['image'] : (isset($item['foto']) ? $item['foto'] : '');
            $imageURL = (!empty($gambar)) ? '../uploads/' . $gambar : 'https://via.placeholder.com/50x50?text=No+Image';
        ?>
        <div class="flex py-4 items-center">
            <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center border rounded-lg bg-white mr-4">
                <img src="<?= htmlspecialchars($imageURL) ?>" alt="" class="object-contain h-16 w-16" />
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <?php if (isset($item['original_price']) && $item['original_price'] > $item['price']): ?>
                            <span class="inline-block bg-pink-500 text-white text-xs font-semibold rounded-full px-2 py-0.5 mb-1 align-middle">On sale</span>
                        <?php endif; ?>
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($item['name']) ?></div>
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
                        <?php if (isset($item['original_price']) && $item['original_price'] > $item['price']): ?>
                            <span class="line-through text-gray-400 text-sm"><?= format_rupiah($item['original_price']) ?></span>
                        <?php endif; ?>
                        <span class="text-lg font-bold text-gray-900"><?= format_rupiah($item['price']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <hr class="my-4" />
    <div class="flex justify-between items-center mb-2">
        <span class="text-gray-800 font-medium">Total</span>
        <span class="text-gray-800 font-medium"><?= format_rupiah($subtotal) ?></span>
    </div>
    <?php if($total_savings > 0): ?>
    <div class="flex justify-between items-center mb-4">
        <span class="font-bold text-lg">Total savings:</span>
        <span class="text-pink-500 text-lg font-bold bg-pink-100 px-3 py-1.5 rounded-full"><?= format_rupiah($total_savings) ?></span>
    </div>
    <?php endif; ?>
<?php endif; ?>
