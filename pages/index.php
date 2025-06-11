<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nail Art Studio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-white">

  <?php include '../views/navbar.php'; ?>
  <?php include 'topSeller.php'; ?>

  <?php include 'boking.php'; ?>
  <?php include 'bestSeller.php'; ?>
  <?php include 'produkKategori.php'; ?>
  <?php include 'ourTeam.php'; ?>
  <?php include 'footer.php'; ?>

</body>
</html>
