<?php
// Include database connection
require_once "../configdb.php";

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Clear any existing user with username 'admin' to avoid duplicates
    $delete_sql = "DELETE FROM user WHERE username = 'admin'";
    $conn->exec($delete_sql);
    
    // Create a new admin user with known credentials
    $username = 'admin';
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $email = 'admin@example.com';
    $fullname = 'Administrator';
    $role = 'admin';
    
    $sql = "INSERT INTO user (username, password, email, fullname, role, status) 
            VALUES (:username, :password, :email, :fullname, :role, 'active')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':role', $role);
    
    $stmt->execute();
    
    echo "<h1>Admin User Created Successfully!</h1>";
    echo "<p>Username: admin</p>";
    echo "<p>Password: admin123</p>";
    echo "<p>Now go to <a href='login.php'>login.php</a> and try these credentials.</p>";
    
} catch (PDOException $e) {
    echo "<h1>Error Creating Admin User</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>