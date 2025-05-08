<?php
// nailcare-booking.php
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Beauty Secret</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      margin: 0; /* Menghilangkan margin default di body */
      padding: 0; /* Menghilangkan padding default di body */
    }
    .content-container {
      background-color:rgb(245, 231, 235); /* Background pink hanya untuk kontainer ini */
      width: 100%; /* Memastikan kontainer memenuhi seluruh lebar layar */
      min-width: 100vw; /* Memastikan lebar minimum memenuhi layar */
      padding: 40px 0; /* Menambahkan padding vertikal untuk konten di dalamnya */
      box-sizing: border-box; /* Memastikan padding tidak menambah lebar elemen */
    }
  </style>
 </head>
 <body>
 <div class="content-container flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-12">
  <div class="flex flex-col max-w-xl text-left">
    <div class="mt-20"> <!-- GANTI jadi mt-20 supaya lebih turun -->
      <h1 class="text-black text-2xl md:text-3xl font-semibold leading-snug mb-3">
        join discovery Nail Art Studio

      </h1>  
      <p class="text-[#1A1A1A] text-sm md:text-base font-normal mb-6 leading-relaxed">
        we are looking for a passionate and we're looking for passionate and creative people to join our tempnam
        enjoy a fun, profesional workspace with training, growth opportunities and great pay 
      </p>
      <a href="lowongan.php">
  <button class="bg-black text-white text-xs md:text-sm font-semibold rounded-md px-5 py-2 w-max hover:bg-gray-900 transition" type="button">
    Apply Now
  </button>
</a>
    </div>
  </div>

  <div class="rounded-xl overflow-hidden flex-shrink-0 w-full md:w-[480px]">
    <img src="https://storage.googleapis.com/a1aa/image/13aae325-a01c-47aa-0420-4b0e1de34178.jpg" 
         alt="Close-up of two models with colorful makeup on pink background, one with green eye makeup and pink hair, the other with silver eye makeup" 
         class="w-full h-auto object-cover rounded-xl" 
         width="480" height="320"/>
  </div>
</div>
<!--member-->
<?php
$team_members = [
  [
    'name' => 'Raynaldi',
    'role' => 'Product Specialist',
    'image' => '../Tazkya-HTML/images/cihuy.jpg', // Gambar pria
    'desc' => 'Raynaldi is responsible for managing and developing products to stay outstanding and relevant.',
    'link' => '#raynaldi'
  ],
  [
    'name' => 'Tazkya',
    'role' => 'Administrator',
    'image' => 'https://images.unsplash.com/photo-1517841905240-472988d4b1e0', // Gambar wanita
    'desc' => 'Tazkya manages all backend systems and ensures smooth daily operations.',
    'link' => '#tazkya'
  ],
  [
    'name' => 'Zahara',
    'role' => 'Landing Page Designer',
    'image' => 'https://images.unsplash.com/photo-1524504382020-1c1c1c1c1c1c', // Gambar wanita
    'desc' => 'Zahara designs captivating landing pages to attract customers from the very first glance.',
    'link' => '#zahara'
  ],
];
?>



<div class="bg-blue-50 py-16 px-4 md:px-12">
  <h2 class="text-3xl font-semibold text-center mb-4">Meet Our Team</h2>
  <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12">
    We are the creative minds behind Nail Art Studio. Get to know the people who bring you the best services!
  </p>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($team_members as $member): ?>
      <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col">
        <img src="<?= $member['image'] ?>" alt="<?= $member['name'] ?>" class="w-full h-64 object-cover"/>
        <div class="p-5 flex flex-col flex-grow">
          <h3 class="text-lg font-semibold mb-1"><?= $member['name'] ?></h3>
          <p class="text-sm text-gray-500 mb-3"><?= $member['role'] ?></p>
          <p class="text-sm text-gray-600 flex-grow"><?= $member['desc'] ?></p>
          <a href="<?= $member['link'] ?>" class="mt-4 inline-block bg-black text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 transition self-start">
            View Profile
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
