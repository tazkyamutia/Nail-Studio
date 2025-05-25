<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nail Art Studio</title>
  
  <body class="bg-white">
  <!-- Top pink bar -->
  <div class="bg-pink-300 text-black text-center text-xs py-2 select-none no-scrollbar">
    FREE shipping and FREE gift when you spend over $75*
  </div>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <style>
    /* Custom scrollbar hidden for the top pink bar */
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>
<body class="bg-white">



<?php
if (session_status() == PHP_SESSION_NONE) session_start();

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalItems = array_sum(array_column($cart, 'qty'));

$categories = [
    [
        "name" => "Nail Polish",
        "img" => "https://storage.googleapis.com/a1aa/image/3454039a-1ec0-4d5b-d768-5eb40bc6fdf3.jpg",
        "alt" => "Classic manicure nails with red polish on a light pink background"
    ],
    [
        "name" => "Nail Tools",
        "img" => "https://storage.googleapis.com/a1aa/image/19a38516-0222-4fe4-e0e8-8b6788672e73.jpg",
        "alt" => "Gel nails with shiny finish on a light pink background"
    ],
    [
        "name" => "Nail Care",
        "img" => "https://storage.googleapis.com/a1aa/image/795a778f-295f-402e-a035-64817b5dd80d.jpg",
        "alt" => "Nail art with floral and geometric designs on a light pink background"
    ],
    [
        "name" => "Nail Art Kits",
        "img" => "https://storage.googleapis.com/a1aa/image/20882152-849e-49b3-885b-fbfe303673a2.jpg",
        "alt" => "Acrylic nails with glitter and rhinestones on a light pink background"
    ],
];
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
    /* Backdrop for modals */
    #modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.3);
      z-index: 40;
    }
    #modal-backdrop.active {
      display: block;
    }

    /* Modal kategori (slide dari kiri) */
  
  #custom-modal {
  position: fixed;
  top: 100px;
  left: -100%;
  width: 100%;
  max-width: 360px;
  background: white;
  border-radius: 0;
 
  padding: 1.5rem;
  z-index: 50;
  box-sizing: border-box;
  height: auto;      /* tinggi mengikuti isi */
  max-height: 78vh;  /* maksimal tinggi 70% viewport height */
  overflow-y: auto;  /* scroll kalau isi melebihi max-height */
  transition: left 0.3s cubic-bezier(0.4,0,0.2,1);
  bottom: none;
}

    #custom-modal.active {
      left: 0;
    }

    #custom-modal header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    #custom-modal header h2 {
      font-size: 1.5rem;
      font-weight: 700;
    }
    #custom-modal button.close-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
    }

    /* Modal cart (slide dari kanan) */
    #cart-modal {
  position: fixed;
  top: 0;
  right: -100%;
  width: 30%;
  max-width: 400px;
  height: 100vh; /* ganti dari auto jadi full tinggi layar */
  background-color: white;
  box-shadow: -2px 0 5px rgba(0,0,0,0.1);
  overflow-y: auto; /* tetap bisa scroll isi kalau penuh */
  transition: right 0.3s cubic-bezier(0.4,0,0.2,1);
  z-index: 50;
  bottom: 0; 
}

    #cart-modal.open {
      right: 0;
    }
    #cart-modal header {
      padding: 1rem;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    #cart-modal header h2 {
      margin: 0;
      font-size: 1.25rem;
    }
    #cart-modal header button.close-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
    }
    #cart-modal ul.cart-items {
      list-style: none;
      padding: 1rem;
      margin: 0;
    }
    #cart-modal ul.cart-items li {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }
    #cart-modal p.empty-message {
      padding: 1rem;
      text-align: center;
      color: #777;
    }
   #custom-modal {
  max-height: 80vh;
  overflow-y: scroll; /* tetap bisa scroll */
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE dan Edge */
}

#custom-modal::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Opera */
}


  </style>
</head>
<body class="overflow-x-hidden">

  <!-- Header -->
 <header class="flex items-center justify-between px-10 py-4 border-b border-gray-200">
  <div class="flex items-center space-x-4">
    <!-- Tombol buka modal kategori -->
    <button aria-label="Open menu" id="open-modal-btn" class="text-black focus:outline-none text-2xl">
      <i class="fas fa-bars"></i>
    </button>
    <h2 class="font-serif font-normal text-3xl leading-none">Nail Art Studio</h2>
  </div>

    <div class="flex items-center space-x-6">
      <!-- Search -->
      <form class="flex items-center bg-gray-100 rounded-full px-4 py-2 w-72 focus-within:ring-2 focus-within:ring-pink-300">
        <i class="fas fa-search text-gray-400 mr-2"></i>
        <input
          type="search"
          placeholder="Search products, brands ..."
          class="bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400 w-full"
          aria-label="Search products and brands"
        />
      </form>

      <!-- Favorites -->
      <button aria-label="Favorites" class="text-gray-700 hover:text-black text-lg">
        <i class="far fa-heart"></i>
      </button>

      <!-- Cart button -->
      <button aria-label="Cart" id="open-cart-btn" class="relative text-gray-700 hover:text-black text-lg">
        <i class="fas fa-shopping-bag"></i>
        <span
          id="cart-count-badge"
          class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center select-none"
          ><?= $totalItems ?></span
        >
      </button>
    </div>
  </header>

  <!-- Backdrop -->
  <div id="modal-backdrop"></div>

  <!-- Modal kategori (JANGAN UBAH ISI MODAL INI) -->
  <div id="custom-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <header>
      <h3 id="modal-title">Shop Categories <br> & Menu</h3>
      <button class="close-btn" aria-label="Close modal">&times;</button>
    </header>

    <main>
      <ul class="space-y-2 max-w-md mb-6">
        <?php foreach ($categories as $category): ?>
          <li class="flex justify-between items-center bg-[#fefcfb] border border-[#f0e9e8] rounded-md px-4 py-3 overflow-hidden">
            <span class="truncate max-w-[70%] block"><?= htmlspecialchars($category['name']) ?></span>
            <div class="flex-shrink-0 ml-3 w-10 h-10">
              <img
                src="<?= htmlspecialchars($category['img']) ?>"
                alt="<?= htmlspecialchars($category['alt']) ?>"
                class="w-full h-full rounded-md object-cover block"
              />
            </div>
          </li>
        <?php endforeach; ?>
      </ul>

      <nav class="divide-y divide-gray-200 border-t border-b border-gray-200 mt-3">
        <a href="#" class="flex items-center gap-2 py-3 text-gray-900 text-base font-normal">
          <i class="far fa-user text-lg"></i> Sign in
        </a>
        <a href="#" class="flex items-center gap-2 py-3 text-gray-900 text-base font-normal">
          <i class="far fa-heart text-lg"></i> Wishlist
        </a>
        <a href="#" class="flex items-center justify-between py-3 text-gray-900 text-base font-normal">
          <div class="flex items-center gap-2">
            <i class="far fa-credit-card text-lg"></i> Payment Options
          </div>
          <i class="fas fa-plus text-lg"></i>
        </a>
        <a href="#" class="flex items-center justify-between py-3 text-gray-900 text-base font-normal">
          <div class="flex items-center gap-2">
            <i class="far fa-question-circle text-lg"></i> Customer Service
          </div>
          <i class="fas fa-plus text-lg"></i>
        </a>
        <a href="#" class="flex items-center justify-between py-3 text-gray-900 text-base font-normal">
          <div class="flex items-center gap-2">
            <i class="far fa-smile text-lg"></i> About Us
          </div>
          <i class="fas fa-plus text-lg"></i>
        </a>
      </nav>
    </main>
  </div>

  <!-- Modal cart (JANGAN UBAH ISI MODAL INI) -->
  <div id="cart-modal" aria-modal="true" aria-labelledby="cart-modal-title" role="dialog">
    <header>
      <h2 id="cart-modal-title">My Cart</h2>
      <button class="close-btn" aria-label="Close cart modal">&times;</button>
    </header>

    <main>
      <ul class="cart-items">
        <?php if (empty($cart)): ?>
          <p class="empty-message">Your cart is empty.</p>
        <?php else: ?>
          <?php foreach ($cart as $id => $item): ?>
            <li>
              <span><?= htmlspecialchars($item['name']) ?></span>
              <span>Qty: <?= intval($item['qty']) ?></span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </main>
  </div>

  <script>
    // Elements
    const modal = document.getElementById('custom-modal');
    const cartModal = document.getElementById('cart-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const openModalBtn = document.getElementById('open-modal-btn');
    const openCartBtn = document.getElementById('open-cart-btn');
    const closeModalBtn = modal.querySelector('.close-btn');
    const closeCartBtn = cartModal.querySelector('.close-btn');

    // Open modal kategori
    openModalBtn.addEventListener('click', () => {
      modal.classList.add('active');
      backdrop.classList.add('active');
      document.body.style.overflow = 'hidden';

      // Ensure cart modal closes if open
      cartModal.classList.remove('open');
    });

    // Close modal kategori
    closeModalBtn.addEventListener('click', closeAllModals);
    // Close modal cart
    closeCartBtn.addEventListener('click', closeAllModals);

    // Open cart modal
    openCartBtn.addEventListener('click', () => {
      cartModal.classList.add('open');
      backdrop.classList.add('active');
      document.body.style.overflow = 'hidden';

      // Ensure kategori modal closes if open
      modal.classList.remove('active');
    });

    // Click backdrop closes all modals
    backdrop.addEventListener('click', closeAllModals);

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeAllModals();
      }
    });

    function closeAllModals() {
      modal.classList.remove('active');
      cartModal.classList.remove('open');
      backdrop.classList.remove('active');
      document.body.style.overflow = '';
    }
  </script>
</body>
</html>
