<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nail Art</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Slab&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: "Poppins", sans-serif;
    }
  </style>
</head>
<body class="bg-[#d7e6fb] min-h-screen p-2 sm:p-10">

  <!-- Breadcrumb -->
  <nav class="flex items-center space-x-2 text-gray-700 text-sm mb-6">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
      viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
      aria-hidden="true" focusable="false">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
    </svg>
    <span class="text-xs">&gt;</span>
    <span class="bg-white text-gray-700 text-xs rounded-full px-3 py-1 select-none">
      Nail Art
    </span>
  </nav>

  <!-- Title & Paragraph -->
  <h1 class="text-3xl font-semibold text-gray-900 mb-3">Nail Art</h1>

  <p class="text-gray-900 mb-3 text-base max-w-xl">
    Nail polish. A splash of color and creativity at your fingertips!
  </p>

  <p class="text-gray-900 mb-3 text-base max-w-xl">
    If you’re searching for the perfect nail polish to express your style,
    you’ve come to the right place! At
    <a href="#" class="underline decoration-gray-900 decoration-1 underline-offset-2">
      Nail Art Studio
    </a>, we’re all about offering you the
  </p>

  <!-- Read More Content -->
  <div id="moreContent" class="text-gray-900 text-base max-w-xl hidden mb-4">
    perfect color, texture, and formula for every occasion. Whether you prefer a subtle nude, a bold red, or something sparkly and festive, nail art polish helps you show your personality in a fun and fashionable way.
    <br><br>
    With hundreds of styles to explore — from minimalist lines to extravagant rhinestones — nail art is more than just a trend; it's a form of self-expression. It boosts confidence, sparks creativity, and completes your overall look. Discover vibrant collections that last long, dry fast, and shine bright, making every manicure a masterpiece.
  </div>

  <!-- Read More / Show Less Buttons -->
  <div id="buttons" class="flex gap-4">
    <button onclick="showMore()" class="flex items-center space-x-1 text-pink-600 text-sm font-medium"
      aria-label="Read more" id="readMoreBtn">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
      <span>Read more</span>
    </button>

    <button onclick="showLess()" class="hidden items-center space-x-1 text-blue-600 text-sm font-medium"
      aria-label="Show less" id="showLessBtn">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
      </svg>
      <span>Show less</span>
    </button>
  </div>

  <!-- Mid Year Sale Banner (tidak full background) -->
  <div class="mt-10">
    <div class="max-w-5xl mx-auto rounded-lg overflow-hidden flex items-center justify-between bg-[#c4c3d6] p-6">
      <div class="max-w-xs">
        <p class="text-gray-800 text-lg font-normal mb-1">Warm up with our</p>
        <p class="text-pink-600 font-semibold italic text-2xl leading-tight" style="font-family: 'Roboto Slab', serif">
          mid-year sales
        </p>
        <button class="mt-3 px-4 py-1.5 border border-pink-600 text-pink-600 rounded-full text-sm font-semibold hover:bg-pink-600 hover:text-white transition">
          SHOP NOW
        </button>
      </div>
      <div class="flex space-x-4">
        <img class="h-20 object-contain" src="https://storage.googleapis.com/a1aa/image/a04aa3a5-7e60-4583-a113-02bdb7a3b806.jpg" alt="Eyeshadow palette"/>
        <img class="h-10 object-contain" src="https://storage.googleapis.com/a1aa/image/06c87de2-dcfb-451e-efdd-74c66e131d72.jpg" alt="Nail polish"/>
        <img class="h-10 object-contain" src="https://storage.googleapis.com/a1aa/image/b29b75ae-4799-449b-58ce-5b9384624353.jpg" alt="Perfume"/>
        <img class="h-10 object-contain" src="https://storage.googleapis.com/a1aa/image/5ee46eb7-4d26-446d-60f6-9e53d723e0af.jpg" alt="Lipstick"/>
        <img class="h-10 object-contain" src="https://storage.googleapis.com/a1aa/image/a5a898da-eea3-435a-30bb-d25efd228f21.jpg" alt="Makeup sponges"/>
        <img class="h-10 object-contain" src="https://storage.googleapis.com/a1aa/image/421ef5ed-7a43-4e49-a3dc-0678301bc0c2.jpg" alt="Lipstick case"/>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script>
    function showMore() {
      document.getElementById('moreContent').classList.remove('hidden');
      document.getElementById('readMoreBtn').classList.add('hidden');
      document.getElementById('showLessBtn').classList.remove('hidden');
    }

    function showLess() {
      document.getElementById('moreContent').classList.add('hidden');
      document.getElementById('readMoreBtn').classList.remove('hidden');
      document.getElementById('showLessBtn').classList.add('hidden');
    }
  </script>

</body>
</html>
