const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});

// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
	type: 'line',
	data: {
		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
		datasets: [{
			label: 'Sales 2024',
			data: [10000000, 16000000, 22000000, 25000000, 28000000, 30000000],
			borderColor: '#89193d',
			tension: 0.4,
			fill: true,
			backgroundColor: 'rgba(175, 91, 76, 0.1)'
		}]
	},
	options: {
		responsive: true,
		plugins: {
			legend: {
				position: 'top',
			}
		},
		scales: {
			y: {
				beginAtZero: true,
				ticks: {
					callback: function(value) {
						return 'Rp ' + value.toLocaleString('id-ID');
					}
				}
			}
		}
	}
});

// Visitor Chart
const visitorCtx = document.getElementById('visitorChart').getContext('2d');
new Chart(visitorCtx, {
	type: 'bar',
	data: {
		labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
		datasets: [{
			label: 'Visitors',
			data: [500, 400, 690, 890, 750, 630, 520],
			backgroundColor: '#89193d',
			borderRadius: 5
		}]
	},
	options: {
		responsive: true,
		plugins: {
			legend: {
				position: 'top',
			}
		}
	}
});

// Product Performance Chart
const productCtx = document.getElementById('productChart').getContext('2d');
new Chart(productCtx, {
	type: 'doughnut',
	data: {
		labels: ['Pink Perfections', 'Green Elegant', 'Tropical Bloom', 'Elegant Ruby'],
		datasets: [{
			data: [50, 35, 20, 15],
			backgroundColor: [
				'#89193d',
				'#AE445A',
				'#AF1740',
				'#D76C82'
			]
		}]
	},
	options: {
		responsive: true,
		plugins: {
			legend: {
				position: 'right',
			}
		}
	}
});

// Order Status Chart
const orderCtx = document.getElementById('orderChart').getContext('2d');
new Chart(orderCtx, {
	type: 'pie',
	data: {
		labels: ['Completed', 'Pending', 'Processing', 'Cancelled'],
		datasets: [{
			data: [45, 25, 20, 10],
			backgroundColor: [
				'#5CB338',  // success
				'#ECE852',  // warning
				'#3C91E6',  // primary
				'#FF5630'   // danger
			]
		}]
	},
	options: {
		responsive: true,
		plugins: {
			legend: {
				position: 'right',
			}
		}
	}
});


const salesData = {
	labels: ['January', 'February', 'March', 'April', 'May', 'June'],
	datasets: [{
		label: 'Sales',
		data: [500, 700, 300, 400, 650, 800],
		borderColor: 'rgb(75, 192, 192)',
		backgroundColor: 'rgba(75, 192, 192, 0.2)',
		tension: 0.1
	}]
};

// Data for Visitor Statistics Chart
const visitorData = {
	labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
	datasets: [{
		label: 'Visitors',
		data: [1200, 1500, 900, 1400, 1600, 1800, 2000],
		borderColor: 'rgb(153, 102, 255)',
		backgroundColor: 'rgba(153, 102, 255, 0.2)',
		tension: 0.1
	}]
};

// Data for Product Performance Chart
const productData = {
	labels: ['Product A', 'Product B', 'Product C', 'Product D'],
	datasets: [{
		label: 'Performance',
		data: [80, 95, 60, 75],
		borderColor: 'rgb(255, 159, 64)',
		backgroundColor: 'rgba(255, 159, 64, 0.2)',
		tension: 0.1
	}]
};

// Data for Order Status Chart
const orderData = {
	labels: ['Pending', 'Shipped', 'Delivered', 'Returned'],
	datasets: [{
		label: 'Order Status',
		data: [200, 150, 500, 50],
		borderColor: 'rgb(54, 162, 235)',
		backgroundColor: 'rgba(54, 162, 235, 0.2)',
		tension: 0.1
	}]
};

// Create the Sales Analytics chart
const salesChart = new Chart(document.getElementById('salesChart'), {
	type: 'line',
	data: salesData,
	options: {
		responsive: true,
		scales: {
			x: { title: { display: true, text: 'Months' } },
			y: { title: { display: true, text: 'Sales' } }
		}
	}
});

// Create the Visitor Statistics chart
const visitorChart = new Chart(document.getElementById('visitorChart'), {
	type: 'bar',
	data: visitorData,
	options: {
		responsive: true,
		scales: {
			x: { title: { display: true, text: 'Days of the Week' } },
			y: { title: { display: true, text: 'Visitors' } }
		}
	}
});

// Create the Product Performance chart
const productChart = new Chart(document.getElementById('productChart'), {
	type: 'radar',
	data: productData,
	options: {
		responsive: true,
		scales: {
			r: { min: 0, max: 100, ticks: { stepSize: 20 } }
		}
	}
});

// Create the Order Status chart
const orderChart = new Chart(document.getElementById('orderChart'), {
	type: 'pie',
	data: orderData,
	options: {
		responsive: true,
		plugins: {
			legend: { position: 'top' }
		}
	}
});

const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const sendButton = document.getElementById('sendButton');

function appendMessage(content, sender) {
    const message = document.createElement('div');
    message.classList.add('message', sender);

    const messageContent = document.createElement('div');
    messageContent.classList.add('content');
    messageContent.textContent = content;

    message.appendChild(messageContent);
    chatMessages.appendChild(message);

    chatMessages.scrollTop = chatMessages.scrollHeight;
}

sendButton.addEventListener('click', () => {
    const message = chatInput.value.trim();
    if (message) {
        appendMessage(message, 'user');

        // Simulate admin response
        setTimeout(() => {
            appendMessage('Admin response to: ' + message, 'admin');
        }, 1000);

        chatInput.value = '';
    }
});

chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendButton.click();
    }
});
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  
  // Close the dropdown menu if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
  
// Fungsi untuk menyortir tabel
function sortTable(columnIndex) {
	const table = document.getElementById("productTable");
	const rows = Array.from(table.rows).slice(1);
	const isAscending = table.rows[0].cells[columnIndex].getAttribute("data-order") !== "asc";
	table.rows[0].cells[columnIndex].setAttribute("data-order", isAscending ? "asc" : "desc");
  
	rows.sort((a, b) => {
	  const aText = a.cells[columnIndex].innerText;
	  const bText = b.cells[columnIndex].innerText;
	  return isAscending
		? aText.localeCompare(bText, undefined, { numeric: true })
		: bText.localeCompare(aText, undefined, { numeric: true });
	});
  
	rows.forEach(row => table.tBodies[0].appendChild(row));
  }
  
  // Fungsi untuk mencari produk
  document.getElementById("searchInput").addEventListener("input", function() {
	const filter = this.value.toLowerCase();
	const rows = document.querySelectorAll("#productTable tbody tr");
	rows.forEach(row => {
	  const productName = row.cells[2].innerText.toLowerCase();
	  row.style.display = productName.includes(filter) ? "" : "none";
	});
  });
  

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu'); // Tombol menu bar
const sidebar = document.getElementById('sidebar'); // Sidebar

// Tambahkan event listener untuk toggle sidebar
menuBar.addEventListener('click', function () {
    // Toggle kelas 'hide' pada sidebar
    sidebar.classList.toggle('hide');
    console.log('Sidebar toggled'); // Debugging log
});

// Menyesuaikan tampilan awal berdasarkan lebar layar
if (window.innerWidth < 768) {
    sidebar.classList.add('hide');
} else {
    sidebar.classList.remove('hide');
}

// Event listener untuk menyesuaikan tampilan sidebar ketika ukuran layar berubah
window.addEventListener('resize', function () {
    if (this.innerWidth > 768) {
        sidebar.classList.remove('hide'); // Tampilkan sidebar jika layar lebih besar dari 768px
    } else {
        sidebar.classList.add('hide'); // Sembunyikan sidebar jika layar lebih kecil dari 768px
    }
});
