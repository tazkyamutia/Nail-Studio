<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Nail Art Polish Product Cards
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
   @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
 </head>
 <body class="bg-white p-4">
  <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
   <!-- Product 1 -->
   <?php
// Data produk
$brand = "Nail Art Co.";
$product = "Bright Red Nail Polish 15ml";
$oldPrice = 9.99;  // Harga lama
$newPrice = 5.00;  // Harga baru
$discountAmount = $oldPrice - $newPrice; // Jumlah diskon
$discountPercent = round(($discountAmount / $oldPrice) * 100, 0); // Persentase diskon
$reviews = 45; // Jumlah ulasan
$imageURL = "https://storage.googleapis.com/a1aa/image/bc05cc17-cba3-4d14-2a38-f7de1155b469.jpg"; // URL gambar produk
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Card</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  <!-- Product Card -->
  <div class="border border-gray-300 rounded-lg p-4 flex flex-col max-w-xs bg-white shadow">
    <div>
      <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
        On sale - <?= $discountPercent ?>% OFF
      </span>
    </div>
    <div class="flex justify-center mb-4">
      <img 
        alt="<?= $product ?>" 
        class="h-24 w-auto object-contain" 
        src="<?= $imageURL ?>" 
      />
    </div>
    <div class="mb-2 text-gray-800 text-sm">
      <?= $brand ?>
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
      <?= $product ?>
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
      <span>$<?= number_format($newPrice, 2) ?></span>
      <span class="line-through text-gray-400 text-sm">$<?= number_format($oldPrice, 2) ?></span>
      <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
        Save $<?= number_format($discountAmount, 2) ?>
      </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="far fa-star"></i>
      <a class="text-gray-700 underline ml-2" href="#">(<?= $reviews ?> Reviews)</a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
      Add to cart
    </button>
  </div>
</body>
</html>

   <!-- Product 2 -->
   <?php
// Data produk
$brand = "Glamour Nails";
$product = "Pink Glitter Nail Polish 12ml";
$oldPrice = 14.99;  // Harga lama
$newPrice = 7.50;  // Harga baru
$discountAmount = $oldPrice - $newPrice; // Jumlah diskon
$discountPercent = round(($discountAmount / $oldPrice) * 100, 0); // Persentase diskon
$reviews = 78; // Jumlah ulasan
$imageURL = "https://storage.googleapis.com/a1aa/image/8f7d248c-5136-42aa-6351-dc87af882f86.jpg"; // URL gambar produk
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Card</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  <!-- Product Card -->
  <div class="border border-gray-300 rounded-lg p-4 flex flex-col max-w-xs bg-white shadow">
    <div>
      <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
        On sale - <?= $discountPercent ?>% OFF
      </span>
    </div>
    <div class="flex justify-center mb-4">
      <img 
        alt="<?= $product ?>" 
        class="h-24 w-auto object-contain" 
        src="<?= $imageURL ?>" 
      />
    </div>
    <div class="mb-2 text-gray-800 text-sm">
      <?= $brand ?>
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
      <?= $product ?>
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
      <span>$<?= number_format($newPrice, 2) ?></span>
      <span class="line-through text-gray-400 text-sm">$<?= number_format($oldPrice, 2) ?></span>
      <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
        Save $<?= number_format($discountAmount, 2) ?>
      </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <a class="text-gray-700 underline ml-2" href="#">(<?= $reviews ?> Reviews)</a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
      Add to cart
    </button>
  </div>
</body>
</html>

   <!-- Product 3 -->
   <?php
// Data produk
$brand = "ColorPop";
$product = "Pastel Blue Nail Polish 14ml";
$oldPrice = 11.99;  // Harga lama
$newPrice = 6.00;  // Harga baru
$discountAmount = $oldPrice - $newPrice; // Jumlah diskon
$discountPercent = round(($discountAmount / $oldPrice) * 100, 0); // Persentase diskon
$reviews = 32; // Jumlah ulasan
$imageURL = "https://storage.googleapis.com/a1aa/image/d6b69bab-dd1e-409f-cdd4-704e7a2c5121.jpg"; // URL gambar produk
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Card</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  <!-- Product Card -->
  <div class="border border-gray-300 rounded-lg p-4 flex flex-col max-w-xs bg-white shadow">
    <div>
      <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
        On sale - <?= $discountPercent ?>% OFF
      </span>
    </div>
    <div class="flex justify-center mb-4">
      <img 
        alt="<?= $product ?>" 
        class="h-24 w-auto object-contain" 
        src="<?= $imageURL ?>" 
      />
    </div>
    <div class="mb-2 text-gray-800 text-sm">
      <?= $brand ?>
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
      <?= $product ?>
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
      <span>$<?= number_format($newPrice, 2) ?></span>
      <span class="line-through text-gray-400 text-sm">$<?= number_format($oldPrice, 2) ?></span>
      <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
        Save $<?= number_format($discountAmount, 2) ?>
      </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="far fa-star"></i>
      <i class="far fa-star"></i>
      <a class="text-gray-700 underline ml-2" href="#">(<?= $reviews ?> Reviews)</a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
      Add to cart
    </button>
  </div>
</body>
</html>

   <!-- Product 4 -->
   <?php
$brand = "Luxe Nails";
$product = "Deep Purple Nail Polish 16ml";
$oldPrice = 15.99;
$newPrice = 8.00;
$discountAmount = $oldPrice - $newPrice;
$discountPercent = round(($discountAmount / $oldPrice) * 100, 0);
$reviews = 60;
$imageURL = "https://via.placeholder.com/100x100.png?text=Nail+Polish";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Card</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  <div class="border border-gray-300 rounded-lg p-4 flex flex-col max-w-xs bg-white shadow">
    <div>
      <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
        On sale - <?= $discountPercent ?>% OFF
      </span>
    </div>
    <div class="flex justify-center mb-4">
      <img 
        alt="<?= $product ?>" 
        class="h-24 w-auto object-contain" 
        src="<?= $imageURL ?>" 
      />
    </div>
    <div class="mb-2 text-gray-800 text-sm">
      <?= $brand ?>
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
      <?= $product ?>
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
      <span>$<?= number_format($newPrice, 2) ?></span>
      <span class="line-through text-gray-400 text-sm">$<?= number_format($oldPrice, 2) ?></span>
      <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
        Save $<?= number_format($discountAmount, 2) ?>
      </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <a class="text-gray-700 underline ml-2" href="#">(<?= $reviews ?> Reviews)</a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
      Add to cart
    </button>
  </div>
</body>
</html>

   <!-- Product 5 -->
   <?php
// Produk 1
$product1_oldPrice = 8.99;
$product1_newPrice = 4.50;
$product1_discountAmount = $product1_oldPrice - $product1_newPrice;
$product1_discountPercent = round(($product1_discountAmount / $product1_oldPrice) * 100, 0);

// Produk 2
$product2_oldPrice = 18.00;
$product2_newPrice = 9.00;
$product2_discountAmount = $product2_oldPrice - $product2_newPrice;
$product2_discountPercent = round(($product2_discountAmount / $product2_oldPrice) * 100, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Cards with Discount</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
<div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of soft pink nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/soft-pink-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Pretty Nails
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Soft Pink Nail Polish 13ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      4.50
     </span>
     <span class="line-through text-gray-400 text-sm">
      8.99
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $4.49
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (25 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 6 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of neon green nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/neon-green-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Neon Nails
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Neon Green Nail Polish 15ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      9.00
     </span>
     <span class="line-through text-gray-400 text-sm">
      18.00
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $9.00
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (50 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 7 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of classic black nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/classic-black-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Black Magic
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Classic Black Nail Polish 14ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      6.50
     </span>
     <span class="line-through text-gray-400 text-sm">
      12.99
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $6.49
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (85 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 8 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of metallic gold nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/metallic-gold-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Gold Glam
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Metallic Gold Nail Polish 13ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      10.00
     </span>
     <span class="line-through text-gray-400 text-sm">
      19.99
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $9.99
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (90 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 9 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of coral orange nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/coral-orange-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Coral Colors
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Coral Orange Nail Polish 15ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      5.50
     </span>
     <span class="line-through text-gray-400 text-sm">
      10.99
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $5.49
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (40 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 10 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of lavender purple nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/lavender-purple-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Lavender Luxe
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Lavender Purple Nail Polish 14ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      6.75
     </span>
     <span class="line-through text-gray-400 text-sm">
      13.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $6.75
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (55 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 11 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of sky blue nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/sky-blue-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Sky Nails
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Sky Blue Nail Polish 15ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      5.25
     </span>
     <span class="line-through text-gray-400 text-sm">
      10.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $5.25
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (38 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 12 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of bright yellow nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/bright-yellow-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Sunny Nails
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Bright Yellow Nail Polish 14ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      6.25
     </span>
     <span class="line-through text-gray-400 text-sm">
      12.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $6.25
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (42 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 13 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of soft peach nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/soft-peach-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Peachy Nails
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Soft Peach Nail Polish 13ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      4.75
     </span>
     <span class="line-through text-gray-400 text-sm">
      9.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $4.75
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (30 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 14 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of bright orange nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/bright-orange-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Orange Crush
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Bright Orange Nail Polish 15ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      5.75
     </span>
     <span class="line-through text-gray-400 text-sm">
      11.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $5.75
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (48 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 15 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of mint green nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/mint-green-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Minty Fresh
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Mint Green Nail Polish 14ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      6.25
     </span>
     <span class="line-through text-gray-400 text-sm">
      12.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $6.25
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (52 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 16 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of rose pink nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/rose-pink-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Rose Beauty
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Rose Pink Nail Polish 13ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      5.00
     </span>
     <span class="line-through text-gray-400 text-sm">
      9.99
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $4.99
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (35 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 17 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of bright turquoise nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/bright-turquoise-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Turquoise Trend
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Bright Turquoise Nail Polish 15ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      7.00
     </span>
     <span class="line-through text-gray-400 text-sm">
      13.99
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $6.99
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (47 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 18 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of soft lilac nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/soft-lilac-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Lilac Love
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Soft Lilac Nail Polish 14ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      5.50
     </span>
     <span class="line-through text-gray-400 text-sm">
      11.00
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $5.50
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (29 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 19 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of classic white nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/classic-white-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     White Chic
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Classic White Nail Polish 15ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      5.75
     </span>
     <span class="line-through text-gray-400 text-sm">
      11.50
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $5.75
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="far fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (44 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
   <!-- Product 20 -->
   <div class="border border-gray-300 rounded-lg p-4 flex flex-col">
    <div>
     <span class="inline-block bg-pink-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-3">
      On sale - NaN% OFF
     </span>
    </div>
    <div class="flex justify-center mb-4">
     <img alt="Bottle of rose gold nail polish with brush cap" class="h-24 w-auto object-contain" height="100" src="https://storage.googleapis.com/a1aa/image/rose-gold-nail-polish.jpg" width="80"/>
    </div>
    <div class="mb-2 text-gray-800 text-sm">
     Rose Gold Glam
    </div>
    <div class="mb-6 font-semibold text-gray-900 text-base leading-snug">
     Rose Gold Nail Polish 14ml
    </div>
    <div class="flex items-center space-x-2 mb-2 text-gray-900 text-base font-normal">
     <span>
      9.50
     </span>
     <span class="line-through text-gray-400 text-sm">
      19.00
     </span>
     <span class="bg-pink-100 text-pink-600 text-xs font-semibold rounded px-2 py-0.5">
      Save $9.50
     </span>
    </div>
    <div class="flex items-center space-x-1 mb-6 text-pink-600 text-sm">
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <i class="fas fa-star"></i>
     <a class="text-gray-700 underline ml-2" href="#">
      (70 Reviews)
     </a>
    </div>
    <button class="mt-auto bg-pink-600 text-white font-semibold rounded-md py-2 w-full hover:bg-pink-700 transition">
     Add to cart
    </button>
   </div>
  </div>
 </body>
</html> 