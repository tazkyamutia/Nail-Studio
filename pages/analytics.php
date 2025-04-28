<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
	<link rel="stylesheet" href="../Tazkya-HTML/css/style2.css">
	

        <main>
            <div class="analytics">
                <div class="chart-container">
                    <div class="chart-card">
                        <h3>Sales Analytics</h3>
                        <canvas id="salesChart"></canvas>
                    </div>

                    <div class="chart-card">
                        <h3>Visitor Statistics</h3>
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>
                <div class="chart-container">
                    <div class="chart-card">
                        <h3>Product Performance</h3>
                        <canvas id="productChart"></canvas>
                    </div>
                    <div class="chart-card">
                        <h3>Order Status</h3>
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </div>
        </main>

        
    </section>
	
<?php include '../views/footer.php'; ?>