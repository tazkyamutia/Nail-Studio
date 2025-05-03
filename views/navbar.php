<?php
// navbar.php
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
</head>


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
      <button aria-label="Cart" class="relative text-gray-700 hover:text-black text-lg">
        <i class="fas fa-shopping-bag"></i>
        <span
          class="absolute -top-1 -right-2 bg-pink-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center select-none"
          >4</span
        >
      </button>
    </div>
  </header>
</body>
</html>
