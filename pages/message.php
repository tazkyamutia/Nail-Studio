<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
<?php include '../configdb.php'; ?>
<link rel="stylesheet" href="../css/style2.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>FAQ message</h1>
            <ul class="breadcrumb">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">FAQ</a></li>
            </ul>
        </div>
    </div>

    <div class="faq-questions">
        <h2>Pertanyaan Member</h2>
        <?php
        // 1. Proses submit jawaban admin (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faq_id'], $_POST['answer'])) {
            $faq_id = (int)$_POST['faq_id'];
            $answer = trim($_POST['answer']);
            if ($answer !== '') {
                // a. Update jawaban dan status di faq_questions
                $stmt = $conn->prepare("UPDATE faq_questions SET answer=?, status='answered' WHERE id=?");
                $stmt->execute([$answer, $faq_id]);

                // b. Ambil pertanyaan & jawaban dari pertanyaan yang baru dijawab
                $stmt2 = $conn->prepare("SELECT question, answer FROM faq_questions WHERE id=?");
                $stmt2->execute([$faq_id]);
                $row = $stmt2->fetch(PDO::FETCH_ASSOC);

                // c. Cek apakah sudah ada di tabel faq
                $stmt3 = $conn->prepare("SELECT COUNT(*) FROM faq WHERE question=? AND answer=?");
                $stmt3->execute([$row['question'], $row['answer']]);
                $exists = $stmt3->fetchColumn();

                // d. Jika belum ada, tambahkan ke tabel faq
                if (!$exists) {
                    $stmt4 = $conn->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
                    $stmt4->execute([$row['question'], $row['answer']]);
                }

                echo "<div class='alert-success'>Jawaban berhasil dipublish & ditambahkan ke FAQ.</div>";
            }
        }

        // 2. Ambil semua pertanyaan dari user (urut terbaru)
        $stmt = $conn->query("SELECT * FROM faq_questions ORDER BY created_at DESC");
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$faqs) {
            echo "<div>Tidak ada pertanyaan dari member.</div>";
        } else {
            foreach ($faqs as $faq) {
                echo "<div class='faq-box'>";
                echo "<div class='faq-question'><b>Pertanyaan:</b> ".htmlspecialchars($faq['question'])."</div>";
                echo "<div class='faq-meta'><small>Dari user_id: {$faq['user_id']} | Status: {$faq['status']}</small></div>";
                // Jika sudah dijawab
                if ($faq['status'] == 'answered') {
                    echo "<div class='faq-answer'><b>Jawaban Admin:</b><br>".nl2br(htmlspecialchars($faq['answer']))."</div>";
                } else {
                    // Form jawaban untuk admin
                    ?>
                    <form method="post" class="faq-answer-form">
                        <input type="hidden" name="faq_id" value="<?= $faq['id'] ?>">
                        <textarea name="answer" rows="2" required placeholder="Tulis jawaban admin..."></textarea>
                        <button type="submit">Publish Jawaban</button>
                    </form>
                    <?php
                }
                echo "</div>";
            }
        }
        ?>
    </div>
</main>

<?php include '../views/footer.php'; ?>

<style>
.faq-questions { margin: 32px 0; }
.faq-box { border: 1px solid #eee; padding: 16px 20px; margin-bottom: 20px; border-radius: 8px; background: #fff; }
.faq-question { font-size: 1.1em; margin-bottom: 6px; }
.faq-meta { color: #888; font-size: .9em; margin-bottom: 10px; }
.faq-answer { background: #f9f9f9; border-radius: 5px; padding: 10px; margin-top: 8px; }
.faq-answer-form textarea { width: 100%; padding: 8px; border-radius: 6px; margin-bottom: 8px; border: 1px solid #ccc;}
.faq-answer-form button { background: #8B1D3B; color: #fff; border: none; border-radius: 5px; padding: 7px 16px; cursor: pointer; }
.alert-success { background: #d0ffd8; border: 1px solid #96d8a8; padding: 8px 16px; border-radius: 6px; margin-bottom: 18px; color: #276b3d;}
</style>
