<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../configdb.php';

// Ambil jumlah item cart dari database jika sudah login
$totalItems = 0;
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT c.id FROM cart c WHERE c.user_id = ? AND c.status = 'active' LIMIT 1");
    $stmt->execute([$user_id]);
    $cart_id = $stmt->fetchColumn();
    if ($cart_id) {
        $stmt = $conn->prepare("SELECT SUM(qty) FROM cart_item WHERE cart_id = ?");
        $stmt->execute([$cart_id]);
        $totalItems = (int)$stmt->fetchColumn();
    }
} else {
    $totalItems = 0;
}

// Ambil jumlah favorite user jika sudah login
$favCount = 0;
if (isset($_SESSION['id'])) {  // sesuai dengan session login kamu
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM favorite WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $favCount = $stmt->fetchColumn();
}

$profile_img = (isset($_SESSION['user_photo']) && $_SESSION['user_photo'])
    ? '../uploads/' . htmlspecialchars($_SESSION['user_photo'])
    : 'https://upload.wikimedia.org/wikipedia/commons/2/2c/Default_pfp.svg';

$categories = [
    [
        "name" => "Nail Polish",
        "img" => "https://storage.googleapis.com/a1aa/image/3454039a-1ec0-4d5b-d768-5eb40bc6fdf3.jpg",
        "alt" => "Classic manicure nails with red polish on a light pink background",
        "url" => "../pages/nailPolish.php"
    ],
    [
        "name" => "Nail Tools",
        "img" => "https://storage.googleapis.com/a1aa/image/19a38516-0222-4fe4-e0e8-8b6788672e73.jpg",
        "alt" => "Gel nails with shiny finish on a light pink background",
        "url" => "../pages/nailTools.php"
    ],
    [
        "name" => "Nail Care",
        "img" => "https://storage.googleapis.com/a1aa/image/795a778f-295f-402e-a035-64817b5dd80d.jpg",
        "alt" => "Nail art with floral and geometric designs on a light pink background",
        "url" => "../pages/nailCare.php"
    ],
    [
        "name" => "Nail Art Kit",
        "img" => "https://storage.googleapis.com/a1aa/image/20882152-849e-49b3-885b-fbfe303673a2.jpg",
        "alt" => "Acrylic nails with glitter and rhinestones on a light pink background",
        "url" => "../pages/nailKit.php"
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <style>
    #universal-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.3);
      z-index: 40;
    }
    #universal-modal-backdrop.active { display: block; }
    #category-modal {
      position: fixed; top: 0; left: -100%; width: 70%; max-width: 360px; height: 100vh;
      background: white; box-shadow: 2px 0 10px rgba(0,0,0,0.15); padding: 0; z-index: 50;
      box-sizing: border-box; overflow-y: auto;
      transition: left 0.3s cubic-bezier(0.4,0,0.2,1);
    }
    #category-modal.open { left: 0; }
    #category-modal .modal-header {
      display: flex; justify-content: space-between; align-items: center;
      padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;
    }
    #category-modal .modal-header h2 { font-size: 1.25rem; font-weight: 600; }
    #category-modal .modal-body { padding: 1.5rem; }
    #category-modal::-webkit-scrollbar { display: none; }
    #category-modal { -ms-overflow-style: none; scrollbar-width: none; }
    #cart-modal {
      position: fixed; top: 0; right: -100%; width: 30%; max-width: 400px; height: 100%;
      background-color: white; box-shadow: -2px 0 5px rgba(0,0,0,0.1); overflow-y: auto;
      transition: right 0.3s cubic-bezier(0.4,0,0.2,1); z-index: 50;
    }
    #cart-modal.open { right: 0; }
    #cart-modal::-webkit-scrollbar { display: none; }
    #cart-modal { -ms-overflow-style: none; scrollbar-width: none; }
    .top-info-bar {
      background-color: #fbcfe8; color: #1f2937; text-align: center;
      font-size: 0.75rem; padding-top: 0.5rem; padding-bottom: 0.5rem; user-select: none;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
  <script>
    var BASE_CART = "<?= (strpos($_SERVER['SCRIPT_NAME'], '/pages/')!==false ? '../cart/' : 'cart/') ?>";
    var BASE_FAVORITE_API = "<?= (strpos($_SERVER['SCRIPT_NAME'], '/pages/')!==false ? '../favorite_api.php' : 'favorite_api.php') ?>";
  </script>
</head>
<body class="overflow-x-hidden">
  <div class="top-info-bar no-scrollbar">
    FREE shipping and FREE gift when you spend over $75*
  </div>

  <div id="universal-modal-backdrop" onclick="closeAllModals()"></div>
  
  <div id="category-modal" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
    <div class="modal-header">
      <h2 id="category-modal-title">Shop Categories & Menu</h2>
      <button onclick="closeCategoryModal()" class="text-gray-500 hover:text-gray-900" aria-label="Close category menu">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <div class="modal-body">
     <ul class="space-y-2 max-w-md mb-6">
      <?php foreach ($categories as $cat): ?>
        <a href="<?= htmlspecialchars($cat['url']) ?>" class="block">
          <li class="flex justify-between items-center bg-[#fefcfb] border border-[#f0e9e8] rounded-md px-4 py-3 overflow-hidden hover:shadow-md transition-shadow duration-200 cursor-pointer">
            <span class="truncate max-w-[70%] block font-medium text-gray-700"><?= htmlspecialchars($cat['name']) ?></span>
            <div class="flex-shrink-0 ml-3 w-10 h-10">
              <img
                src="<?= htmlspecialchars($cat['img']) ?>"
                alt="<?= htmlspecialchars($cat['alt']) ?>"
                class="w-full h-full rounded-md object-cover block"
              />
            </div>
          </li>
        </a>
      <?php endforeach; ?>
     </ul>

      <nav class="divide-y divide-gray-200 border-t border-b border-gray-200 mt-3">
        <a href="register.php" class="flex items-center gap-3 py-3 text-gray-700 hover:text-pink-600 text-base font-normal transition-colors">
          <i class="far fa-user text-lg w-5 text-center"></i> Sign in
        </a>
        <a href="../pages/favorite.php" class="flex items-center gap-3 py-3 text-gray-700 hover:text-pink-600 text-base font-normal transition-colors">
          <i class="far fa-heart text-lg w-5 text-center"></i> Wishlist
        </a>
        <a href="payment.php" class="flex items-center justify-between py-3 text-gray-700 hover:text-pink-600 text-base font-normal transition-colors">
          <div class="flex items-center gap-3">
            <i class="far fa-credit-card text-lg w-5 text-center"></i> Payment Options
          </div>
          <i class="fas fa-plus text-sm text-gray-400"></i>
        </a>
        <a href="service.php" class="flex items-center justify-between py-3 text-gray-700 hover:text-pink-600 text-base font-normal transition-colors">
          <div class="flex items-center gap-3">
            <i class="far fa-question-circle text-lg w-5 text-center"></i> Customer Service
          </div>
          <i class="fas fa-plus text-sm text-gray-400"></i>
        </a>
        <a href="abouteUss.php" class="flex items-center justify-between py-3 text-gray-700 hover:text-pink-600 text-base font-normal transition-colors">
          <div class="flex items-center gap-3">
            <i class="far fa-smile text-lg w-5 text-center"></i> About Us
          </div>
          <i class="fas fa-plus text-sm text-gray-400"></i>
        </a>
      </nav>
    </div>
  </div>
  
  <div id="cart-modal">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <h2 class="text-xl font-semibold">Your Cart</h2>
      <button onclick="closeCartModal()" class="text-gray-500 hover:text-gray-900" aria-label="Close cart">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="p-4" id="cart-items-modal">
      <!-- Cart items will be loaded here via AJAX -->
    </div>
    <div class="p-4 border-t border-gray-200">
      <button onclick="window.location.href='../pages/checkout.php'" class="w-full bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded mb-2 transition-colors">Proceed to Checkout</button>
      <button onclick="window.location.href='../pages/cart_page.php'" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition-colors">View Cart Page</button>
    </div>
  </div>

  <!-- HEADER DENGAN FORM SEARCH SUDAH FIX -->
  <header class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-white sticky top-0 z-30">
    <div class="flex items-center space-x-4">
      <button aria-label="Open menu" class="text-black focus:outline-none" onclick="openCategoryModal()">
        <i class="fas fa-bars text-2xl"></i>
      </button>
      <a href="../pages/index.php" class="font-serif font-semibold text-2xl text-black select-none hover:text-pink-600 transition">
        Nails Studio
      </a>
    </div>
    <div class="flex items-center space-x-6 ml-4">
      <!-- FORM SEARCH YANG BERFUNGSI -->
      <form id="searchForm"
        class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-2 w-72 focus-within:ring-2 focus-within:ring-pink-300"
        autocomplete="off" style="margin-bottom:0;">
        <button type="submit" class="focus:outline-none">
          <i class="fas fa-search text-gray-400 mr-2"></i>
        </button>
        <input
          type="search"
          id="searchInput"
          name="q"
          placeholder="Search products ..."
          class="bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400 w-full"
          aria-label="Search products and brands"
          required minlength="2"
        />
      </form>
      <script>
      const searchForm = document.getElementById("searchForm");
      const searchInput = document.getElementById("searchInput");
      searchForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query.length < 2) {
          alert("Please type at least 2 characters.");
          return;
        }
        window.location.href = 'search.php?q=' + encodeURIComponent(query);
      });
      </script>
      <!-- END FORM SEARCH -->

      <!-- FAVORITE HEART BADGE DENGAN DATABASE -->
      <button aria-label="Favorites" class="relative text-gray-700 hover:text-black text-lg" onclick="location.href='../pages/favorite.php'">
        <i class="far fa-heart"></i>
        <span
          id="favorite-badge"
          class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center select-none"
        ><?= $favCount ?></span>
      </button>
      <!-- END FAVORITE HEART BADGE -->

      <!-- Cart button -->
      <button aria-label="Cart" id="cart-btn" class="relative text-gray-700 hover:text-black text-lg">
        <i class="fas fa-shopping-bag"></i>
        <span
          id="cart-count-badge"
          class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center select-none"
        ><?= $totalItems ?></span>
      </button>

      <!-- PROFILE ICON START -->
      <?php if (isset($_SESSION['id']) || isset($_COOKIE['user_id'])): ?>
        <a href="../pages/profile.php">
          <img
            src="<?= $profile_img ?>"
            alt="Profile"
            class="w-8 h-8 rounded-full object-cover"
            onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/2/2c/Default_pfp.svg';"
          >
        </a>
      <?php else: ?>
        <a href="../pages/profile.php">
          <img
            src="https://upload.wikimedia.org/wikipedia/commons/2/2c/Default_pfp.svg"
            alt="Profile"
            class="w-8 h-8 rounded-full object-cover"
          >
        </a>
      <?php endif; ?>
      <!-- PROFILE ICON END -->
    </div>
  </header>

  <script>
    // SISA SCRIPT ASLI MU (JANGAN DIUBAH)
    const categoryModal = document.getElementById('category-modal');
    const cartModal = document.getElementById('cart-modal');
    const universalBackdrop = document.getElementById('universal-modal-backdrop');
    function openCategoryModal() {
      closeCartModal(false);
      categoryModal.classList.add('open');
      universalBackdrop.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    function closeCategoryModal(closeBackdrop = true) {
      categoryModal.classList.remove('open');
      if (closeBackdrop && !cartModal.classList.contains('open')) {
        universalBackdrop.classList.remove('active');
        document.body.style.overflow = '';
      }
    }
    function openCartModal() {
      closeCategoryModal(false);
      cartModal.classList.add('open');
      universalBackdrop.classList.add('active');
      document.body.style.overflow = 'hidden';
      fetch(BASE_CART+'get_cart.php')
        .then(r => r.text())
        .then(html => document.getElementById('cart-items-modal').innerHTML = html)
        .catch(error => console.error('Error fetching cart items:', error));
    }
    function closeCartModal(closeBackdrop = true) {
      cartModal.classList.remove('open');
      if (closeBackdrop && !categoryModal.classList.contains('open')) {
        universalBackdrop.classList.remove('active');
        document.body.style.overflow = '';
      }
    }
    function closeAllModals() {
      categoryModal.classList.remove('open');
      cartModal.classList.remove('open');
      universalBackdrop.classList.remove('active');
      document.body.style.overflow = '';
    }
    function updateCartBadge(count) {
      var badge = document.getElementById('cart-count-badge');
      if (badge) {
        badge.textContent = count;
      }
    }
    function cartPlus(id) {
      var fd = new FormData();
      fd.append('action', 'plus');
      fd.append('cart_item_id', id);
      fetch(BASE_CART+'cart_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Hanya update badge, tidak reload modal
            if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
              document.getElementById('cart-count-badge').textContent = data.cart_count;
            }
            // Update qty input langsung jika ingin (opsional)
            // Cari input qty di modal dan update nilainya
            const row = document.querySelector('.modal-item-checkbox[value="'+id+'"]')?.closest('.flex');
            if (row) {
              let qtyInput = row.querySelector('input[type="text"]');
              if (qtyInput) qtyInput.value = parseInt(qtyInput.value) + 1;
            }
          } else {
            alert(data.message || 'Gagal menambah qty.');
          }
        });
    }
    function cartMinus(id) {
      var fd = new FormData();
      fd.append('action', 'minus');
      fd.append('cart_item_id', id);
      fetch(BASE_CART+'cart_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
              document.getElementById('cart-count-badge').textContent = data.cart_count;
            }
            // Update qty input langsung jika ingin (opsional)
            const row = document.querySelector('.modal-item-checkbox[value="'+id+'"]')?.closest('.flex');
            if (row) {
              let qtyInput = row.querySelector('input[type="text"]');
              if (qtyInput && parseInt(qtyInput.value) > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
              // Jika qty jadi 0, reload modal
              if (qtyInput && parseInt(qtyInput.value) <= 1 && data.cart_count === 0) {
                loadCartModal();
              }
            }
          } else {
            alert(data.message || 'Gagal mengurangi qty.');
          }
        });
    }
    function cartQty(id, qty) {
      var fd = new FormData();
      fd.append('action', 'update');
      fd.append('cart_item_id', id);
      fd.append('qty', qty);
      fetch(BASE_CART+'cart_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
              document.getElementById('cart-count-badge').textContent = data.cart_count;
            }
          } else {
            alert(data.message || 'Gagal update qty.');
          }
        });
    }
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeAllModals();
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      // Cart button login check
      document.getElementById('cart-btn').addEventListener('click', function(e) {
        var isLoggedIn = <?= isset($_SESSION['id']) ? 'true' : 'false' ?>;
        if (!isLoggedIn) {
          alert('Anda harus login terlebih dahulu!');
          window.location.href = '<?= (strpos($_SERVER['SCRIPT_NAME'], '/pages/')!==false ? '../pages/' : '') ?>login.php';
          return;
        }
        // Jika sudah login, buka modal cart
        openCartModal();
      });
    });

    // Load cart items into modal
function loadCartModal() {
  fetch(BASE_CART+'get_cart.php')
    .then(res => res.text())
    .then(html => {
      document.getElementById('cart-items-modal').innerHTML = html;
    });
}

// Open cart modal and load items
function openCartModal() {
  document.getElementById('cart-modal').classList.add('open');
  loadCartModal();
}

// Close cart modal
function closeCartModal() {
  document.getElementById('cart-modal').classList.remove('open');
}

// Hapus item dari cart
function cartDelete(cart_item_id) {
  var fd = new FormData();
  fd.append('action', 'delete');
  fd.append('cart_item_id', cart_item_id);
  fetch(BASE_CART+'cart_api.php', {
    method: 'POST',
    body: fd
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      loadCartModal();
      if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
        document.getElementById('cart-count-badge').textContent = data.cart_count;
      }
    } else {
      alert(data.message || 'Gagal menghapus item dari cart.');
    }
  });
}

// Tambah/kurang qty juga harus reload modal
function cartPlus(cart_item_id) {
  var fd = new FormData();
  fd.append('action', 'plus');
  fd.append('cart_item_id', cart_item_id);
  fetch(BASE_CART+'cart_api.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        loadCartModal();
        if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
          document.getElementById('cart-count-badge').textContent = data.cart_count;
        }
      } else {
        alert(data.message || 'Gagal menambah qty.');
      }
    });
}
function cartMinus(cart_item_id) {
  var fd = new FormData();
  fd.append('action', 'minus');
  fd.append('cart_item_id', cart_item_id);
  fetch(BASE_CART+'cart_api.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        loadCartModal();
        if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
          document.getElementById('cart-count-badge').textContent = data.cart_count;
        }
      } else {
        alert(data.message || 'Gagal mengurangi qty.');
      }
    });
}
function cartQty(cart_item_id, qty) {
  var fd = new FormData();
  fd.append('action', 'update');
  fd.append('cart_item_id', cart_item_id);
  fd.append('qty', qty);
  fetch(BASE_CART+'cart_api.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        loadCartModal();
        if (data.cart_count !== undefined && document.getElementById('cart-count-badge')) {
          document.getElementById('cart-count-badge').textContent = data.cart_count;
        }
      } else {
        alert(data.message || 'Gagal update qty.');
      }
    });
}

// Pastikan tombol Cart memanggil openCartModal()
document.addEventListener('DOMContentLoaded', function() {
  var cartBtn = document.getElementById('cart-btn');
  if (cartBtn) {
    cartBtn.addEventListener('click', function(e) {
      // ...login check jika perlu...
      openCartModal();
    });
  }
});
  </script>
</body>
</html>
