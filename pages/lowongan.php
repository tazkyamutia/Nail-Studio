<?php include 'hehe.php'; ?>
<?php include 'navbar.php'; ?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lowongan Nailist - Nail Art Studio</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Quicksand&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(135deg, #fdf0f5, #fce4ec);
      color: #5a4b51;
    }

    .main-container {
      max-width: 960px;
      margin: 40px auto;
      background-color: #fff;
      border-radius: 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
      overflow: hidden;
      padding: 40px;
      display: flex;
      gap: 40px;
    }

    .job-left {
      flex: 2;
    }

    .job-right {
      flex: 1;
      text-align: center;
      border-left: 1px solid #eee;
      padding-left: 30px;
    }

    .company-logo {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
    }

    .job-title {
      font-family: 'Playfair Display', serif;
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 10px;
      color: #ce4d86;
    }

    .job-subtitle {
      font-size: 16px;
      color: #777;
      margin-bottom: 20px;
    }

    .job-subtitle i {
      margin-right: 6px;
    }

    .job-info-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    .job-info-item {
      width: 48%;
      background-color: #fce4ec;
      color: #5a4b51;
      padding: 12px 15px;
      border-radius: 10px;
      font-size: 14px;
      display: flex;
      align-items: center;
    }

    .job-info-item i {
      color: #ce4d86;
      margin-right: 10px;
    }

    .apply-button {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      background-color: #ce4d86;
      color: #fff;
      border: none;
      border-radius: 30px;
      font-weight: bold;
      text-decoration: none;
    }

    .job-description {
      margin-top: 30px;
      font-size: 16px;
      line-height: 1.7;
    }

    .job-details-extra h2 {
      font-size: 20px;
      margin-top: 25px;
      color: #ce4d86;
    }

    .job-details-extra ul {
      list-style: disc;
      margin-left: 20px;
      margin-top: 10px;
    }

    .company-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #ce4d86;
      margin-bottom: 10px;
    }

    .company-info {
      font-size: 14px;
      margin-bottom: 8px;
    }

    .company-info i {
      color: #ce4d86;
      margin-right: 6px;
    }

    .footer-report {
      text-align: center;
      margin-top: 30px;
      font-size: 14px;
      color: #777;
    }

    .footer-report a {
      color: #ce4d86;
      text-decoration: none;
    }

    .footer-report a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="main-container">
  <!-- KIRI -->
  <div class="job-left">
    <div class="job-title">Nail Specialist / Nailist</div>
    <div class="job-subtitle">
      <i class="fas fa-building"></i> NAIL ART STUDIO &nbsp;&bull;&nbsp;
      <i class="fas fa-calendar-alt"></i> 19 hari yang lalu
    </div>

    <div class="job-info-grid">
      <div class="job-info-item"><i class="fas fa-location-dot"></i> Jakarta Selatan</div>
      <div class="job-info-item"><i class="fas fa-user-tag"></i> Kontrak</div>
      <div class="job-info-item"><i class="fas fa-layer-group"></i> Junior Level</div>
      <div class="job-info-item"><i class="fas fa-briefcase"></i> Nail Art</div>
      <div class="job-info-item"><i class="fas fa-graduation-cap"></i> SMA / SMK</div>
      <div class="job-info-item"><i class="fas fa-money-bill-wave"></i> Negosiasi</div>
    </div>

    <a href="lamaran.php" class="apply-button">Lamar Pekerjaan</a>


    <div class="job-description">
      <p>Kami sedang membuka kesempatan untuk kamu yang ingin menjadi Nail Artist profesional bersama tim Nail Art Studio. Berikan pelayanan terbaik dan ekspresikan kreativitasmu di dunia kecantikan kuku.</p>
    </div>

    <div class="job-details-extra">
      <h2>Kriteria & Syarat:</h2>
      <ul>
        <li>Wanita, usia 19-30 tahun</li>
        <li>Jujur, mau belajar, dan ramah</li>
        <li>Berpenampilan menarik</li>
        <li>Domisili Jakarta Selatan</li>
        <li>Tidak sedang kuliah</li>
      </ul>

      <h2>Benefit:</h2>
      <ul>
        <li>Uang Makan</li>
        <li>Uang Kerajinan</li>
        <li>Pelatihan ±2 bulan (dapat uang makan)</li>
      </ul>

      <h2>Jam Kerja:</h2>
      <p>Mulai jam 9 pagi, off bergiliran.</p>
    </div>
  </div>

  <!-- KANAN -->
  <div class="job-right">
    <img src="../Tazkya-HTML/images/logonails.png" alt="logo" height="70" width="70">
    <div class="company-name">NAIL ART STUDIO</div>
    <div class="company-info"><i class="fas fa-location-dot"></i> JAKARTA SELATAN</div>
    <div class="company-info"><i class="fas fa-heart"></i> Beauty & Wellness</div>
    <div class="company-info"><i class="fas fa-users"></i> < 5 Karyawan</div>
    <p style="margin-top: 10px;">Kami adalah studio baru dengan semangat tinggi untuk menghadirkan layanan nail art dan lash extension berkualitas dan memanjakan pelanggan.</p>
  </div>
</div>

<div class="footer-report">
  <i class="fas fa-clipboard"></i>
  <a href="kendala.php">Laporkan kendala lowongan/perusahaan</a>
</div>

</body>
</html>
  </div>
  </div>
 
  
