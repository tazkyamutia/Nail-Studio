<?php include '../views/navbar.php'; ?>
<?php
require_once '../configdb.php';
$conn = $conn ?? null;

$reviews = $conn->query("
    SELECT 
        r.*, 
        u.fullname, u.photo AS user_photo,
        p.namaproduct, p.image AS product_image
    FROM reviews r
    JOIN user u ON r.user_id = u.id
    JOIN product p ON r.product_id = p.id_product
    ORDER BY r.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Review Showcase</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<main class="flex-1 py-24">
  <div class="max-w-5xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8 text-center text-pink-600">✨ User Reviews</h1>

    <?php if (isset($_GET['success'])): ?>
      <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
        Review berhasil ditambahkan!
      </div>
    <?php endif; ?>

    <div class="space-y-6">
    <?php foreach ($reviews as $r): ?>
      <?php
        // Path relatif karena file ini ada di /views/
        $avatarPath = '../uploads/' . ($r['user_photo'] ?? '');
        $productImagePath = '../uploads/' . ($r['product_image'] ?? '');
        $reviewPhotoPath = '../uploads/' . ($r['photo'] ?? '');

        // Cek file ada atau tidak
        $avatarURL = (!empty($r['user_photo']) && file_exists($avatarPath))
            ? $avatarPath
            : 'https://via.placeholder.com/48?text=Avatar';

        $productImageURL = (!empty($r['product_image']) && file_exists($productImagePath))
            ? $productImagePath
            : 'https://via.placeholder.com/60?text=Product';

        $reviewPhotoExists = (!empty($r['photo']) && file_exists($reviewPhotoPath));
      ?>

      <div class="bg-white rounded-2xl shadow p-6 border border-pink-100">
        <!-- Header User -->
        <div class="flex items-center mb-4">
          <img src="<?= $avatarURL ?>" class="w-12 h-12 rounded-full border object-cover" alt="Avatar">
          <div class="ml-4">
            <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($r['fullname']) ?></h3>
            <div class="text-sm text-gray-500"><?= date('M d, Y H:i', strtotime($r['created_at'])) ?></div>
          </div>
        </div>

        <!-- Produk -->
        <div class="flex items-center mb-4">
          <img src="<?= $productImageURL ?>" class="w-16 h-16 object-cover rounded border" alt="Product">
          <div class="ml-4 flex-1">
            <p class="text-xs text-gray-500 font-medium uppercase">Product Review</p>
            <h4 class="font-semibold text-pink-700"><?= htmlspecialchars($r['namaproduct']) ?></h4>
            <div class="flex items-center text-yellow-400 text-sm mt-1">
              <?= str_repeat("★", (int)$r['rating']) ?>
              <?= str_repeat("☆", 5 - (int)$r['rating']) ?>
              <span class="text-gray-600 text-xs ml-2"><?= $r['rating'] ?>/5</span>
            </div>
          </div>
        </div>

        <!-- Tag & Komentar -->
        <div class="mb-4">
          <?php if ($r['tag']): ?>
            <span class="inline-block bg-pink-100 text-pink-700 text-xs font-semibold px-2 py-1 rounded-full mb-2">
              <?= htmlspecialchars($r['tag']) ?>
            </span>
          <?php endif; ?>
          <p class="text-sm text-gray-800 leading-relaxed"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
        </div>

        <!-- Rating Detail -->
        <div class="grid grid-cols-3 gap-4 text-sm text-center mb-4">
          <div><span class="font-semibold text-pink-600"><?= $r['packaging'] ?>/5</span><br>Packaging</div>
          <div><span class="font-semibold text-pink-600"><?= $r['pigmentation'] ?>/5</span><br>Pigmentation</div>
          <div><span class="font-semibold text-pink-600"><?= $r['longwear'] ?>/5</span><br>Long Wear</div>
          <div><span class="font-semibold text-pink-600"><?= $r['texture'] ?>/5</span><br>Texture</div>
          <div><span class="font-semibold text-pink-600"><?= $r['value_for_money'] ?>/5</span><br>Value</div>
        </div>

        <!-- Label Ringkasan -->
        <div class="grid grid-cols-3 gap-4 text-sm text-center mb-4">
          <div><span class="text-pink-600 font-semibold"><?= htmlspecialchars($r['recommend']) ?></span><br>Recommend</div>
          <div><span class="text-yellow-600 font-semibold"><?= htmlspecialchars($r['repurchase']) ?></span><br>Repurchase</div>
          <div><span class="text-pink-700 font-semibold"><?= htmlspecialchars($r['usage_period']) ?></span><br>Usage Period</div>
        </div>

        <!-- Foto Review -->
        <?php if ($reviewPhotoExists): ?>
          <div class="text-center">
            <img src="<?= $reviewPhotoPath ?>" alt="Review Photo"
                 class="rounded-lg max-w-xs mx-auto shadow mt-3">
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
