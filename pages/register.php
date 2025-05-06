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
    $role = $_POST["signup-role"];

    if (empty($username) || empty($fullname) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error = "Semua field wajib diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error = "Username sudah digunakan.";
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
                $success = "Registrasi berhasil. Silakan login.";
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
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <img src="../Tazkya-HTML/images/logonails.png" alt="Nail Studio Logo">
        <p>Selamat datang di platform kami! Login atau daftar untuk melanjutkan.</p>
    </div>
    <div class="right-panel">
        <div class="welcome-box">Welcome!</div>

        <h2>Sign Up</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>

        <form action="" method="post">
            <label for="signup-role">Sign up as</label>
            <select id="signup-role" name="signup-role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="member">Member</option>
            </select>

            <label for="signup-username">Username</label>
            <input type="text" id="signup-username" name="username" required>

            <label for="signup-fullname">Full Name</label>
            <input type="text" id="signup-fullname" name="fullname" required>

            <label for="signup-email">Email</label>
            <input type="email" id="signup-email" name="email" required>

            <label for="signup-password">Password</label>
            <input type="password" id="signup-password" name="password" required>

            <label for="signup-confirm">Confirm Password</label>
            <input type="password" id="signup-confirm" name="confirm-password" required>

            <button type="submit">Sign Up</button>

            <div class="toggle-link">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
