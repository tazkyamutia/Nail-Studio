<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalItems = array_sum(array_column($cart, 'qty'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nail Art Studio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  />
  <style>
    #cart-modal {
      position: fixed;
      top: 0;
      right: -100%;
      width: 30%;
      max-width: 400px;
      height: 100%;
      background-color: white;
      box-shadow: -2px 0 5px rgba(0,0,0,0.1);
      overflow-y: auto;
      transition: right 0.3s cubic-bezier(0.4,0,0.2,1);
      z-index: 50;
    }
    #cart-modal.open { right: 0; }
    #cart-modal-backdrop {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.2);
      z-index: 40;
    }
    #cart-modal-backdrop.active { display: block; }
  </style>
</head>
<body>
  <!-- Modal backdrop -->
  <div id="cart-modal-backdrop" onclick="closeCartModal()"></div>
  <!-- Modal -->
  <div id="cart-modal">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <h2 class="text-xl font-semibold">Your Cart</h2>
      <button onclick="closeCartModal()" class="text-gray-500 hover:text-gray-900" aria-label="Close cart">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="p-4" id="cart-items-modal">
      <?php
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
    </div>
    <div class="p-4 border-t border-gray-200">
      <button class="w-full bg-pink-600 text-white px-4 py-2 rounded mb-2">Lanjutkan ke pembayaran</button>
      <button class="w-full bg-gray-600 text-white px-4 py-2 rounded">Lihat keranjang</button>
    </div>
  </div>
  <!-- Navigation bar -->
  <header class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
    <div class="flex items-center space-x-4">
      <!-- Hamburger menu icon -->
      <button aria-label="Open menu" class="text-black focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
      </button>

      <!-- Logo text -->
      <div class="font-serif font-semibold text-2xl text-black select-none">
        Nails Studio
      </div>
    </div>

    <!-- Right section (search, love, cart) -->
    <div class="flex items-center space-x-6 ml-4">
      <!-- Search button -->
      <form class="flex items-center bg-gray-100 rounded-full px-4 py-2 w-72 focus-within:ring-2 focus-within:ring-pink-300">
        <i class="fas fa-search text-gray-400 mr-2"></i>
        <input
          type="search"
          placeholder="Search products, brands ..."
          class="bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400 w-full"
          aria-label="Search products and brands"
        />
      </form>

      <!-- Love button -->
      <button aria-label="Favorites" class="text-gray-700 hover:text-black text-lg">
        <i class="far fa-heart"></i>
      </button>

      <!-- Cart button -->
      <button aria-label="Cart" class="relative text-gray-700 hover:text-black text-lg" onclick="openCartModal()">
        <i class="fas fa-shopping-bag"></i>
        <span
          id="cart-count-badge"
          class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center select-none"
          ><?= $totalItems ?></span
        >
      </button>
    </div>
  </header>
  <script>
    function openCartModal() {
      document.getElementById('cart-modal').classList.add('open');
      document.getElementById('cart-modal-backdrop').classList.add('active');
      fetch('../cart/get_cart.php')
        .then(r => r.text())
        .then(html => document.getElementById('cart-items-modal').innerHTML = html);
    }
    function closeCartModal() {
      document.getElementById('cart-modal').classList.remove('open');
      document.getElementById('cart-modal-backdrop').classList.remove('active');
    }
    function updateCartBadge(count) {
      document.getElementById('cart-count-badge').innerText = count;
    }

    // CRUD Cart
    function cartPlus(id) {
        cartAction('plus', id);
    }
    function cartMinus(id) {
        cartAction('minus', id);
    }
    function cartDelete(id) {
        cartAction('delete', id);
    }
    function cartQty(id, qty) {
        cartAction('update', id, qty);
    }
    function cartAction(action, id, qty=1) {
        let fd = new FormData();
        fd.append('action', action);
        fd.append('product_id', id);
        if(action === 'update') fd.append('qty', qty);
        fetch('../cart/cart_api.php', { method: 'POST', body: fd })
        .then(r=>r.json())
        .then(data=>{
            if(data.success) {
                updateCartBadge(data.cart_count);
                openCartModal();
            }
        });
    }
  </script>
</body>
</html>