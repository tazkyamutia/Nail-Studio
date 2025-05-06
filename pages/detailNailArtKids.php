<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Nail Art Kits</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
        Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
    }
  </style>
</head>
<body class="bg-white min-h-screen">
  <div class="flex min-h-screen">

    <!-- Konten kiri -->
    <div class="w-full md:w-1/3 border border-gray-200 shadow-sm bg-white m-4">
      <div class="flex items-center px-4 py-3 border-b border-gray-200">
        <button aria-label="Back" class="flex items-center text-gray-800 text-sm font-normal">
          <i class="fas fa-arrow-left mr-2"></i>
          Back
        </button>
        <button aria-label="Close" class="ml-auto text-gray-400 hover:text-gray-600 text-xl font-light">
          ×
        </button>
      </div>

      <h1 class="px-4 py-3 text-xl font-semibold text-gray-900 border-b border-gray-200">
        <?php echo "Nail Art Kits"; ?>
      </h1>

      <nav class="divide-y divide-gray-200">
        <?php
        $menuItems = [
          "Starter Kits",
          "Professional Kits",
          "Gel Nail Kits",
          "Nail Art Accessories",
          "Cleaning & Maintenance"
        ];

        foreach ($menuItems as $item) {
          echo '
            <a href="#" class="flex justify-between items-center px-4 py-4 text-gray-800 text-base font-normal hover:bg-gray-50">
              ' . htmlspecialchars($item) . '
              <i class="fas fa-chevron-right text-gray-400"></i>
            </a>';
        }
        ?>
      </nav>

      <img 
        src="https://storage.googleapis.com/a1aa/image/eb4a7170-91ae-46dc-09a3-8aba42a550ef.jpg" 
        alt="Nail art kits including brushes, polishes, and accessories arranged on a white surface" 
        class="w-full object-contain" 
        width="600" 
        height="300" 
        loading="lazy"
      />
    </div>

    <!-- Area kanan kosong -->
    <div class="w-2/3 hidden md:block"></div>

  </div>
</body>
</html>
