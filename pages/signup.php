<?php
session_start();
include 'configdb.php';  // Menyertakan koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];  // Mengambil role (admin atau pelanggan)

    // Validasi apakah password dan konfirmasi password cocok
    if ($password == $confirm_password) {
        // Hash password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data ke database
        $sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed_password', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "Account created successfully!";
            header("Location: login.php");  // Redirect ke halaman login
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Password and confirm password do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <form method="POST" action="signup.php">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br><br>

        <label for="role">Role:</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="pelanggan">Pelanggan</option>
        </select><br><br>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
