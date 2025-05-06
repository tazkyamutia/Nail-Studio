<link rel="stylesheet" href="../css/login.css">
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
