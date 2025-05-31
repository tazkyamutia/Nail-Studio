<?php
require_once '../configdb.php';  // pastikan path sesuai struktur folder kamu

$sql = "SELECT 
          p.id_product, 
          p.namaproduct, 
          p.category, 
          p.price, 
          p.image, 
          SUM(s.quantity) AS total_sold
        FROM 
          product p
        JOIN 
          sales s ON p.id_product = s.id_product
        WHERE 
          s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        GROUP BY 
          p.id_product, p.namaproduct, p.category, p.price, p.image
        ORDER BY 
          total_sold DESC
        LIMIT 10";

try {
    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Query error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Top Sellers - Nail Art</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-[#eaf4fc]">

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-[#eaf4fc] relative">
  <h2 class="text-center text-black text-2xl font-normal mb-6">Top Sellers</h2>
  <div class="relative">
    <!-- Left button -->
    <button id="scrollLeft" aria-label="Scroll Left" class="absolute top-1/2 -left-6 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition z-10">
      <i class="fas fa-chevron-left text-black text-lg"></i>
    </button>

    <!-- Right button -->
    <button id="scrollRight" aria-label="Scroll Right" class="absolute top-1/2 -right-6 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition z-10">
      <i class="fas fa-chevron-right text-black text-lg"></i>
    </button>

    <div id="scrollContainer" class="flex space-x-4 overflow-x-auto scrollbar-hide pb-4 scroll-smooth">
      <?php foreach ($results as $row) { ?>
        <div class="min-w-[200px] bg-white rounded-2xl shadow-md p-4">
          <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['namaproduct']); ?>" class="w-full h-40 object-cover rounded-lg mb-2">
          <h3 class="text-lg font-semibold text-black"><?php echo htmlspecialchars($row['namaproduct']); ?></h3>
          <p class="text-sm text-gray-600 mb-1"><?php echo htmlspecialchars($row['category']); ?></p>
          <p class="text-sm text-gray-800 font-bold mb-1">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
          <p class="text-xs text-gray-500">Terjual: <?php echo $row['total_sold']; ?>x</p>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- Chat icon -->
<button aria-label="Chat" class="fixed bottom-4 right-4 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition">
  <i class="fas fa-comment-alt text-black text-lg"></i>
</button>

<script>
  const scrollContainer = document.getElementById('scrollContainer');
  const scrollLeftBtn = document.getElementById('scrollLeft');
  const scrollRightBtn = document.getElementById('scrollRight');

  scrollLeftBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({ left: -280, behavior: 'smooth' });
  });

  scrollRightBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({ left: 280, behavior: 'smooth' });
  });
</script>

</body>
</html>
