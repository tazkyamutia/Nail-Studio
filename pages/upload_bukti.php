<?php

session_start();
require_once '../configdb.php';

// Redirect jika belum login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// Ambil cart aktif
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

if (!$cart_id) {
    die("Keranjang tidak ditemukan.");
}

// Cek apakah file dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti_bayar'])) {
    $file = $_FILES['bukti_bayar'];
    $upload_dir = '../uploads/';

    // Buat folder upload jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        die("Format file tidak didukung. Hanya jpg, jpeg, png, webp.");
    }

    if ($file['size'] > 2 * 1024 * 1024) { // Maks 2MB
        die("Ukuran file terlalu besar. Maksimum 2MB.");
    }

    $new_filename = uniqid('bukti_', true) . '.' . $file_ext;
    $file_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO bukti_bayar (cart_id, file_path) VALUES (?, ?)");
        $stmt->execute([$cart_id, $new_filename]);

        // Update status cart jadi "paid"
        $update = $conn->prepare("UPDATE cart SET status = 'paid' WHERE id = ?");
        $update->execute([$cart_id]);

        echo "<script>alert('Bukti bayar berhasil diupload!'); window.location.href='succes_page.php';</script>";
    } else {
        die("Gagal mengupload file.");
    }
} else {
    die("Tidak ada file yang dikirim.");
}
?>
