  <?php include '../views/navbar.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>About Us - Nail Art Studio</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
        }
        .hero {
            background-color:rgb(250, 203, 237);
            color: white;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }
        .hero h1 {
            font-size: 36px;
            margin-bottom: 10px;
            max-width: 600px;
        }
        .hero p {
            font-size: 16px;
            margin-top: 10px;
            max-width: 600px;
            line-height: 1.5;
        }
        .hero a.button {
            display: inline-block;
            margin-top: 20px;
            background-color: black;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
        }
        .hero img {
            width: 300px;
            position: absolute;
            right: 40px;
            top: 40px;
            border-radius: 8px;
            max-width: 100%;
            height: auto;
        }
        .section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            gap: 40px;
            flex-wrap: wrap;
        }
        .section .text {
            flex: 1 1 400px;
            text-align: left;
        }
        .section .text h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .section .text p {
            font-size: 16px;
            line-height: 1.6;
        }
        .section .image {
            flex: 1 1 300px;
        }
       .section .image img {
         max-width: 50%;
         border-radius: 8px;
         height: auto;
         margin-left: 70px; /* geser ke kanan 20px */
        }       

        .core-values {
            background-color: #f7f7f7;
            padding: 60px 40px;
            text-align: center;
        }
        .core-values h2 {
            font-size: 28px;
            margin-bottom: 40px;
            color: #333;
        }
        .core-boxes {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .core-box {
            background-color:rgb(207, 233, 255);
            padding: 20px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.05);
            text-align: left;
        }
        .core-box h3 {
            color: #000;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .core-box p {
            font-size: 14px;
            color: #444;
        }
.core-img-container {
  display: flex;
  justify-content: center; /* untuk tengah secara horizontal */
  gap: 20px; /* jarak antar gambar */
  flex-wrap: wrap; /* jika layar kecil, gambar bisa turun baris */
  padding: 20px 0; /* opsional, beri jarak atas bawah */
}

.core-img-container img.core-img {
  width: 25%; /* ukuran gambar */
  height: auto;
  border-radius: 10px;
  object-fit: cover;
}




        @media (max-width: 768px) {
            .hero img {
                position: static;
                display: block;
                margin: 20px auto 0;
                width: 100%;
                max-width: 300px;
                height: auto;
            }
            .section {
                flex-direction: column;
                padding: 40px 20px;
            }
            .section .text {
                text-align: center;
            }
            .section .image {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="hero">
        <h1>About Us - Nail Studio</h1>
        <p>Welcome to Nail Studio, your ultimate destination for creative, stylish, and professional nail art services. We believe that beautiful nails are a form of self-expression and confidence. Our mission is to bring out the unique personality of every client through personalized designs, top-quality products, and the latest nail trends.

At Nail Studio, we combine artistry with hygiene and comfort. Our team of passionate nail artists stays updated with the newest techniques and innovations in the industry to deliver flawless results every time. Whether you want a classic manicure, bold nail art, or a trendy gel polish, we’re here to make your vision come true.</p>
       
        <img src="../uploads/gg.jpg" alt="Manfaat About Us" />
    </div>

    <div class="section">
        <div class="image">
            <img src="../uploads/download (23).jpg" alt="Potensi Bisnis" />
        </div>
        <div class="text">
            <h2>Business Potential Nails Studio</h2>
            <p>The nail art studio has promising business opportunities, given the growing number of enthusiasts in beauty trends and self-care, especially among K-pop fans. Since the COVID-19 pandemic, awareness of mental health has increased significantly. Nail art has become a medium for self-expression as well as an enjoyable form of self-care.

By combining fashion trends with touches of Korean culture, the target market for the nail studio continues to expand. Moreover, many young people and adults want to look stylish and confident through the appearance of their nails.

</p>
        </div>
    </div>

    <div class="core-values">
        <h2>Core Value</h2>
        <div class="core-boxes">
            <div class="core-box">
                <h3>💡Limitless Innovation</h3>
                <p>We encourage limitless creativity with the latest nail art techniques, unique colors, and professional tools to deliver results that are always fresh and fashionable.</p>
            </div>
            <div class="core-box">
                <h3>🤝 Meaningful Collaboration</h3>
                <p>We don’t just provide services; we collaborate. We listen to our clients’ desires and tailor designs to match their personalities and special moments.</p>
            </div>
            <div class="core-box">
                <h3>💎 Uncompromising Quality</h3>
                <p>We only use the best, hygienic, and long-lasting products to ensure the comfort and safety of our clients’ nails.</p>
            </div>
        </div>
     <div class="core-img-container">
  <img src="../uploads/download (25).jpg" alt="Core Values" class="core-img" />
  <img src="../uploads/22.jpg" alt="Core Values" class="core-img" />
  <img src="../uploads/11.jpg" alt="Core Values" class="core-img" />
</div>

</div>

    </div>
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
<!--member-->
<?php
$team_members = [
  [
    'name' => 'Raynaldi',
    'role' => 'Product Specialist',
    'image' => '../Tazkya-HTML/images/ray.jpg', // Gambar pria
    'desc' => 'Raynaldi is responsible for managing and developing products to stay outstanding and relevant.',
    'link' => '#raynaldi'
  ],
  [
    'name' => 'Tazkya',
    'role' => 'Administrator',
    'image' => '../Tazkya-HTML/images/tazkya.jpg', // Gambar wanita
    'desc' => 'Tazkya manages all backend systems and ensures smooth daily operations.',
    'link' => '#tazkya'
  ],
  [
    'name' => 'Zahara',
    'role' => 'Landing Page Designer',
    'image' => '../Tazkya-HTML/images/zahara.jpg', // Gambar wanita
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

<?php include '../pages/footer.php'; ?>
</body>
</html>
