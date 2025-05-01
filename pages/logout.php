<?php
session_start();p

// Menghancurkan sesi login
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
