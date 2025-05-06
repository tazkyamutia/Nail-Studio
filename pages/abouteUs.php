
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>About Us</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: "Poppins", sans-serif;
    }
  </style>
</head>
<body class="bg-[#ffe4e6] m-0 p-0">
  <nav class="flex items-center gap-2 px-4 py-2" style="background-color:rgb(246, 164, 205);">
    <i class="fas fa-home text-[#1a1a1a] text-[14px]"></i>
    <span class="text-[14px] text-[#1a1a1a] bg-white rounded-full px-3 py-[2px] font-normal select-none">
      About Us
    </span>
  </nav>

  <header class="text-center py-12 px-4" style="background-color: rgb(246, 164, 205);">
    <h1 class="text-[28px] font-semibold text-[#1a1a1a] mb-3">About Us</h1>
    <p class="text-[15px] text-[#1a1a1a] max-w-xl mx-auto">
      Discover Australia's most reliable destination for all your nail art needs.
    </p>
  </header>

  <main class=" px-6 md:px-12 lg:px-20 py-10 max-w-7xl mx-auto flex flex-col md:flex-row gap-10 md:gap-20">
    <section class="text-[#3a2c2c] text-[14px] leading-relaxed md:w-1/2">
      <p class="mb-6" > 
        Ladies, gather 'round! We all know the drill: Nail art, beauty potions, skin elixirs, and fabulous accessories are the bread and butter of our glam lives. But let's be honest, the price tags can be real party poopers. We work our pretty little tushies off, and we deserve to indulge in these fancy must-haves without draining our bank accounts, right?
      </p>
      <p>
        Well, darlings, say hello to Nail Art Saya - your fairy godmother in the world of beauty! We're here to sprinkle some magic dust on your shopping spree, making sure you get the best deals on all things glam.
      </p>
    </section>

    <section class="md:w-1/2">
      <img src="https://storage.googleapis.com/a1aa/image/c830a093-2874-4971-41a5-6ef3064fc160.jpg"
           alt="Two women smiling with curly and straight hair wearing pink lipstick, close-up portrait"
           class="rounded-xl w-full object-cover"
           width="600" height="400"/>
    </section>
  </main>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Info Cards</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-white p-4">
  <main class="max-w-7xl mx-auto">
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <?php
      $cards = [
        [
          "title" => "Shipping Information",
          "description" => "We offer free shipping throughout Australia for orders over \$75.",
          "image" => "https://storage.googleapis.com/a1aa/image/d6006fb1-1d49-4232-6798-4a03aa5f176d.jpg",
          "alt" => "Smiling woman holding a cardboard box in front of a pink background"
        ],
        [
          "title" => "Refund Information",
          "description" => "We offer a 30 day refund if you are in any way unsatisfied with your purchase.",
          "image" => "https://storage.googleapis.com/a1aa/image/e9daea27-0c14-4640-e2e3-03a631892a23.jpg",
          "alt" => "Model of a white delivery truck with a red arrow pointing to an open cardboard box on a pink background"
        ],
        [
          "title" => "Frequently Asked Questions",
          "description" => "Got a question? This is where you'll find the answer!",
          "image" => "https://storage.googleapis.com/a1aa/image/84ab18ca-b2f1-42a8-6919-bbdda0ff9640.jpg",
          "alt" => "Woman wearing a face mask and bathrobe sitting on a chair with hands offering cosmetic products and a coin on a purple background"
        ]
      ];

      foreach ($cards as $card) {
        echo '
        <article class="bg-[#d3e5fb] rounded-lg overflow-hidden shadow-sm">
          <img src="'.$card["image"].'" alt="'.$card["alt"].'" class="w-full object-cover" width="600" height="300"/>
          <div class="p-6">
            <h2 class="font-semibold text-gray-900 text-lg mb-2">'.$card["title"].'</h2>
            <p class="text-gray-800 text-sm mb-4">'.$card["description"].'</p>
            <button class="bg-black text-white text-sm font-semibold rounded px-4 py-2 hover:bg-gray-900 transition">
              Learn more
            </button>
          </div>
        </article>
        ';
      }
      ?>

    </section>
  </main>
</body>
</html>
<?php include 'footer.php'; ?>

