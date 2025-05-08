<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    echo '<p class="text-gray-700">Keranjang Anda kosong.</p>';
} else {
    foreach ($cart as $item) {
        echo '<div class="flex items-center mb-4">';
        echo '<img src="'.htmlspecialchars($item['foto']).'" class="w-12 h-12 object-cover rounded mr-2" alt="">';
        echo '<div class="flex-1">';
        echo '<div class="font-semibold">'.htmlspecialchars($item['name']).'</div>';
        echo '<div class="text-xs text-gray-500">Rp'.number_format($item['price'],0,',','.').'</div>';
        echo '<div class="flex items-center mt-1">';
        echo '<button onclick="cartMinus('.$item['id'].')" class="px-2 py-1 bg-gray-200 rounded text-xs mr-1">-</button>';
        echo '<input type="text" value="'.$item['qty'].'" min="1" class="w-10 border rounded text-center text-xs" onchange="cartQty('.$item['id'].',this.value)" />';
        echo '<button onclick="cartPlus('.$item['id'].')" class="px-2 py-1 bg-gray-200 rounded text-xs ml-1">+</button>';
        echo '<button onclick="cartDelete('.$item['id'].')" class="ml-2 text-red-500 hover:underline text-xs">Hapus</button>';
        echo '</div>';
        echo '</div></div>';
    }
}
?>