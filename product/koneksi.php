<?php
//01. Melakukan koneksi ke MySQL dan memilih database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "nailstudio_db";

$conn = mysqli_connect ($host, $user, $password, $dbname);
if ($conn->connect_error) {
	die("Koneksi gagal: " . $conn->connect_error);

}
?>