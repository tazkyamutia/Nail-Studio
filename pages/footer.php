<?php
require_once '../configdb.php';

// Memeriksa koneksi
if (!$conn) {
    die("Connection failed: Unable to connect to database.");
}

// Mengecek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Validasi email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: footer.php?message=success");
                exit;  // Penting: Gunakan exit setelah header untuk menghentikan eksekusi
            } else {
                header("Location: footer.php?message=error");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: footer.php?message=error");
            exit;
        }
    } else {
        header("Location: footer.php?message=invalid_email");
        exit;
    }
}
?>

<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Nail Art Studio Footer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: 'Inter', sans-serif;
    }
   #footer-wrapper {
     background: linear-gradient(90deg, #f7d6e0 0%, #c7d9fb 100%);
   }
   .payment-icons img {
     filter: none;
     opacity: 1;
   }
  </style>
 </head>
 <body>
  <div id="footer-wrapper" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">
   <!-- Newsletter Section -->
   <div class="bg-white rounded-lg p-6 sm:p-8 mb-14 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
    <div>
     <h2 class="font-semibold text-lg leading-6 text-[#1a1a1a]">
      Join our newsletter
     </h2>
     <p class="text-sm text-[#4b4b4b] mt-1 max-w-md">
      Be the first to know about our latest updates, exclusive offers, and more.
     </p>
    </div>
    <!-- Form untuk pengiriman email -->
    <form action="footer.php" method="POST" class="flex w-full sm:w-auto gap-3">
     <input class="flex-grow sm:flex-grow-0 sm:w-[220px] rounded-md border border-gray-300 px-4 py-2 text-sm text-[#1a1a1a] placeholder:text-[#4b4b4b] focus:outline-none focus:ring-2 focus:ring-black" placeholder="Enter your email" required="" type="email" name="email"/>
     <button class="bg-black text-white rounded-md px-5 py-2 text-sm font-semibold hover:bg-gray-900 transition" type="submit">
      Subscribe
     </button>
    </form>
   </div>

   
   <!-- Footer Main -->
   <footer class="text-[#1a1a1a]">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-y-10 gap-x-8 text-sm">
     <!-- Logo and Social -->
     <div>
      <h3 class="font-semibold text-base mb-4">
       Nail Art Studio
      </h3>
      <div class="flex space-x-4">
       <a aria-label="Facebook" class="text-[#1a1a1a] hover:text-gray-700" href="#">
        <i class="fab fa-facebook-f text-lg"></i>
       </a>
       <a aria-label="Instagram" class="text-[#1a1a1a] hover:text-gray-700" href="#">
        <i class="fab fa-instagram text-lg"></i>
       </a>
      </div>
     </div>
     <!-- Customer Service -->
     <div>
      <h4 class="font-semibold mb-3">
       Customer Service
      </h4>
      <ul class="space-y-2 text-[#4b4b4b]">
       <li><a class="hover:underline" href="../pages/boking.php">Booking & Appointments</a></li>
       <li><a class="hover:underline" href="#">Frequently Asked Questions</a></li>
       <li><a class="hover:underline" href="#">Cancellation Policy</a></li>
       <li><a class="hover:underline" href="#">Gift Cards</a></li>
       <li><a class="hover:underline" href="../pages/profile.php">My Account</a></li>
       <li><a class="hover:underline" href="#">Payment Options</a></li>
      </ul>
     </div>
     <!-- Products -->
     <div>
      <h4 class="font-semibold mb-3">
       Services
      </h4>
      <ul class="space-y-2 text-[#4b4b4b]">
       <li><a class="hover:underline" href="../pages/nailPolish.php">Nail polish</a></li>
       <li><a class="hover:underline" href="../pages/nailTools.php">Nail tools</a></li>
       <li><a class="hover:underline" href="../pages/nailCare.php">Nail care</a></li>
       <li><a class="hover:underline" href="../pages/nailKit.php">Nail art kits</a></li>
      </ul>
     </div>
     <!-- About Us -->
     <div>
      <h4 class="font-semibold mb-3">
       About Us
      </h4>
      <ul class="space-y-2 text-[#4b4b4b]">
       <li><a class="hover:underline" href="#">Our Story</a></li>
       <li><a class="hover:underline" href="#">Contact Us</a></li>
       <li><a class="hover:underline" href="../pages/addreview.php">Reviews</a></li>
      </ul>
     </div>
     <!-- Company Info -->
     <div>
      <h4 class="font-semibold mb-3">
       Company info
      </h4>
      <div class="text-[#4b4b4b] space-y-3 text-xs max-w-[180px]">
       <div>
        <p class="font-semibold text-[10px] mb-0.5">Address</p>
        <p>Jakarta, Indonesia</p>
       </div>
       <div>
        <p class="font-semibold text-[10px] mb-0.5">Email</p>
        <p>support@nailartstudio.com</p>
       </div>
       <div>
        <p class="font-semibold text-[10px] mb-0.5">Business Number</p>
        <p>123 456 789</p>
       </div>
       <div>
        <p class="font-semibold text-[10px] mb-0.5">Company Reviews</p>
        <div class="flex items-center space-x-2">
         <img alt="Google star rating with 5 stars" class="h-5 w-auto" height="20" src="https://storage.googleapis.com/a1aa/image/849c18c5-0d0a-4934-53d5-277d521e11a7.jpg" width="60"/>
         <img alt="Product Review logo" class="h-5 w-auto" height="20" src="https://storage.googleapis.com/a1aa/image/62aa740b-6dec-41ab-076f-1f3a86b77ca1.jpg" width="80"/>
        </div>
       </div>
      </div>
     </div>
    </div>
    <!-- Bottom Bar -->
    <div class="mt-14 text-center text-[10px] text-[#4b4b4b]">
     <div class="flex flex-col items-center space-y-4">
      <div class="flex flex-wrap gap-4 justify-center">
       <span>© 2025 Nail Art Studio</span>
       <a class="hover:underline" href="#">Privacy Policy</a>
       <a class="hover:underline" href="#">Terms of Service</a>
       <a class="hover:underline" href="#">Security Policy</a>
      </div>
     </div>
    </div>
  </footer>
 </div>

</body>
</html>
