<?php 
session_start(); 
require_once "../configdb.php"; 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  

$error = "";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {     
    $username = trim($_POST["username"]);     
    $password = trim($_POST["password"]);     

    try {         
        $check_sql = "SELECT * FROM user WHERE username = :username";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':username', $username);
        $check_stmt->execute();
                
        if ($check_stmt->rowCount() > 0) {             
            $user = $check_stmt->fetch(PDO::FETCH_ASSOC);
                     
            if (password_verify($password, $user['password'])) {                 
                $_SESSION["loggedin"] = true;                     
                $_SESSION["id"] = $user["id"];                     
                $_SESSION["username"] = $user["username"];                     
                $_SESSION["role"] = $user["role"];

                // ✅ UPDATE LAST LOGIN (fix untuk Visitors Today)
                $update_login = $conn->prepare("UPDATE user SET last_login = NOW() WHERE id = :id");
                $update_login->bindParam(':id', $user['id']);
                $update_login->execute();

                // ✅ Redirect sesuai role
                if ($_SESSION["role"] == "admin") {                         
                    header("Location: dashboard.php");                     
                } else if ($_SESSION["role"] == "member") {                         
                    header("Location: index.php");                     
                }                     
                exit;                 
            } else {                     
                $error = "Invalid password";                 
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
        .error { color: red; margin-bottom: 15px; }         
        .right-panel { display: flex; flex-direction: column; justify-content: center; }
        .form-container { width: 100%; max-width: 600px; }
        h2 { text-align: left; margin-bottom: 25px; }
        .login-link { display: flex; justify-content: center; margin-top: 15px; }
        input[type="text"], input[type="password"] {
            height: 50px; border-radius: 8px; margin-bottom: 20px;
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
                <h2>Login</h2>             
                        
                <?php if (!empty($error)): ?>                 
                    <div class="error"><?php echo $error; ?></div>             
                <?php endif; ?>             
                        
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">                 
                    <input type="hidden" name="role" value="member">
                    
                    <label for="username">Username</label>                 
                    <input type="text" id="username" name="username" required>                 
                                    
                    <label for="password">Password</label>                 
                    <input type="password" id="password" name="password" required>                 
                                    
                    <button type="submit" class="signup-btn">Login</button>                 
                </form>
                
                <div class="login-link">                     
                    Don't have an account? <a href="register.php">Sign Up</a>                 
                </div>
            </div>
        </div>     
    </div> 
</body> 
</html>
