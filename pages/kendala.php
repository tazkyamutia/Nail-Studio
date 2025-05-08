<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "nailstudio_db";

$koneksi = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Proses simpan data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST["nama"]);
    $email = htmlspecialchars($_POST["email"]);
    $telepon = htmlspecialchars($_POST["telepon"]);
    $kategori = htmlspecialchars($_POST["kategori"]);
    $pesan = htmlspecialchars($_POST["pesan"]);

    // Simpan ke database
    $stmt = $koneksi->prepare("INSERT INTO kendalalowongan_db (nama, email, telepon, kategori, pesan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $email, $telepon, $kategori, $pesan);

    $sukses = false;
    if ($stmt->execute()) {
        $sukses = true;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hubungi Kami - Nail Art Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to right, #ffe6f0, #fff0f5);
      color: #333;
      padding: 40px 20px;
    }

    .container {
      max-width: 1100px;
      margin: auto;
      display: flex;
      gap: 40px;
      background: #fff;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      border-radius: 20px;
      overflow: hidden;
    }

    .info {
      flex: 1;
      padding: 40px;
      background: linear-gradient(to bottom right, #fcd6e6, #f8bbd0);
      color: #4a4a4a;
    }

    .info h2 {
      font-size: 28px;
      margin-bottom: 20px;
      color: #7b004c;
    }

    .info p {
      margin-bottom: 30px;
      font-size: 16px;
    }

    table td {
      padding: 5px 0;
      font-size: 16px;
    }

    .form {
      flex: 1;
      padding: 40px;
    }

    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-weight: 600;
    }

    input, select, textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 10px;
      outline: none;
      font-size: 16px;
      transition: 0.3s;
    }

    input:focus, select:focus, textarea:focus {
      border-color: #ff8ac3;
      box-shadow: 0 0 8px #ffd6eb;
    }

    button {
      margin-top: 25px;
      padding: 14px 25px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    button[type="submit"] {
      background: #ff99cc;
      color: #fff;
    }

    button[type="submit"]:hover {
      background: #ff66b2;
    }

    button[type="button"] {
      margin-left: 10px;
      background: #999;
      color: white;
    }

    .result {
      max-width: 800px;
      margin: 20px auto;
      padding: 15px;
      border-radius: 12px;
      font-weight: bold;
      text-align: center;
    }

    .result.success {
      background: #e6ffe6;
      color: #006600;
      border: 1px solid #b3ffb3;
    }

    .result.error {
      background: #ffe6e6;
      color: #cc0000;
      border: 1px solid #ffb3b3;
    }
  </style>
</head>
<body>

<?php if (isset($sukses) && $sukses): ?>
  <div class="result success">✅ Terima kasih! Pesan Anda telah berhasil dikirim.</div>
<?php elseif (isset($sukses) && !$sukses): ?>
  <div class="result error">❌ Gagal mengirim pesan. Silakan coba lagi.</div>
<?php endif; ?>

<div class="container">
  <div class="info">
    <h2>Apakah Anda mengalami kendala?</h2>
    <p>Jika ya, silakan isi form berikut dan tim kami akan membantu Anda secepat mungkin.</p>
    <table>
      <tr><td>Studio</td><td>: Nail Art Studio</td></tr>
      <tr><td>Alamat</td><td>: Jl. TELEKOMUNIKASI</td></tr>
    </table>
  </div>

  <div class="form">
    <form method="POST" action="">
      <label>Nama <span style="color:red">*</span></label>
      <input type="text" name="nama" required>

      <label>Email <span style="color:red">*</span></label>
      <input type="email" name="email" required>

      <label>Telepon <span style="color:red">*</span></label>
      <input type="text" name="telepon" required>

      <label>Kategori Kendala <span style="color:red">*</span></label>
      <select name="kategori" required>
        <option value="">-- Pilih Kendala --</option>
        <option value="kurangnya info">Kurangnya Info</option>
        <option value="pelayanan">Pelayanan</option>
        <option value="lainnya">Lainnya</option>
      </select>

      <label>Pesan <span style="color:red">*</span></label>
      <textarea name="pesan" rows="5" required></textarea>

      <button type="submit">KIRIM PESAN</button>
      <button type="button" onclick="history.back()">KEMBALI</button>
    </form>
  </div>
</div>

</body>
</html>
