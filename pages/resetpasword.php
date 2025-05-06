<?php
// Include database connection
require_once "../configdb.php";

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // User to update
    $username = 'testadmin'; // This is the username from your database
    
    // New password
    $new_password = 'admin123';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the password
    $sql = "UPDATE user SET password = :password WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':username', $username);
    
    if($stmt->execute()) {
        echo "<h1>Password Reset Successfully!</h1>";
        echo "<p>Username: testadmin</p>";
        echo "<p>New Password: admin123</p>";
        echo "<p>Now go to <a href='login.php'>login.php</a> and try these credentials.</p>";
    } else {
        echo "<h1>Failed to Reset Password</h1>";
    }
    
} catch (PDOException $e) {
    echo "<h1>Error Resetting Password</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>