<?php include '../views/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Wishlist / Produk Favorit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6 text-pink-700">Produk Favorit Saya</h1>
    <div id="wishlist-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"></div>
    <div id="wishlist-empty" class="text-center text-gray-400 mt-8 hidden">Belum ada produk favorit.</div>
  </div>

<script>
// Ambil ID produk favorite dari localStorage
function loadWishlist() {
  try { return JSON.parse(localStorage.getItem('wishlist') || '[]'); } catch { return []; }
}
function removeFromWishlist(pid) {
  let arr = loadWishlist();
  arr = arr.filter(x => x !== pid);
  localStorage.setItem('wishlist', JSON.stringify(arr));
  renderWishlistPage();
  if (window.updateFavoriteBadge) updateFavoriteBadge(arr.length);
}
function renderWishlistPage() {
  const wishlist = loadWishlist();
  const container = document.getElementById('wishlist-container');
  const emptyMsg = document.getElementById('wishlist-empty');
  container.innerHTML = '';
  if (!wishlist.length) {
    emptyMsg.classList.remove('hidden');
    return;
  } else {
    emptyMsg.classList.add('hidden');
  }
  // Ambil data produk dari server berdasarkan id (AJAX sekali saja untuk semua ID)
  fetch('get_products_by_ids.php?ids=' + wishlist.join(','))
    .then(res => res.json())
    .then(data => {
      if (!data.length) {
        container.innerHTML = '<div class="col-span-full text-center text-gray-400">Belum ada produk favorit.</div>'; return;
      }
      data.forEach(product => {
        const html = `
        <div class="bg-white border rounded-lg shadow p-4 flex flex-col">
          <img src="../uploads/${product.image || ''}" alt="${product.namaproduct}" class="h-40 object-contain rounded mb-4" onerror="this.src='https://via.placeholder.com/220x220.png?text=No+Image'">
          <div class="font-semibold text-gray-900 mb-2">${product.namaproduct}</div>
          <div class="font-bold text-pink-600 text-lg mb-2">Rp ${parseInt(product.price).toLocaleString('id-ID')}</div>
          <button class="py-1 px-3 rounded bg-pink-500 hover:bg-pink-600 text-white font-medium mb-2" onclick="removeFromWishlist('${product.id_product}')">Hapus dari Favorit</button>
        </div>`;
        container.innerHTML += html;
      });
    });
}
document.addEventListener('DOMContentLoaded', renderWishlistPage);
</script>
</body>
</html>
