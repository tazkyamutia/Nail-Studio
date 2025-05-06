<?php
// Start session
session_start();

// Include database connection
require_once "../configdb.php";

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define variables
$error = "";

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]);
    
    try {
        // First check if user exists without checking role
        $check_sql = "SELECT * FROM user WHERE username = :username";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':username', $username);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $user_info = $check_stmt->fetch();
            // Debug info (can be removed once working)
            $error = "Found user but role is: " . $user_info["role"] . " (you selected: $role)";
            
            // Now check with role included
            $sql = "SELECT * FROM user WHERE username = '$username' AND role = '$role'";
            $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':username', $username);
            // $stmt->bindParam(':role', $role);
            $stmt->execute();
            
            // Check if user exists with matching role
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch();
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, start session
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["role"] = $user["role"];
                    
                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password";
                }
            } else {
                $error = "User found but role does not match. Please select role: " . $user_info["role"];
            }
        } else {
            $error = "Username not found";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nail Studio</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        .error {
            color: red;
            margin-bottom: 15px;
        }
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
            
            <h2>Login</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="role">Login as</label>
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="member">Member</option>
                </select>
                
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
                
                <div class="bottom-links">
                    <a href="resetpassword.php">Forgot Password?</a>
                </div>
                
                <div class="toggle-link">
                    Don't have an account? <a href="register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>