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


	<section id="sidebar">
		<div class="container">
			<div class="navigation">
				<ul>
					<li>
						<a href="#home"> <img src="../Tazkya-HTML/images/logo.png" alt="logo" height="50" width="70"></a>
					</li>
				</ul>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard' ></i>
					<p class="text">Admin Dashboard</p>
				</a>
			</li>
			<li>
				<a href="product.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<p class="text">Product</p>

				</a>
			</li>
			<li>
				<a href="analytics.php">
					<i class='bx bxs-doughnut-chart' ></i>
					<p class="text">Analytics</p>
				</a>
			</li>
			<li>
				<a href="message.php">
					<i class='bx bxs-message-dots' ></i>
					<p class="text">Message</p>
				</a>
			</li>
			<li>
				<a href="transaction.php">
					<i class='bx bxs-credit-card-front'></i>
					<p class="text">Transaction</p>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			
			<li>
				<a href="#" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					
					<p class="text">Logout</p>
				</a>
			</li>
		</ul>
		</div>
	</section>
	
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
					<button onclick="myFunction()" class="dropbtn"><img src="profile.jpg"></button>
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
		