<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Nail Art Tools</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-[#f7f0ed] min-h-screen">
  <div class="flex min-h-screen">
    <!-- Sisi kiri: Konten utama -->
    <div class="w-full md:w-1/3 bg-white rounded-md shadow-sm mt-4 ml-0 md:ml-4">
      <header class="flex items-center px-4 py-3 text-[#1a1a1a] text-base font-medium">
        <button aria-label="Back" class="flex items-center space-x-2">
          <i class="fas fa-chevron-left text-base"></i>
          <span>Back</span>
        </button>
        <div class="flex-grow"></div>
        <button aria-label="Close" class="text-[#1a1a1a] text-xl font-light">×</button>
      </header>
      <h1 class="px-4 text-[#1a1a1a] text-xl font-semibold border-b border-[#eaeaea] pb-3">
        <?php echo "Nail Art Tools"; ?>
      </h1>
      <nav class="divide-y divide-[#eaeaea]">
        <?php
          $tools = [
            
            "Basic Nail Art Tools" => "#",
            "Nail Art Concerns" => "#",
            "Active Ingredients" => "#",
            "Cleaning Supplies" => "#",
            "Sun Care" => "#"
          ];
          $last = array_key_last($tools);
          foreach ($tools as $label => $link) {
            $isLast = $label === $last;
            echo '<a href="' . $link . '" class="flex justify-between items-center px-4 py-4 text-[#1a1a1a] text-base font-normal' . ($isLast ? ' rounded-b-md' : '') . '">';
            echo '<span>' . $label . '</span>';
            if ($label !== "All Nail Art Tools") {
              echo '<i class="fas fa-chevron-right text-sm"></i>';
            }
            echo '</a>';
          }
        ?>
      </nav>
      <div class="p-4">
        <img 
          src="https://storage.googleapis.com/a1aa/image/2d03c40e-f170-4c9c-8660-389afffb463a.jpg" 
          alt="Various nail art tools including brushes, dotting tools, and nail polish bottles on a light background"
          class="rounded-md w-full object-cover"
          width="600" 
          height="150"
        />
      </div>
    </div>

    <!-- Sisi kanan: Kosong -->
    <div class="w-2/3 hidden md:block"></div>
  </div>
</body>
</html>
