<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();
}

// Menampilkan informasi pengguna yang login
echo "Welcome, " . $_SESSION['role'] . "! You are logged in.";
echo "<br><a href='logout.php'>Logout</a>";
?>

<!-- Halaman Dashboard -->
<div>
    <?php
    // Cek role pengguna dan tampilkan konten sesuai role
    if ($_SESSION['role'] == 'admin') {
        echo "<h2>Admin Dashboard</h2>";
        // Konten khusus admin
    } else {
        echo "<h2>Pelanggan Dashboard</h2>";
        // Konten khusus pelanggan
    }
    ?>
</div>
