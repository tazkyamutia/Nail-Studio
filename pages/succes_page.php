<?php
include '../views/headers.php'; 

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - Nail Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center px-4">
    <div class="w-full flex justify-center items-center py-20">
        <div class="bg-white max-w-md w-full rounded-xl shadow-lg p-8 text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" alt="Success" class="w-24 h-24 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-pink-600 mb-2">Payment Successful!</h1>
            <p class="text-gray-700 mb-4">Thank you for your payment. We will process your order shortly.</p>

            <div class="mt-6 space-y-2">
                <a href="index.php" class="block w-full text-center bg-pink-600 text-white font-semibold py-2 rounded hover:bg-pink-700 transition">
                    Back to Homepage
                </a>
                <a href="orders.php" class="block w-full text-center bg-gray-200 text-gray-800 font-semibold py-2 rounded hover:bg-gray-300 transition">
                    View Order History
                </a>
            </div>
        </div>
    </div>

    <?php include '../pages/footer.php'; ?>
</body>
</html>
