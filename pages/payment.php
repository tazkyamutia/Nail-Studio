 <?php include '../views/navbar.php'; ?>
<?php
$title = "Nail Art Studio";
$intro = "Nail Art Studio is thrilled to introduce our new appointment and design selection system! Booking your perfect nail style has never been easier.";
$steps = [
  [
    "number" => 1,
    "title" => "Select your nail art design",
    "content" => "Browse through our creative collection or work with our experts to create a custom nail art design. Pick the style that fits your personality best."
  ],
  [
    "number" => 2,
    "title" => "Book your appointment",
    "content" => "Choose a date and time that suits you. Our friendly and professional team will take care of the rest, ensuring you receive top-notch service and beautiful results."
  ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Arial', sans-serif;
    }
  </style>
</head>
<body class="bg-pink-200 text-gray-900">

  <!-- Tombol Home -->
   

  <!-- Header -->
  <section class="text-center py-12 px-4 bg-pink-300">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-4xl font-bold"><?= $title ?></h1>
      <p class="mt-4 text-base leading-relaxed"><?= $intro ?></p>
    </div>
  </section>

  <!-- Steps Section -->
  <section class="bg-white py-16 px-6">
    <div class="max-w-5xl mx-auto">
      <h2 class="text-2xl font-bold mb-10 text-center">How <?= $title ?> Works</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <?php foreach ($steps as $step): ?>
        <div class="text-center md:text-left">
          <div class="flex items-center justify-center md:justify-start mb-4">
            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-pink-100 text-pink-700 font-bold text-sm">
              <?= $step['number'] ?>
            </div>
            <h3 class="ml-3 font-semibold text-lg"><?= $step['title'] ?></h3>
          </div>
          <p class="text-gray-700 text-sm leading-relaxed"><?= $step['content'] ?></p>
        </div>
        
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   How Afterpay works
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: 'Inter', sans-serif;
    }
  </style>
 </head>
 <body class="bg-white text-[#1a1a1a]">
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
   <h2 class="text-lg font-semibold mb-8">
    How Afterpay works
   </h2>
   <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Left Column -->
    <section>
     <div class="flex items-center mb-3">
      <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#e6f0fa] text-[#1a1a1a] font-semibold mr-3 select-none">
       1
      </div>
      <h3 class="text-base font-semibold leading-tight">
       Select Afterpay at checkout
      </h3>
     </div>
     <p class="text-sm leading-relaxed mb-6 max-w-md">
      Afterpay is fully integrated with Cosmetic Capital's checkout. All you
          need to do is choose Afterpay as your payment option when you’re ready
          to buy.
     </p>
     <div aria-label="Payment options with Afterpay selected" class="border border-[#b94a5b] rounded-md max-w-md p-4">
      <h4 class="font-bold text-sm mb-1">
       Payment
      </h4>
      <p class="text-xs text-gray-600 mb-3">
       All transactions are secure and encrypted.
      </p>
      <form class="text-xs text-[#1a1a1a] space-y-2">
       <label class="flex items-center justify-between border border-gray-300 rounded px-2 py-1 cursor-pointer">
        <input aria-label="Credit card payment option" class="mr-2" name="payment" type="radio"/>
        Credit card
        <div class="flex space-x-1">
         <img alt="Visa card logo" class="h-4 w-auto" height="16" src="https://storage.googleapis.com/a1aa/image/8a45ca36-fae7-424d-6c6b-f6f4a8fba8a3.jpg" width="24"/>
         <img alt="Mastercard logo" class="h-4 w-auto" height="16" src="https://storage.googleapis.com/a1aa/image/e2c2b1e3-87a3-4a0f-8215-f64a3e8986b4.jpg" width="24"/>
         <img alt="American Express card logo" class="h-4 w-auto" height="16" src="https://storage.googleapis.com/a1aa/image/463ec09a-ccbc-45cd-ada9-f9090152aa42.jpg" width="24"/>
         <img alt="UnionPay card logo" class="h-4 w-auto" height="16" src="https://storage.googleapis.com/a1aa/image/135a7e74-f320-4e59-8aa0-7c1017c07ce4.jpg" width="24"/>
         <img alt="JCB card logo" class="h-4 w-auto" height="16" src="https://storage.googleapis.com/a1aa/image/52f72204-7db8-4524-9d4c-734c8632ac3c.jpg" width="24"/>
        </div>
       </label>
       <label class="flex items-center justify-between border border-gray-300 rounded px-2 py-1 cursor-pointer">
        <input aria-label="Zip - Buy now, pay later payment option" class="mr-2" name="payment" type="radio"/>
        Zip - Buy now, pay later
        <img alt="Zip logo" class="h-4 w-auto" height="16" src="https://storage.googleapis.com/a1aa/image/669dccee-807f-457c-9748-f08e3066f124.jpg" width="40"/>
       </label>
       <label aria-checked="true" class="flex items-center justify-between border border-gray-300 rounded bg-[#d9e8d9] px-2 py-1 cursor-pointer" role="radio" tabindex="0">
        <input aria-label="Afterpay payment option selected" checked="" class="mr-2" name="payment" type="radio"/>
        Afterpay
        <svg aria-hidden="true" class="h-5 w-5 text-[#2f7a2f]" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
         <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round">
         </path>
        </svg>
       </label>
      </form>
      <div class="border border-gray-300 rounded bg-[#f0f2f0] mt-3 p-6 text-center text-xs text-[#1a1a1a]">
       <img alt="Browser window with arrow pointing right" class="mx-auto mb-2" height="80" src="https://storage.googleapis.com/a1aa/image/b2c74665-4f89-4acb-7bdb-9ca551ef1fca.jpg" width="160"/>
       After clicking “Pay now”, you will be redirected to Afterpay to
            complete your purchase securely.
      </div>
     </div>
    </section>
    <!-- Right Column -->
    <section>
     <div class="flex items-center mb-3">
      <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#e6f0fa] text-[#1a1a1a] font-semibold mr-3 select-none">
       2
      </div>
      <h3 class="text-base font-semibold leading-tight">
       Choose instalment payment method
      </h3>
     </div>
     <p class="text-sm leading-relaxed mb-6 max-w-md">
      Afterpay splits your payments over four equal instalments due every
          fortnight. Nominate the debit card or credit card you want to use and
          Afterpay will schedule automatic payments for you.
     </p>
     <div aria-label="Mobile phone showing Afterpay checkout screen" class="border border-[#b94a5b] rounded-md max-w-md p-4">
      <img alt="Mobile phone showing Afterpay checkout screen with order payment dates and linked payment method" class="mx-auto max-w-full h-auto" height="400" src="https://storage.googleapis.com/a1aa/image/7e9b2fd3-c66a-40cc-2d09-cc082a0dc363.jpg" width="320"/>
     </div>
    </section>
   </div>
  </main>
 </body>
</html>


<?php
// Menetapkan konten untuk halaman
$title = "Afterpay Steps";
$steps = [
    [
        'number' => '3',
        'title' => 'Get approved instantly and securely',
        'description' => 'No long forms or detailed personal information. Afterpay simply uses your nominated bank card to process your application on the spot.',
        'image' => 'https://storage.googleapis.com/a1aa/image/8f3a201c-2c09-4445-c3d0-b365d788ea0b.jpg'
    ],
    [
        'number' => '4',
        'title' => 'Enjoy your purchase',
        'description' => 'Afterpay handles reminders and automatic payments for you. Your saved details are ready for your next use.',
        'image' => 'https://storage.googleapis.com/a1aa/image/ac3437bc-f896-4e86-91bf-e7e7a3444b50.jpg'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title><?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <main class="max-w-5xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
            <?php foreach ($steps as $step): ?>
                <section>
                    <h3 class="flex items-center font-semibold text-base text-gray-900 mb-2">
                        <span class="mr-2 font-bold text-sm bg-gray-200 text-gray-900 rounded-full p-3"><?php echo $step['number']; ?></span>
                        <?php echo $step['title']; ?>
                    </h3>
                    <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                        <?php echo $step['description']; ?>
                    </p>
                    <img alt="<?php echo $step['title']; ?>" class="w-full rounded-lg object-cover" height="400" loading="lazy" src="<?php echo $step['image']; ?>" width="600"/>
                </section>
            <?php endforeach; ?>
            <section>
               
           

        </div>
    </main>
</body>
</html>
<?php include 'footer.php'; ?>