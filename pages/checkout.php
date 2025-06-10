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
$stmt = $conn->prepare("SELECT ci.qty, ci.price, p.id_product, p.namaproduct FROM cart_item ci
    JOIN product p ON ci.product_id = p.id_product
    WHERE ci.cart_id = ?");
$stmt->execute([$cart_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['qty'] * $item['price'];
}

// PERBAIKAN: Proses pengurangan stok ketika bukti bayar berhasil diupload
if (isset($_POST['process_payment']) && $_POST['process_payment'] == 'qris') {
    try {
        // Mulai transaction untuk memastikan konsistensi data
        $conn->beginTransaction();
        
        // Ubah status cart menjadi 'Processing'
        $updateCartStatus = $conn->prepare("UPDATE cart SET status = 'Processing' WHERE id = ?");
        $updateCartStatus->execute([$cart_id]);

        // Pengurangan Stok untuk setiap item di cart_item
        foreach ($cart_items as $item) {
            // Cek stok tersedia dulu
            $checkStock = $conn->prepare("SELECT stock FROM product WHERE id_product = ?");
            $checkStock->execute([$item['id_product']]);
            $currentStock = $checkStock->fetchColumn();
            
            if ($currentStock < $item['qty']) {
                throw new Exception("Stok produk {$item['namaproduct']} tidak mencukupi!");
            }
            
            // Mengurangi stok produk berdasarkan quantity yang dibeli
            $stmtStock = $conn->prepare("UPDATE product SET stock = stock - ? WHERE id_product = ?");
            $stmtStock->execute([$item['qty'], $item['id_product']]);
        }
        
        // Commit transaction
        $conn->commit();
        
        // Redirect ke halaman sukses atau tampilkan pesan
        echo "<div class='text-center py-12 text-green-500'>Pembayaran berhasil diproses, stok produk sudah berkurang.</div>";
        echo "<script>setTimeout(function(){ window.location.href = 'succes_page.php'; }, 2000);</script>";
        exit;
        
    } catch (Exception $e) {
        // Rollback jika ada error
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
        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        #qrisModal {
            display: none;
        }
    </style>
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
        <h2 class="text-lg font-semibold mb-4">Select Payment Method</h2>
        <!-- PERBAIKAN: Hapus event.preventDefault() agar form bisa submit -->
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
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2 rounded bg-pink-600 text-white font-semibold hover:bg-pink-700 transition">
                    Next
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Bayar dengan QRIS -->
    <div id="qrisModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 px-2 md:px-64" style="display: none;">
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
            
            <!-- PERBAIKAN: Form upload bukti sekaligus proses pembayaran -->
            <form method="post" enctype="multipart/form-data" class="space-y-4 mt-6" id="buktiForm">
                <input type="hidden" name="process_payment" value="qris">
                <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                
                <label class="block">
                    <span class="block text-sm font-medium text-gray-700 mb-1">Upload Payment Receipt</span>
                    <input type="file" name="bukti_bayar" accept="image/*" required
                        class="block w-full text-sm text-gray-700
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-pink-50 file:text-pink-700
                        hover:file:bg-pink-100
                        transition-colors duration-150
                        cursor-pointer
                        "
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

    <div class="flex justify-end">
        <a href="cart_page.php" class="px-5 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 mr-2">Back to Cart</a>
    </div>
</div>

<script>
function handlePaymentSubmit() {
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
    if (selectedPayment === 'qris') {
        showQrisModal();
        return false; // Prevent form submission, show modal instead
    }
    return true; // Allow form submission for other payment methods
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