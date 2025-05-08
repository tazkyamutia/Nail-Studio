<?php
require_once '../configdb.php'; 

// Cek jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $fullname    = $_POST['fullname'] ?? '';
  $email       = $_POST['email'] ?? '';
  $phone       = $_POST['phone'] ?? '';
  $position    = $_POST['position'] ?? '';
  $description = $_POST['description'] ?? '';


   // email tidak boleh digunakan 2 q
   $checkEmail = $conn->prepare("SELECT COUNT(*) FROM job_applications WHERE email = ?");
   $checkEmail->execute([$email]);
   $emailExists = $checkEmail->fetchColumn();
 
   if ($emailExists > 0) {
     echo "<script>alert('alamat email telah digunkan, silakan gunakan email lain.');</script>";
   } else {

  // Simpan file CV
  if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $cv = $_FILES['cv'];
    $cv_filename = basename($cv['name']);
    $target_dir = "upload/";
    $target_file = $target_dir . $cv_filename;

    // Validasi ekstensi
    if (strtolower(pathinfo($target_file, PATHINFO_EXTENSION)) !== "pdf") {
      die("Only PDF files are allowed.");
    }

    // Upload file
    if (move_uploaded_file($cv['tmp_name'], $target_file)) {
      $stmt = $conn->prepare("INSERT INTO job_applications (fullname, email, phone, position, description, cv_filename) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssss", $fullname, $email, $phone, $position, $description, $cv_filename);

      if ($stmt->execute()) {
        echo "<script>alert('Application submitted successfully!');</script>";
      } else {
        echo "<script>alert('Database error: " . $stmt->error . "');</script>";
      }
      $stmt->close();
    } else {
      echo "<script>alert('Failed to upload CV.');</script>";
    }
  } else {
    echo "<script>alert('Please upload a valid PDF CV.');</script>";
  }
}
}

$conn = null;

?>

<?php include 'hehe.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lamaran Pekerjaan - Nail Art Studio</title>
  <style>
   <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff0f5, #ffd6e8);
      color: #4a004d;
      min-height: 100vh;
    }

    .container {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      padding: 40px;
      flex-wrap: wrap;
    }

    .left-content {
      max-width: 500px;
      flex: 1;
    }

    .left-content h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .left-content p {
      font-size: 16px;
      margin-bottom: 20px;
    }

    form {
      width: 100%;
    }

    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 15px;
      flex-wrap: wrap;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #fff;
      font-family: inherit;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button {
      margin-top: 10px;
      padding: 12px 20px;
      background-color: #ff66b2;
      border: none;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      width: 100%;
    }

    .right-image {
      flex: 1;
      display: flex;
      justify-content: center;
    }

    .right-image img {
      max-width: 100%;
      max-height: 400px;
      border-radius: 20px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        text-align: center;
      }

      .form-row {
        flex-direction: column;
      }
    }
      .right-image {
  flex: 1;
  display: flex;
  justify-content: flex-end; /* geser ke kanan */
  padding-left: 20px;        /* tambah jarak kiri */
}

.right-image img {
  max-width: 600px; /* tambah ukuran gambar */
  width: 100%;
  border-radius: 20px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}



 
  </style>
</head>
<body>
  <div class="container">
    <div class="left-content">
      <h1>Join Our Nail Art Studio</h1>
      <p>We’re looking for creative and passionate individuals to join our team.</p>

      <form action="lamaran.php" method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group">
            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" id="fullname" placeholder="Full Name" required>
          </div>

          <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" placeholder="Email Address" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" id="phone" placeholder="e.g. 081234567890" required>
          </div>

          <div class="form-group">
            <label for="position">Position Applying For:</label>
            <select name="position" id="position" required>
              <option value="" disabled selected>Select Position</option>
              <option value="Nail Artist">Nail Artist</option>
              <option value="Receptionist">Receptionist</option>
              <option value="Customer Service">Customer Service</option>
              <option value="Social Media Manager">Social Media Manager</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="description">Tell us about yourself:</label>
          <textarea name="description" id="description" placeholder="Write a brief description about yourself..." required></textarea>
        </div>

        <div class="form-group">
          <label for="cv">Upload Your CV (PDF only):</label>
          <input type="file" name="cv" id="cv" accept="application/pdf" required>
        </div>

        <button type="submit">SUBMIT APPLICATION</button>
      </form>
    </div>

    <div class="right-image">
      <img src="../Tazkya-HTML/images/cihuy.jpg" alt="Nail Studio Logo">
    </div>
  </div>
</body>
</html>
