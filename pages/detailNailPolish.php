<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Nail Art Polish</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="flex min-h-screen">
    
    <!-- Konten di sebelah kiri -->
    <div class="w-full md:w-1/3 bg-white rounded-md shadow-md m-4">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
        <button aria-label="Back" class="flex items-center text-gray-800 text-base font-normal">
          <i class="fas fa-chevron-left mr-2"></i>
          Back
        </button>
        <button aria-label="Close" class="text-gray-800 text-xl font-normal">×</button>
      </div>
      <h1 class="text-2xl font-semibold text-gray-900 px-4 py-4">
        <?php echo "Nail Art Polish Products"; ?>
      </h1>
      <nav class="divide-y divide-gray-200">
        <?php
          $items = [
            "Nail Polish Colors",
            "Nail Polish Removers",
            "Nail Polish Base Coats",
            "Nail Polish Top Coats",
            "Nail Care Treatments"
          ];
          foreach ($items as $item) {
            echo '
              <a href="#" class="flex justify-between items-center px-4 py-4 text-gray-700 text-base font-normal hover:bg-gray-50 cursor-pointer">
                ' . $item . '
                <i class="fas fa-chevron-right text-gray-400"></i>
              </a>';
          }
        ?>
      </nav>
      <div class="p-4">
        <img 
          src="https://storage.googleapis.com/a1aa/image/d3b8ddba-745a-467f-257c-ea7d086cef42.jpg"
          alt="Various nail polish bottles and nail art products arranged on a light surface"
          class="w-full rounded-md object-cover"
          width="600" height="300"
        />
      </div>
    </div>

    <!-- Bagian kanan kosong -->
    <div class="w-2/3 hidden md:block"></div>
    
  </div>
</body>
</html>
