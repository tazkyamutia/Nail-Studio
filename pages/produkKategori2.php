<?php
$categories = [
    [
        "name" => "Nail Polish",
        "img" => "https://storage.googleapis.com/a1aa/image/3454039a-1ec0-4d5b-d768-5eb40bc6fdf3.jpg",
        "alt" => "Classic manicure nails with red polish on a light pink background"
    ],
    [
        "name" => "Nail Tools",
        "img" => "https://storage.googleapis.com/a1aa/image/19a38516-0222-4fe4-e0e8-8b6788672e73.jpg",
        "alt" => "Gel nails with shiny finish on a light pink background"
    ],
    [
        "name" => "Nail Care",
        "img" => "https://storage.googleapis.com/a1aa/image/795a778f-295f-402e-a035-64817b5dd80d.jpg",
        "alt" => "Nail art with floral and geometric designs on a light pink background"
    ],
    [
        "name" => "Nail Art Kits",
        "img" => "https://storage.googleapis.com/a1aa/image/20882152-849e-49b3-885b-fbfe303673a2.jpg",
        "alt" => "Acrylic nails with glitter and rhinestones on a light pink background"
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Nail Art Studio & Nail Art Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            background-color: #fefcfb;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            overflow-x: hidden;
        }

        #modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 50;
        }

        #modal-backdrop.active {
            display: block;
        }

        #custom-modal {
            position: fixed;
            top: 100px;
            left: 0;
            bottom: 0;
            width: 100%;
            max-width: 360px;
            background: white;
            border-radius: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.5rem;
            z-index: 51;
            display: none;
            box-sizing: border-box;
            max-height: calc(100vh - 100px);
        }

        #custom-modal.active {
            display: block;
        }

        #custom-modal header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        #custom-modal header h2 {
            font-size: 5rem;
            font-weight: 700;
        }

        #custom-modal button.close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        #custom-modal nav a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            color: #111827;
            text-decoration: none;
            border-bottom: 1px solid #e5e7eb;
        }

        #custom-modal nav a:last-child {
            border-bottom: none;
        }
        #custom-modal::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}

#custom-modal {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE 10+ */
}

    </style>
</head>
<body class="overflow-x-hidden">

<div class="max-w-7xl mx-auto">
    <header class="flex items-center space-x-4 mb-8" style="height:72px;">
        <button aria-label="Menu" id="open-modal-btn" class="text-black text-3xl leading-none">☰</button>
        <h1 class="font-serif font-bold text-3xl leading-none">Nail Art Studio</h1>
    </header>
</div>

<!-- Modal backdrop -->
<div id="modal-backdrop"></div>

<!-- Modal -->
<div id="custom-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title"  style="font-weight: 700;">
    <header>
       <h2 id="modal-title">
  Shop Categories<br>
  Menu
</h2>

        <button class="close-btn" aria-label="Close modal">&times;</button>
    </header>

    <main>
        <ul class="space-y-2 max-w-md mb-6">
            <?php foreach ($categories as $category): ?>
                <li class="flex justify-between items-center bg-[#fefcfb] border border-[#f0e9e8] rounded-md px-4 py-3 overflow-hidden">
                    <span class="truncate max-w-[70%] block"><?= htmlspecialchars($category['name']) ?></span>
                    <div class="flex-shrink-0 ml-3 w-10 h-10">
                        <img
                            src="<?= htmlspecialchars($category['img']) ?>"
                            alt="<?= htmlspecialchars($category['alt']) ?>"
                            class="w-full h-full rounded-md object-cover block"
                        />
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <nav class="divide-y divide-gray-200 border-t border-b border-gray-200 mt-3">
            <a href="#" class="flex items-center gap-2 py-3 text-gray-900 text-base font-normal">
                <i class="far fa-user text-lg"></i> Sign in
            </a>
            <a href="#" class="flex items-center gap-2 py-3 text-gray-900 text-base font-normal">
                <i class="far fa-heart text-lg"></i> Wishlist
            </a>
            <a href="#" class="flex items-center justify-between py-3 text-gray-900 text-base font-normal">
                <div class="flex items-center gap-2">
                    <i class="far fa-credit-card text-lg"></i> Payment Options
                </div>
                <i class="fas fa-plus text-lg"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 text-gray-900 text-base font-normal">
                <div class="flex items-center gap-2">
                    <i class="far fa-question-circle text-lg"></i> Customer Service
                </div>
                <i class="fas fa-plus text-lg"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 text-gray-900 text-base font-normal">
                <div class="flex items-center gap-2">
                    <i class="far fa-smile text-lg"></i> About Us
                </div>
                <i class="fas fa-plus text-lg"></i>
            </a>
        </nav>
    </main>
</div>

<script>
    const openBtn = document.getElementById('open-modal-btn');
    const modal = document.getElementById('custom-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const closeBtn = modal.querySelector('.close-btn');

    function openModal() {
        modal.classList.add('active');
        backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
</script>

</body>
</html>
