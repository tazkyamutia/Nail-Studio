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

// Ambil item cart
$stmt = $conn->prepare("SELECT ci.qty, ci.price, p.namaproduct FROM cart_item ci
    JOIN product p ON ci.product_id = p.id_product
    WHERE ci.cart_id = ?");
$stmt->execute([$cart_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['qty'] * $item['price'];
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
        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6 text-pink-700">Checkout</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Rincian Pesanan</h2>
        <?php if (empty($cart_items)): ?>
            <div class="text-center py-8 text-gray-500">Keranjang Anda kosong.</div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200 mb-4">
                <?php foreach ($cart_items as $item): ?>
                    <li class="py-2 flex justify-between items-center">
                        <span><?= htmlspecialchars($item['namaproduct']) ?> <span class="text-xs text-gray-500">x<?= $item['qty'] ?></span></span>
                        <span class="font-medium text-gray-800">Rp<?= number_format($item['qty'] * $item['price'], 0, ',', '.') ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex justify-between items-center border-t pt-4">
                <span class="font-semibold text-gray-900">Total</span>
                <span class="font-semibold text-pink-700 text-lg">Rp<?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h2>
        <form>
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer disabled opacity-50">
                    <input type="radio" name="payment_method" value="debit" disabled class="accent-pink-500" />
                    <span class="text-gray-700 font-medium">Debit / Credit Card</span>
                    <span class="ml-2 text-xs bg-gray-200 text-gray-500 px-2 py-1 rounded">Coming Soon</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="payment_method" value="qris" checked class="accent-pink-500" />
                    <span class="text-gray-700 font-medium">QRIS (E-wallet & Bank)</span>
                    <img src="../qris/qris.png" alt="QRIS" class="w-10 h-10 object-contain ml-2" />
                </label>
            </div>
        </form>
    </div>

    <div id="qris-section" class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Bayar dengan QRIS</h2>
        <div class="flex flex-col md:flex-row items-center gap-8">
            <img src="../qris/Screenshot_20250607_161241_GoPay Merchant.jpg" alt="QRIS" class="w-56 h-56 object-contain border rounded-lg bg-white shadow" />
            <div>
                <p class="mb-2 text-gray-700">Scan kode QRIS di samping menggunakan aplikasi e-wallet atau mobile banking Anda (GoPay, OVO, DANA, ShopeePay, dll).</p>
                <ul class="text-sm text-gray-600 mb-2 list-disc ml-5">
                    <li>Pastikan nominal pembayaran sesuai: <span class="font-semibold text-pink-700">Rp<?= number_format($subtotal, 0, ',', '.') ?></span></li>
                    <li>Setelah pembayaran, konfirmasi ke admin jika diperlukan.</li>
                </ul>
                <div class="mt-4">
                    <span class="inline-block bg-pink-100 text-pink-700 px-3 py-1 rounded text-xs font-semibold">QRIS Aktif 24 Jam</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <a href="cart_page.php" class="px-5 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 mr-2">Kembali ke Keranjang</a>
        <button type="button" class="px-6 py-2 rounded bg-pink-600 text-white font-semibold hover:bg-pink-700 transition disabled:opacity-50" disabled>
            Bayar Sekarang
        </button>
    </div>
</div>
</body>
</html>
