<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
  <link rel="stylesheet" href="../css/style2.css">
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
						<h3>30</h3>
						<p>Recent Orders</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3>70</h3>
						<p>Visitors</p>
					</span>
				</li>
				<li>
					<i class='bx bx-wallet'></i>
					<span class="text">
						<h3>Rp 5.000.000</h3>
						<p>Total Sales</p>
					</span>
				</li>
				<li><i class='bx bx-line-chart'></i>
                    <span class="text">
                        <h3>27</h3>
                        <p>Today</p>
                    </span>
                </li>
				
			</ul>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Orders</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>User</th>
								<th>Date Order</th>
										<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="../Tazkya-HTML/images/Irene.jpg">
									<p>Irene</p>
								</td>
								<td>01-10-2024</td>
								<td><span class="status completed">Completed</span></td>
							</tr>
							<tr>
								<td>
									<img src="../Tazkya-HTML/images/seulgi.jpg">
									<p>Seulgi</p>
								</td>
								<td>10-10-2024</td>
								<td><span class="status pending">Pending</span></td>
							</tr>
							<tr>
								<td>
									<img src="../Tazkya-HTML/images/yeri.jpg">
									<p>Yeri</p>
								</td>
								<td>19-10-2024</td>
								<td><span class="status process">Process</span></td>
							</tr>
							<tr>
								<td>
									<img src="../Tazkya-HTML/images/Joy.jpg">
									<p>Joy</p>
								</td>
								<td>15-11-2024</td>
								<td><span class="status pending">Pending</span></td>
							</tr>
							<tr>
								<td>
									<img src="../Tazkya-HTML/images/wendy.jpg">
									<p>Wendy</p>
								</td>
								<td>12-12-2024</td>
								<td><span class="status completed">Completed</span></td>
							</tr>
						</tbody>
					</table>
				</div>
				
				
			</div>
		</main>
		
	</section>

	

<?php include '../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/dashboard.js"></script>