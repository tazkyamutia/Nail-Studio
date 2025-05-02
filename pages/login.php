<link rel="stylesheet" href="../css/login.css">

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

  <script src="../js/login.js">
</body>
</html>
