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
							<a class="active" href="#">Messages</a>
						</li>
					</ul>
				</div>
				
			</div>

            <div class="chat-container">
				<div class="chat-header">
					<h2>Messages</h2>
				</div>
				<div class="toggle">
					<span id="toggle-user">Switch to Receiver</span>
				</div>
				<div class="chat-messages" id="chat-messages"></div>
				<div class="chat-input">
					<input type="text" id="message-input" placeholder="Type your message here...">
					<button id="send-button">Send</button>
				</div>
			</div>
        
    </section>
	
<?php include '../views/footer.php'; ?>
	