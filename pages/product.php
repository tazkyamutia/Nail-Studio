<?php include '../views/header.php'; ?>
<?php include '../views/sidebar.php'; ?>
	<link rel="stylesheet" href="../Tazkya-HTML/css/style2.css">
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
							<a class="active" href="#">Product</a>
						</li>
					</ul>
				</div>
				
			</div>
		</main>
		
<header>
    <h1></h1>
    <div>
        <button style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px;">+ Add Product</button>
    </div>
</header>

<div class="search-bar">
    <input type="text" id="search" placeholder="Search products...">
</div>

<table id="product-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Status</th>
            <th>Added</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-1.jpg" alt="Pink Perfection">
                    <span>PINK PERFECTION</span>
                </div>
            </td>
            <td>Almond Nails</td>
            <td>10</td>
            <td>Rp120.000</td>
            <td><span class="status low-stock">Low Stock</span></td>
            <td>29 Dec 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
        <tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-2.jpg" alt="Green Elegance">
                    <span>GREEN ELEGANCE</span>
                </div>
            </td>
            <td>Oval Nails</td>
            <td>30</td>
            <td>Rp130.000</td>
            <td><span class="status published">Published</span></td>
            <td>24 Nov 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
        <tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-3.jpg" alt="Tropical Bloom Nails">
                    <span>TROPICAL BLOOM NAILS</span>
                </div>
            </td>
            <td>Lipstick Nails</td>
            <td>25</td>
            <td>Rp140.000</td>
            <td><span class="status draft">Draft</span></td>
            <td>12 Dec 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-4.jpg" alt="Elegant Ruby Glam Nails">
                    <span>ELEGANT RUBY GLAM NAILS</span>
                </div>
            </td>
            <td>Oval Nails</td>
            <td>35</td>
            <td>Rp110.000</td>
            <td><span class="status draft">Draft</span></td>
            <td>9 Oct 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-5.jpg" alt="Black Cute Nails">
                    <span>BLACK CUTE NAILS</span>
                </div>
            </td>
            <td>Almond Nails</td>
            <td>55</td>
            <td>Rp125.000</td>
            <td><span class="status draft">Draft</span></td>
            <td>15 Aug 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-6.jpg" alt="Glamour's">
                    <span>GLAMOUR'S</span>
                </div>
            </td>
            <td>Lipstick Nails</td>
            <td>15</td>
            <td>Rp165.000</td>
            <td><span class="status low-stock">Low Stock</span></td>
            <td>29 Nov 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-7.jpg" alt="Fake Pink's">
                    <span>FAKE PINK'S</span>
                </div>
            </td>
            <td>Almond Nails</td>
            <td>45</td>
            <td>Rp200.000</td>
            <td><span class="status published">Published</span></td>
            <td>12 Sept 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-8.jpg" alt="Cow's">
                    <span>COW'S</span>
                </div>
            </td>
            <td>Oval nails</td>
            <td>26</td>
            <td>Rp135.000</td>
            <td><span class="status published">Published</span></td>
            <td>10 Oct 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-9.jpg" alt="Flower's">
                    <span>FLOWER'S</span>
                </div>
            </td>
            <td>Almond Nails</td>
            <td>45</td>
            <td>Rp170.000</td>
            <td><span class="status low-stock">Low Stock</span></td>
            <td>25 Oct 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-10.jpg" alt="Blackie's">
                    <span>BLACKIE'S</span>
                </div>
            </td>
            <td>Lipstick Nails</td>
            <td>65</td>
            <td>Rp115.000</td>
            <td><span class="status published">Published</span></td>
            <td>10 Oct 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-11.jpg" alt="Azure Dream">
                    <span>AZURE DREAM</span>
                </div>
            </td>
            <td>Oval Nails</td>
            <td>15</td>
            <td>Rp115.000</td>
            <td><span class="status draft">Draft</span></td>
            <td>15 Aug 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
		<tr>
            <td>
                <div style="display: flex; align-items: center;">
                    <img src="nail-art-12.jpg" alt="Crimson Noir">
                    <span>CRIMSON NOIR</span>
                </div>
            </td>
            <td>Lipstick Nails</td>
            <td>67</td>
            <td>Rp105.000</td>
            <td><span class="status published">Published</span></td>
            <td>7 Nov 2024</td>
            <td class="action-buttons">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>


    </tbody>
</table>

<?php include '../views/footer.php'; ?>