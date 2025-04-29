<?php
session_start();
include '../configdb.php'; // pastikan file ini ada di root folder

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            header("Location: ../views/dashboard.php"); // pastikan file ini ada
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- FIX: path CSS diperbaiki -->
    <link rel="stylesheet" href="./Tazkya-HTML/css/login.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</body>
</html>
