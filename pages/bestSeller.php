<?php
$products = [
    [
        "label" => "On sale",
        "image" => "https://storage.googleapis.com/a1aa/image/8cf7c61c-28ec-4bfe-a54d-fa8db7d4b03d.jpg",
        "brand" => "Nail Art Co.",
        "name" => "Premium Nail Polish Set - 5 Colors",
        "price" => 12.95,
        "old_price" => 25.95,
        "reviews" => 210,
        "rating" => 4
    ],
    [
        "label" => "On sale",
        "image" => "https://storage.googleapis.com/a1aa/image/9f4b2651-d65a-424c-7773-f8fd9ba66778.jpg",
        "brand" => "Nail Art Co.",
        "name" => "Complete Nail Art Decoration Kit",
        "price" => 29.99,
        "old_price" => 49.99,
        "reviews" => 340,
        "rating" => 5
    ],
    [
        "label" => "On sale",
        "image" => "https://storage.googleapis.com/a1aa/image/7f7cd34d-e0fc-4306-47ef-ca5a7775e135.jpg",
        "brand" => "Nail Art Co.",
        "name" => "Nail Art Brush Set - 10 Pieces",
        "price" => 15.00,
        "old_price" => 30.00,
        "reviews" => 180,
        "rating" => 4
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Product Nail Art</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<body class="bg-pink-200">
<div class="max-w-7xl mx-auto px-4 py-10 bg-pink-200">
  <div class="flex flex-col md:flex-row md:space-x-8">
    <div class="flex flex-col justify-center mb-8 md:mb-0 md:w-1/4">
      <h2 class="text-black text-2xl font-normal leading-snug mb-6 max-w-xs">
      Shop<br/> Our Best Sellers
      </h2>
      <button class="bg-black text-white text-sm font-semibold rounded px-5 py-3 w-max" type="button">
        Shop sale now
      </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:w-3/4">
      <?php foreach ($products as $product): ?>
        <div class="bg-white rounded-lg p-5 flex flex-col justify-between">
          <div>
            <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-4">
              <?= $product["label"] ?>
            </span>
            <div class="mb-6 flex justify-center">
              <img src="<?= $product["image"] ?>" alt="<?= $product["name"] ?>" class="object-contain" width="300" height="180"/>
            </div>
            <p class="text-gray-700 text-xs font-semibold mb-1"><?= $product["brand"] ?></p>
            <h3 class="text-gray-900 font-semibold text-base mb-2 leading-snug"><?= $product["name"] ?></h3>
            <div class="flex items-center space-x-2 mb-2">
              <span class="text-black font-semibold text-base">$<?= number_format($product["price"], 2) ?></span>
              <span class="line-through text-gray-400 text-sm">$<?= number_format($product["old_price"], 2) ?></span>
              <span class="text-pink-600 text-xs font-semibold">
                Save $<?= number_format($product["old_price"] - $product["price"], 2) ?>
              </span>
            </div>
            <div class="flex items-center space-x-1 text-pink-600 text-sm mb-3">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <i class="<?= $i < $product["rating"] ? 'fas' : 'far' ?> fa-star"></i>
              <?php endfor; ?>
              <a href="#" class="text-gray-700 text-xs underline ml-2">(<?= $product["reviews"] ?> Reviews)</a>
            </div>
          </div>
          <button class="bg-pink-600 text-white text-sm font-semibold rounded-md py-2 w-full" type="button">
            Add to cart
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</body>
</html>
