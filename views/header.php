<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../Tazkya-HTML/css/style2.css">
	<title>Nails</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

	<section id="content">
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link"></a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button class="search-btn" type="submit"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<a href="notif.html" class="notification">
				<i class='bx bxs-bell' ></i>
				<p class="num">10</p>
			</a>
			<a href="#" class="profile">
				<div class="dropdown" >
					<button onclick="myFunction()" class="dropbtn"><img src="../Tazkya-HTML/images/profile.jpg"></button>
					<div id="myDropdown" class="dropdown-content">
					  <a href="profile.html">
						<i class='far fa-user-circle'></i>
						<p>Profile</p>
					  </a>
					  <a href="index.php">Logout</a>
					</div>
				  </div>
			</a>
		</nav>
		