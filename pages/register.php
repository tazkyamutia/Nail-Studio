<?php
session_start();
require_once "../configdb.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];
    $role = "member"; // Always set role to member

    if (empty($username) || empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field wajib diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error = "Username dan Email sudah digunakan.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (username, fullname, email, password, role, status) 
                    VALUES (:username, :fullname, :email, :password, :role, 'active')";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);
            if ($stmt->execute()) {
                $error = "Data berhasil disimpan. Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Nail Studio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">     
    <style>         
        .error {             
            color: red;             
            margin-bottom: 15px;         
        }
        /* Added custom styles for this page */
        .right-panel {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 60px 32px 40px 32px;
            overflow-y: auto;
            min-height: 100vh;
            height: 100vh;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            /* Hapus min-height agar tidak memaksa tinggi, biar scroll jalan */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            flex: 1 1 auto;
        }
        .login-link {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            margin-bottom: 0;
            /* Tambahkan min-height agar selalu terlihat */
            min-height: 40px;
        }
        #signup-form {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }
        .signup-btn {
            margin-bottom: 20px;
        }
        h2 {
            text-align: left;
            margin-bottom: 25px;
        }
        /* Pastikan container bisa scroll jika terlalu tinggi */
        .container {
            min-height: 100vh;
            height: 100vh;
            display: flex;
            overflow: auto;
        }
    </style>
</head>
<body>     
    <div class="container">         
        <div class="left-panel">
            <div class="logo-container">
                <img src="../Tazkya-HTML/images/logonails.png" alt="Nail Studio Logo">
            </div>
            <p>Selamat datang di platform kami! Login atau daftar untuk melanjutkan.</p>         
        </div>         
                
        <div class="right-panel">             
            <div class="welcome-box">Welcome!</div>             
                    
            <div class="form-container">
                <h2>Sign Up</h2>     

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>
        
        <form action="" method="post" id="signup-form">
            <!-- Hidden input for role - always set to "member" -->
            <input type="hidden" name="signup-role" value="member">
            
            <!-- Display fixed role to user -->
            <div class="role-indicator">
                <!-- Jika ingin tampilkan peran ke user, bisa tambahkan teks di sini -->
                Anda mendaftar sebagai <strong>Member</strong>.
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>

            <button type="submit" class="signup-btn">Sign Up</button>

            <div class="login-link">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
