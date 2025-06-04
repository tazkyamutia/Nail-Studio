<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
<?php include '../configdb.php'; ?>
<link rel="stylesheet" href="../css/style2.css">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Transaction</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <div class="header"></div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Nama Pembeli</th>
                                <th>Daftar Barang</th>
                                <th>Harga per Item</th>
                                <th>Total Harga</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTable">
                            <tr>
                                <td>#001</td>
                                <td>Kamila</td>
                                <td>
                                    <ul>
                                        <li>Pink Perfection</li>
                                        <li>Green Elegance</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>Rp 120.000</li>
                                        <li>Rp 130.000</li>
                                    </ul>
                                </td>
                                <td>Rp 250.000</td>
                                <td class="rating">★★★★★</td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Zahara</td>
                                <td>
                                    <ul>
                                        <li>Glamour's</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>Rp 165.000</li>
                                    </ul>
                                </td>
                                <td>Rp 165.000</td>
                                <td class="rating">★★★★☆</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="load-more" id="loadMore">Fit More</button>
            </div>
        </main>
    </section>
    
    <?php include '../views/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/dashboard.js"></script>