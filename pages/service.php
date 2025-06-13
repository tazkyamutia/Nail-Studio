
<?php
session_start();

include '../configdb.php';

// SEARCH FAQ (dari tabel faq)
$search = '';
$where = '';
$params = [];
$faqs = [];



if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $search = trim($_GET['q']);
    $where = "WHERE question LIKE :search OR answer LIKE :search";
    $params[':search'] = "%$search%";
    $sql = "SELECT question, answer FROM faq $where ORDER BY id ASC";
} else {
    // Tampilkan hanya 4 pertanyaan jika tidak search
    $sql = "SELECT question, answer FROM faq ORDER BY id ASC LIMIT 3";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HANDLE FORM SUBMIT (faq_questions)
$success = '';
$error = '';
if (isset($_POST['submit_question'])) {
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $question = trim($_POST['member_question'] ?? '');
    if ($question !== '') {
        $sqlQ = "INSERT INTO faq_questions (user_id, question) VALUES (:user_id, :question)";
        $stmtQ = $conn->prepare($sqlQ);
        $stmtQ->bindValue(':user_id', $user_id);
        $stmtQ->bindValue(':question', $question);
        if ($stmtQ->execute()) {
            $success = "Pertanyaan Anda berhasil dikirim! Admin akan segera menjawab.";
        } else {
            $error = "Terjadi kesalahan saat mengirim pertanyaan. Coba lagi!";
        }
    } else {x
        $error = "Pertanyaan tidak boleh kosong.";
    }
}

// Siapkan nama user untuk header
$nama_user = 'Guest';
if (isset($_SESSION['fullname']) && $_SESSION['fullname'] != '') {
    $nama_user = $_SESSION['fullname'];
} else if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
    $nama_user = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>FAQ Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
   body { font-family: "Inter", sans-serif; }
  </style>
</head>
<body class="bg-[#ffecf5] min-h-screen flex flex-col">
  <header class="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto w-full">
   <nav class="flex space-x-8 text-[10px] sm:text-xs font-semibold tracking-widest uppercase text-[#3a3a3a]">
    <a class="text-[#e26fa3]" href="#">Contact Us</a>
    <a class="hover:underline" href="index.php">Home</a>
    <a class="hover:underline" href="abouteUss.php">About Us</a>
   </nav>
   <div>
    <a class="text-[10px] sm:text-xs font-semibold tracking-widest uppercase text-[#3a3a3a] underline decoration-[#e26fa3] decoration-1 underline-offset-2" href="#">
      Hello, <?= htmlspecialchars($nama_user) ?>
    </a>
   </div>
  </header>
  <main class="flex flex-col md:flex-row max-w-7xl mx-auto flex-grow px-6 py-10 gap-10 md:gap-20">
   <section class="md:w-1/2 max-w-md">
    <h1 class="text-[#3a3a59] font-extrabold text-2xl sm:text-3xl leading-tight mb-6">
     Frequently Asked<br/>Questions
    </h1>
    <!-- Search form -->
    <form class="mb-8 relative" method="get" action="">
      <input
        name="q"
        value="<?= htmlspecialchars($search) ?>"
        class="w-full bg-[#f3f3e9] text-xs sm:text-sm text-[#a3a39a] font-light italic rounded-md py-2 pl-4 pr-10 placeholder-[#a3a39a] focus:outline-none"
        placeholder="Search question here"
        type="text"
        autocomplete="off"
      />
      <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-none">
        <svg aria-hidden="true" class="w-4 h-4 text-[#a3a39a]" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="7"></circle>
          <line x1="21" x2="16.65" y1="21" y2="16.65"></line>
        </svg>
      </button>
    </form>
    <!-- End search form -->

    <!-- Collapsible FAQ List -->
    <div class="space-y-2 text-[10px] sm:text-xs text-[#3a3a3a] font-light" id="faq-list">
      <?php if ($faqs && count($faqs) > 0): ?>
        <?php foreach ($faqs as $index => $row): ?>
          <div class="border-b border-[#eee] py-2">
            <button
              type="button"
              class="flex justify-between items-center w-full font-semibold text-left focus:outline-none faq-toggle"
              data-index="<?= $index ?>">

              <?= htmlspecialchars($row['question']) ?>
              <svg class="w-3 h-3 ml-2 transition-transform duration-200" data-icon="<?= $index ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>
            <div class="faq-answer hidden pl-2 mt-1 text-[#a3a39a] leading-tight">
              <?= nl2br(htmlspecialchars($row['answer'])) ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div>
          <p>Tidak ada data FAQ.</p>
        </div>
      <?php endif; ?>
    </div>
    <!-- End Collapsible FAQ List -->

    <!-- FORM AJUKAN PERTANYAAN -->
    <hr class="my-8"/>
    <h2 class="font-bold mb-2 text-[#3a3a59]">Ajukan Pertanyaan Anda</h2>
    <?php if ($success): ?>
      <div class="text-green-600 mb-3"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="text-red-600 mb-3"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-2 mb-8">
      <textarea name="member_question" required class="w-full px-3 py-2 rounded border border-gray-300 text-xs" placeholder="Tulis pertanyaan di sini"></textarea>
      <button type="submit" name="submit_question" class="bg-[#e26fa3] text-white px-4 py-2 rounded text-xs font-bold">Kirim Pertanyaan</button>
    </form>
   </section>
   <section class="md:w-1/2 flex justify-center items-center">
    <img src="../uploads/jara..jpg" alt="Manfaat About Us" width="400" style="height:auto;">

   </section>
  </main>
  <script>
    // Collapsible logic
    document.querySelectorAll('.faq-toggle').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var answer = this.parentElement.querySelector('.faq-answer');
        answer.classList.toggle('hidden');
        // Panah animasi
        var icon = this.querySelector('svg');
        if (answer.classList.contains('hidden')) {
          icon.style.transform = '';
        } else {
          icon.style.transform = 'rotate(180deg)';
        }
      });
    });
  </script>
  
</body>
</html>
<?php $conn = null; ?>
