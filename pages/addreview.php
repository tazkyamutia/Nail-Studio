<?php include '../views/navbar.php'; ?>
<?php
require_once '../configdb.php';
$conn = $conn ?? null;

// ambil user dan produk
$users = $conn->query("SELECT id, fullname FROM user")->fetchAll();
$products = $conn->query("SELECT id_product, namaproduct FROM product")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Review</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 py-10">
  <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow border">
    <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">Reviews</h1>

    <form action="savereview.php" method="POST" enctype="multipart/form-data" class="space-y-6">

      <!-- User & Produk -->
      <div class="grid grid-cols-2 gap-6">
        <div>
          <label class="block font-semibold text-gray-700 mb-1">User</label>
          <select name="user_id" required class="w-full border px-3 py-2 rounded">
            <option value="">-- Pilih --</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['fullname']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block font-semibold text-gray-700 mb-1">Produk</label>
          <select name="product_id" required class="w-full border px-3 py-2 rounded">
            <option value="">-- Pilih --</option>
            <?php foreach ($products as $p): ?>
              <option value="<?= $p['id_product'] ?>"><?= htmlspecialchars($p['namaproduct']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Rating Keseluruhan -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Rating Utama</label>
        <select name="rating" required class="w-full border px-3 py-2 rounded">
          <option value="">-- Pilih Rating --</option>
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <option value="<?= $i ?>"><?= $i ?>/5</option>
          <?php endfor; ?>
        </select>
      </div>

      <!-- Rating Per Aspek -->
      <div class="grid grid-cols-3 gap-4">
        <?php
        $aspects = ['packaging', 'pigmentation', 'longwear', 'texture', 'value_for_money'];
        foreach ($aspects as $aspect): ?>
          <div>
            <label class="block font-medium text-sm mb-1 text-gray-700"><?= ucfirst(str_replace('_', ' ', $aspect)) ?></label>
            <select name="<?= $aspect ?>" required class="w-full border px-2 py-1 rounded">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>"><?= $i ?>/5</option>
              <?php endfor; ?>
            </select>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Komentar -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Komentar</label>
        <textarea name="comment" rows="4" required class="w-full border rounded px-3 py-2 resize-none"></textarea>
      </div>

      <!-- Tag -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Tag (opsional)</label>
        <input type="text" name="tag" placeholder="Misal: Popping, Everyday" class="w-full border px-3 py-2 rounded">
      </div>

      <!-- Label -->
      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="block font-semibold mb-1 text-gray-700">Recommend?</label>
          <select name="recommend" required class="w-full border px-2 py-1 rounded">
            <option>Yes</option>
            <option>No</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold mb-1 text-gray-700">Repurchase?</label>
          <select name="repurchase" required class="w-full border px-2 py-1 rounded">
            <option>Yes</option>
            <option>Maybe</option>
            <option>No</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold mb-1 text-gray-700">Usage Period</label>
          <input type="text" name="usage_period" required placeholder="Contoh: 1 Minggu"
                 class="w-full border px-2 py-1 rounded">
        </div>
      </div>

      <!-- Upload Foto -->
      <div>
        <label class="block font-semibold mb-1 text-gray-700">Foto Review (opsional)</label>
        <input type="file" name="photo" accept="image/*" class="block">
      </div>

      <!-- Submit -->
      <div class="text-center">
        <button type="submit" 
                class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-6 py-2 rounded-full shadow">
          Kirim Review
        </button>
      </div>
    </form>
  </div>
</body>
</html>
<?php include 'footer.php'; ?>
