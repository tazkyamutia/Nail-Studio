<?php
session_start();
require_once '../configdb.php';

// Redirect jika belum login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// Ambil cart aktif
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

if (!$cart_id) {
    echo "<div class='text-center py-12 text-gray-500'>Keranjang Anda kosong.</div>";
    exit;
}

// Ambil hanya item yang dipilih (jika ada POST selected_items)
$cart_items = [];
if (!empty($_POST['selected_items']) && is_array($_POST['selected_items'])) {
    $ids = array_map('intval', $_POST['selected_items']);
    if (!empty($ids)) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT ci.qty, ci.price, ci.product_id AS id_product, p.discount, p.namaproduct 
                FROM cart_item ci
                JOIN product p ON ci.product_id = p.id_product
                WHERE ci.cart_id = ? AND ci.id IN ($in)";
        $params = array_merge([$cart_id], $ids);
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Jika tidak ada selected_items, tampilkan semua item cart
    $stmt = $conn->prepare("SELECT ci.qty, ci.price, ci.product_id AS id_product, p.discount, p.namaproduct 
                            FROM cart_item ci
                            JOIN product p ON ci.product_id = p.id_product
                            WHERE ci.cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hitung subtotal
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['qty'] * $item['price'];
}

// Proses pengurangan stok ketika bukti bayar berhasil diupload
if (isset($_POST['process_payment']) && $_POST['process_payment'] == 'qris') {
    try {
        $conn->beginTransaction();

        // Update status cart menjadi 'Processing'
        $updateCartStatus = $conn->prepare("UPDATE cart SET status = 'Processing' WHERE id = ?");
        $updateCartStatus->execute([$cart_id]);

        // Proses stok setiap item
        foreach ($cart_items as $item) {
            // Cek stok
            $checkStock = $conn->prepare("SELECT stock FROM product WHERE id_product = ?");
            $checkStock->execute([$item['id_product']]);
            $currentStock = $checkStock->fetchColumn();

            if ($currentStock < $item['qty']) {
                throw new Exception("Stok produk {$item['namaproduct']} tidak mencukupi!");
            }

            // Kurangi stok
            $stmtStock = $conn->prepare("UPDATE product SET stock = stock - ? WHERE id_product = ?");
            $stmtStock->execute([$item['qty'], $item['id_product']]);
        }

        $conn->commit();
        echo "<script>setTimeout(function(){ window.location.href = 'succes_page.php'; }, 2000);</script>";
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div class='text-center py-12 text-red-500'>Error: " . $e->getMessage() . "</div>";
    }
}

include '../views/navbar.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Nail Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .disabled { opacity: 0.5; pointer-events: none; }
        #qrisModal { display: none; }
        [x-cloak] { display: none !important; }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6 text-pink-700">Checkout</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Order Details</h2>
        <?php if (empty($cart_items)): ?>
            <div class="text-center py-8 text-gray-500">Keranjang Anda kosong.</div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200 mb-4">
                <?php foreach ($cart_items as $item): ?>
                    <?php
                        // Calculate the price after discount
                        $productPrice = $item['price'];
                        $productDiscount = $item['discount'];
                        $priceAfterDiscount = $productPrice * (1 - $productDiscount / 100);
                        $priceFormatted = number_format($productPrice, 0, ',', '.');
                        $priceAfterDiscountFormatted = number_format($priceAfterDiscount, 0, ',', '.');
                    ?>
                    <li class="py-2 flex justify-between items-center">
                        <span><?= htmlspecialchars($item['namaproduct']) ?> <span class="text-xs text-gray-500">x<?= $item['qty'] ?></span></span>
                        <span class="font-medium text-gray-800">
                            <?php if ($productDiscount > 0): ?>
                                <!-- Show original price if there's a discount -->
                                <span class="line-through text-gray-500">Rp<?= $priceFormatted ?></span> 
                                <span class="font-semibold text-gray-800">Rp<?= $priceAfterDiscountFormatted ?></span>
                            <?php else: ?>
                                <!-- Show only the regular price if no discount -->
                                Rp<?= $priceFormatted ?>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex justify-between items-center border-t pt-4">
                <span class="font-semibold text-gray-900">Total</span>
                <span class="font-semibold text-pink-700 text-lg">Rp<?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Payment Method Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Select payment options</h2>
            <span class="text-sm text-gray-500">Pembayaran dienkripsi 🔒</span>
        </div>
        <form id="payment-method-form" method="POST" onsubmit="return handlePaymentSubmit();">
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer disabled opacity-50">
                    <input type="radio" name="payment_method" value="debit" disabled class="accent-pink-500" />
                    <span class="text-gray-700 font-medium">Debit / Credit Card</span>
                    <span class="ml-2 text-xs bg-gray-200 text-gray-500 px-2 py-1 rounded">Coming Soon</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="payment_method" value="qris" checked class="accent-pink-500" />
                    <span class="text-gray-700 font-medium">QRIS (E-wallet & Bank)</span>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/QRIS_logo.svg" alt="QRIS Logo" class="w-24 h-24 object-contain" />
                </label>
            </div>
        </form>
    </div>

    <!-- Alamat Pengiriman Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8" x-data="{ showModal: false }">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Shipping Address</h2>
            <button type="button" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-1 rounded text-sm font-semibold" @click="showModal = true">
                Add New Address
            </button>
        </div>
        <?php if (!empty($addresses)): ?>
            <form id="address-form">
                <div class="space-y-3">
                    <?php foreach ($addresses as $i => $addr): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="selected_address" value="<?= $addr['id'] ?>" <?= $i === 0 ? 'checked' : '' ?> class="accent-pink-500" />
                            <span class="text-gray-700"><?= htmlspecialchars($addr['address']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </form>
        <?php else: ?>
            <div class="text-gray-500 mb-2">You don't have a saved address yet.</div>
        <?php endif; ?>

        <!-- Modal Tambah Alamat Baru -->
        <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center z-50">
            <div class="fixed inset-0 bg-black bg-opacity-40" @click="showModal = false"></div>
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md z-10">
                <h3 class="text-lg font-semibold mb-2">Add New Address</h3>
                <form method="post" action="add_address.php" class="space-y-4">
                    <textarea name="address" rows="3" required class="w-full border rounded px-3 py-2"></textarea>
                    <input type="hidden" name="type" value="shipping">
                    <div class="flex justify-end gap-2">
                        <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" @click="showModal = false">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded bg-pink-600 text-white hover:bg-pink-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal QRIS -->
    <div id="qrisModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 px-2 md:px-64" style="display:none;">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-lg font-semibold">Bayar dengan</h2>
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/QRIS_logo.svg" alt="QRIS Logo" class="w-24 h-24 object-contain" />
            </div>
            <div class="flex flex-col md:flex-row items-center gap-8">
                <img src="../qris/Screenshot_20250607_161241_GoPay Merchant.jpg" alt="QRIS" class="w-56 h-56 object-contain border rounded-lg bg-white shadow" />
                <div>
                    <p class="mb-2 text-gray-700">Scan kode QRIS di samping menggunakan aplikasi e-wallet atau mobile banking Anda (GoPay, OVO, DANA, ShopeePay, dll).</p>
                    <ul class="text-sm text-gray-600 mb-2 list-disc ml-5">
                        <li>
                            Nominal yang harus dibayar:
                            <span class="font-extrabold text-green-700 text-2xl font-mono tracking-wider drop-shadow-sm">
                                Rp<?= number_format($subtotal, 0, ',', '.') ?>
                            </span>
                        </li>
                        <li>Setelah pembayaran, upload bukti bayar di bawah.</li>
                    </ul>
                    <div class="mt-4">
                        <span class="inline-block bg-pink-100 text-pink-700 px-3 py-1 rounded text-xs font-semibold">QRIS Aktif 24 Jam</span>
                    </div>
                </div>
            </div>
            <form method="post" enctype="multipart/form-data" class="space-y-4 mt-6" id="buktiForm">
                <input type="hidden" name="process_payment" value="qris">
                <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                <label class="block">
                    <span class="block text-sm font-medium text-gray-700 mb-1">Upload Payment Receipt</span>
                    <input type="file" name="bukti_bayar" accept="image/*" required
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 transition-colors duration-150 cursor-pointer"
                        id="buktiInput"
                    />
                </label>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="hideQrisModal()" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
                    <button type="submit" id="buktiSubmitBtn" class="px-6 py-2 rounded bg-pink-600 text-white font-semibold hover:bg-pink-700 transition" disabled>
                        Complete Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BOTTOM FLEX: Back to Cart & Next -->
    <div class="flex justify-between items-center mt-8">
        <a href="cart_page.php" class="px-5 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Back to Cart</a>
        <button type="submit" form="payment-method-form" class="px-6 py-2 rounded bg-pink-600 text-white font-semibold hover:bg-pink-700 transition">
            Next
        </button>
    </div>
</div>

<script>
function handlePaymentSubmit() {
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
    if (selectedPayment === 'qris') {
        showQrisModal();
        return false;
    }
    return true;
}

function showQrisModal() {
    document.getElementById('qrisModal').style.display = 'flex';
}

function hideQrisModal() {
    document.getElementById('qrisModal').style.display = 'none';
}

document.getElementById('buktiInput').addEventListener('change', function() {
    document.getElementById('buktiSubmitBtn').disabled = !this.files.length;
});
</script>
</body>
</html>
