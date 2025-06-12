<?php
session_start();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="../css/success_page_style.css">

</head>
<body class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full flex justify-center items-center py-20">
        <div class="bg-white max-w-md w-full rounded-xl shadow-lg p-8 text-center">
            
            <div class="mb-4">
                <svg class="success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="success-checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="success-checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Payment Successful!</h1>
            <p class="text-gray-600 mb-6">Thank you for your payment. Your order is now being processed.</p>

            <div class="mt-8 space-y-3">
                <a href="index.php" class="block w-full text-center bg-pink-600 text-white font-semibold py-3 rounded-lg hover:bg-pink-700 transition-transform transform hover:scale-105">
                    Back to Homepage
                </a>
                <a href="orders.php" class="block w-full text-center bg-gray-100 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-200 transition-transform transform hover:scale-105">
                    View Order History
                </a>
            </div>
        </div>
    </div>

    <?php include '../pages/footer.php'; ?>
</body>
</html>