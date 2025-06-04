<?php 
session_start();

// Menghapus semua variabel sesi
session_unset();

// Menghancurkan sesi login
session_destroy();

// Menghapus cookie yang mungkin ada
setcookie('user_id', '', time() - 3600, '/');
setcookie('user_name', '', time() - 3600, '/');
setcookie('user_email', '', time() - 3600, '/');

// Redirect ke halaman login
header("Location: login.php");
exit();
?>