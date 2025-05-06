<?php
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
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Nail Art Studio &amp; Nail Art Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: "Montserrat", sans-serif;
        }
    </style>
</head>
<body class="bg-[#fefcfb] min-h-screen p-4">
    <div class="flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">
        <!-- Nail Art Studio - left half on large screens -->
        <section class="lg:w-1/2 w-full">
            <header class="flex items-center space-x-4 mb-8">
                <button aria-label="Menu" class="text-black text-3xl leading-none">☰</button>
                <h1 class="font-serif font-bold text-3xl leading-none">Nail Art Studio</h1>
            </header>
            <main>
                <h2 class="text-lg font-semibold mb-4">Shop Categories</h2>
                <ul class="space-y-2 max-w-md">
                    <?php foreach ($categories as $category): ?>
                        <li class="flex justify-between items-center bg-[#fefcfb] border border-[#f0e9e8] rounded-md px-4 py-3">
                            <span><?= htmlspecialchars($category['name']) ?></span>
                            <img
                                src="<?= htmlspecialchars($category['img']) ?>"
                                alt="<?= htmlspecialchars($category['alt']) ?>"
                                class="w-10 h-10 rounded-md"
                                width="40"
                                height="40"
                            />
                        </li>
                    <?php endforeach; ?>
                    <html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Cosmetic Capital
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Georgia&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: 'Georgia', serif;
    }
  </style>
 </head>
 

   <nav class="divide-y divide-gray-200 border-t border-b border-gray-200 mt-3">
    <a class="flex items-center gap-2 py-3 text-gray-900 text-base font-normal" href="#">
     <i class="far fa-user text-lg">
     </i>
     Sign in
    </a>
    <a class="flex items-center gap-2 py-3 text-gray-900 text-base font-normal" href="#">
     <i class="far fa-heart text-lg">
     </i>
     Wishlist
    </a>
    <a class="flex items-center justify-between py-3 text-gray-900 text-base font-normal" href="#">
     <div class="flex items-center gap-2">
      <i class="far fa-credit-card text-lg">
      </i>
      Payment Options
     </div>
     <i class="fas fa-plus text-lg">
     </i>
    </a>
    <a class="flex items-center justify-between py-3 text-gray-900 text-base font-normal" href="#">
     <div class="flex items-center gap-2">
      <i class="far fa-question-circle text-lg">
      </i>
      Customer Service
     </div>
     <i class="fas fa-plus text-lg">
     </i>
    </a>
    <a class="flex items-center justify-between py-3 text-gray-900 text-base font-normal" href="#">
     <div class="flex items-center gap-2">
      <i class="far fa-smile text-lg">
      </i>
      About Us
     </div>
     <i class="fas fa-plus text-lg">
     </i>
    </a>
   </nav>
  </main>
 </body>
</html>
                </ul>
            </main>
        </section>
    </div>
</body>
</html>
