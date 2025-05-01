<?php
include "koneksi.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['login-role']; // dari <select>

    $sql = "SELECT * FROM user WHERE username = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifikasi password (menggunakan password_hash saat signup)
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan berdasarkan role
            if ($user['role'] === 'admin') {
                header("Location: ./pages/admin/dashboard.php");
            } else if ($user['role'] === 'member') {
                header("Location: ./pages/member/dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('User tidak ditemukan atau role salah.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
