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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login & Sign Up</title>
  <link rel="stylesheet" href="../Tazkya-HTML/css/login.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(120deg, #ff69b4, #ffb6c1);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      width: 95%;
      max-width: 950px;
      height: 90vh;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      background: white;
      display: flex;
      flex-direction: row;
    }

    .left-panel {
      flex: 1;
      background: #fce4ec;
      padding: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .left-panel img {
      max-width: 200px;
      margin-bottom: 30px;
    }

    .left-panel p {
      font-size: 14px;
      color: #555;
    }

    .right-panel {
      flex: 1.2;
      padding: 30px;
      position: relative;
      overflow-y: auto;
    }

    .welcome-box {
      background: #f06292;
      color: white;
      padding: 8px 20px;
      border-radius: 20px;
      position: absolute;
      top: 20px;
      right: 30px;
      font-size: 14px;
    }

    h2 {
      margin-top: 60px;
      margin-bottom: 20px;
      color: #333;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 5px;
      font-size: 14px;
      color: #666;
    }

    input, select {
      padding: 10px;
      margin-bottom: 18px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
    }

    button {
      padding: 12px;
      border: none;
      border-radius: 25px;
      background: linear-gradient(to right, #ec407a, #f06292);
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: linear-gradient(to right, #d81b60, #ec407a);
    }

    .bottom-links {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    .bottom-links a {
      font-size: 12px;
      color: #d81b60;
      text-decoration: none;
    }

    .bottom-links a:hover {
      text-decoration: underline;
    }

    .toggle-link {
      margin-top: 10px;
      font-size: 13px;
      text-align: center;
    }

    .toggle-link a {
      color: #d81b60;
      cursor: pointer;
      text-decoration: none;
    }

    .toggle-link a:hover {
      text-decoration: underline;
    }

    .hidden {
      display: none;
    }

    /* Scroll fix for small screens */
    .right-panel::-webkit-scrollbar {
      width: 6px;
    }

    .right-panel::-webkit-scrollbar-thumb {
      background-color: #ccc;
      border-radius: 3px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <img src="../Tazkya-HTML/images/logonails.png" alt="Illustration" />
      <p>Selamat datang di platform kami! Login atau daftar untuk melanjutkan.</p>
    </div>

    <div class="right-panel">
      <div class="welcome-box">Welcome!</div>

      <!-- Login Form -->
      <div id="login-form">
        <h2>Login</h2>
        <form action="#" method="post">
          <label for="login-role">Login as</label>
          <select id="login-role" name="login-role">
            <option value="admin">Admin</option>
            <option value="pelanggan">Member</option>
          </select>

          <label for="login-username">Username</label>
          <input type="text" id="login-username" name="username" required>

          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="password" required>

          <button type="submit">Login</button>

          <div class="bottom-links">
            <a href="#">Forgot Password?</a>
          </div>

          <div class="toggle-link">
            Don't have an account? <a onclick="showSignup()">Sign Up</a>
          </div>
        </form>
      </div>

      <!-- Signup Form -->
      <div id="signup-form" class="hidden">
        <h2>Sign Up</h2>
        <form action="#" method="post">
          <label for="signup-role">Sign up as</label>
          <select id="signup-role" name="signup-role">
            <option value="admin">Admin</option>
            <option value="pelanggan">Pelanggan</option>
            <option value="nail_artist">Nail Artist</option>
          </select>

          <label for="signup-name">Full Name</label>
          <input type="text" id="signup-name" name="fullname" required>

          <label for="signup-email">Email</label>
          <input type="email" id="signup-email" name="email" required>

          <label for="signup-password">Password</label>
          <input type="password" id="signup-password" name="password" required>

          <label for="signup-confirm">Confirm Password</label>
          <input type="password" id="signup-confirm" name="confirm-password" required>

          <button type="submit">Sign Up</button>

          <div class="toggle-link">
            Already have an account? <a onclick="showLogin()">Back to Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function showSignup() {
      document.getElementById('login-form').classList.add('hidden');
      document.getElementById('signup-form').classList.remove('hidden');
    }

    function showLogin() {
      document.getElementById('signup-form').classList.add('hidden');
      document.getElementById('login-form').classList.remove('hidden');
    }
  </script>
</body>
</html>

