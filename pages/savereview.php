<?php
// === FILE: savereview.php ===
require_once '../configdb.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    // Validasi & Ambil data
    $user_id = $_POST['user_id'] ?? null;
    $product_id = $_POST['product_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? '';
    $tag = $_POST['tag'] ?? '';
    $recommend = $_POST['recommend'] ?? '';
    $repurchase = $_POST['repurchase'] ?? '';
    $usage_period = $_POST['usage_period'] ?? '';

    $packaging = $_POST['packaging'] ?? 0;
    $pigmentation = $_POST['pigmentation'] ?? 0;
    $longwear = $_POST['longwear'] ?? 0;
    $texture = $_POST['texture'] ?? 0;
    $value_for_money = $_POST['value_for_money'] ?? 0;

    if (!$user_id || !$product_id || !$rating || !$comment) {
        throw new Exception("Semua field wajib diisi.");
    }

    // Handle upload foto
    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            throw new Exception("Format gambar tidak didukung.");
        }

        if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            throw new Exception("Ukuran gambar terlalu besar (maks 5MB)");
        }

        $filename = uniqid('review_') . ".{$ext}";
        $uploadPath = '../uploads/' . $filename;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            throw new Exception("Gagal mengunggah gambar.");
        }
        $photo = $filename;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO reviews
        (user_id, product_id, rating, comment, tag, recommend, repurchase, usage_period,
        packaging, pigmentation, longwear, texture, value_for_money, photo, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->execute([
        $user_id, $product_id, $rating, $comment, $tag,
        $recommend, $repurchase, $usage_period,
        $packaging, $pigmentation, $longwear, $texture, $value_for_money,
        $photo
    ]);

    // Redirect setelah sukses
    header("Location: showreview.php?success=1");
    exit;

} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='addreview.php'>← Kembali ke form</a>";
}
?>
