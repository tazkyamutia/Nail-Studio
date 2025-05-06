<?php
// test_db.php - Place this in your pages directory
require_once "../configdb.php";

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Database Connection Test</h2>";

try {
    // Check connection
    echo "Database connection successful!<br>";
    
    // List all tables
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tables in database:</h3>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";
    
    // Check if user table exists
    if (in_array('user', $tables)) {
        // List user table structure
        echo "<h3>User table structure:</h3>";
        $columns = $conn->query("DESCRIBE user")->fetchAll();
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
        
        // List all users
        echo "<h3>Users in database:</h3>";
        $users = $conn->query("SELECT * FROM user")->fetchAll();
        echo "<pre>";
        print_r($users);
        echo "</pre>";
        
        // Try to login with hardcoded credentials
        $username = "admin";
        $password = "admin123"; // Plain text password
        
        // Get user by username
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            echo "<h3>Found user:</h3>";
            echo "<pre>";
            print_r($user);
            echo "</pre>";
            
            // Test password
            if (password_verify($password, $user['password'])) {
                echo "<p style='color:green;'>Password verification successful!</p>";
            } else {
                echo "<p style='color:red;'>Password verification failed!</p>";
                echo "<p>Stored hash: " . $user['password'] . "</p>";
                echo "<p>Test password: " . $password . "</p>";
                echo "<p>New hash for 'admin123': " . password_hash("admin123", PASSWORD_DEFAULT) . "</p>";
            }
        } else {
            echo "<p style='color:red;'>User 'admin' not found!</p>";
        }
    } else {
        echo "<p style='color:red;'>User table does not exist!</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>