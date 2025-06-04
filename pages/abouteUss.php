<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Nail Studio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      height: 100vh;
      overflow: hidden;
      position: relative;
    }
    .bg-image {
      position: fixed;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover; /* memenuhi layar, bisa potong */
      filter: brightness(0.5) saturate(1.1); /* redup dan warna lebih hidup */
      z-index: -2;
      will-change: transform;
    }
    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(50, 50, 50, 0.3); /* abu-abu transparan */
      z-index: -1;
      pointer-events: none;
    }
  </style>
</head>
<body>

  <!-- Background Image HD full screen -->
  <img 
    src="../uploads/gambar.jpg" 
    alt="Nail salon background" 
    class="bg-image" 
    loading="eager"
  />
  <div class="overlay"></div>

  <!-- Navigation -->
  <nav class="relative z-10 flex items-center justify-between px-4 sm:px-6 md:px-10 lg:px-16 py-4 text-white text-sm font-semibold">
    <div class="flex items-center space-x-8">
      <a class="flex items-center space-x-2" href="#">
       <img alt="Logo" class="w-8 h-8" src="../uploads/logo.jpg" />
        <span class="text-white text-lg font-semibold">Nail Studio</span>

      </a>
   
    </div>
    <div class="hidden md:flex items-center space-x-6 text-xs font-semibold">
      <a class="flex items-center space-x-1 hover:text-gray-300" href="tel:">
        <i class="fas fa-phone-alt text-[10px]"></i>
        <span>CONTACT</span>
      </a>
      <button class="flex items-center space-x-1 hover:text-gray-300">
        <i class="fas fa-globe-americas text-[10px]"></i>
        <span>loc</span>
       
      </button>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="relative z-10 flex flex-col items-center justify-center text-center px-4 sm:px-6 md:px-10 lg:px-16 py-32">
    <h3 class="text-white text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-semibold max-w-4xl leading-tight">
      Empowering the people who power the world.
    </h3>
    <a href="detailAbouteUs.php" class="mt-8 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-6 py-2 rounded inline-block text-center">
    About Us
    </a>

  </main>

</body>
</html>
