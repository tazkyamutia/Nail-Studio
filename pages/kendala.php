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
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff;
      color: #333;
      padding: 20px;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      max-width: 1000px;
      margin: auto;
      gap: 30px;
    }

    .info {
      flex: 1;
      min-width: 300px;
    }

    .info h2 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .info p {
      margin-bottom: 20px;
    }

    table {
      font-size: 16px;
    }

    .form {
      flex: 1;
      min-width: 300px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    span {
      color: red;
    }

    button {
      margin-top: 15px;
      background-color: rgb(71, 37, 241);
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: rgb(59, 30, 200);
    }

    .result {
      max-width: 800px;
      margin: 20px auto;
      background: #e6ffe6;
      color: #006600;
      padding: 15px;
      border-radius: 6px;
      border: 1px solid #b3ffb3;
    }
  </style>
</head>
<body>

<?php if (isset($sukses) && $sukses): ?>
  <div class="result">✅ Terima kasih! Pesan Anda telah berhasil dikirim.</div>
<?php elseif (isset($sukses) && !$sukses): ?>
  <div class="result" style="background:#ffe6e6; color:#cc0000; border-color:#ffb3b3;">❌ Gagal mengirim pesan. Silakan coba lagi.</div>
<?php endif; ?>

<div class="container">
  <div class="info">
    <h2>Apakah Anda mengalami kendala?</h2>
    <p>Jika ya, silakan isi form berikut dan tim kami akan membantu Anda secepat mungkin.</p>

    <table>
      <tr>
        <td>Studio</td>
        <td>: Nail Art Studio</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>: Jl. TELEKOMUNIKASI</td>
      </tr>
    </table>
  </div>

  <div class="form">
    <form method="POST" action="">
      <label>Nama <span>*</span></label>
      <input type="text" name="nama" required>

      <label>Email <span>*</span></label>
      <input type="email" name="email" required>

      <label>Telepon <span>*</span></label>
      <input type="text" name="telepon" required>

      <label>Kategori Kendala <span>*</span></label>
      <select name="kategori" required>
        <option value="">-- Pilih Kendala --</option>
        <option value="Pembayaran">kurangnya info</option>
        <option value="Pelayanan">Pelayanan</option>
        <option value="Lainnya">Lainnya</option>
      </select>

      <label>Pesan <span>*</span></label>
      <textarea name="pesan" rows="5" required></textarea>

      <button type="submit">KIRIM PESAN</button>
      <button type="button" onclick="history.back()" style="margin-left: 10px; background-color: gray;">KEMBALI</button>
    </form>
  </div>
</div>

</body>
</html>
