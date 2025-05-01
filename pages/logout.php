<<<<<<< HEAD
<?php
session_start();p
=======
<?php p
session_start();
>>>>>>> caa486b9bc095ce5f9cac74e43bebdda0757a2e6

// Menghancurkan sesi login
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
