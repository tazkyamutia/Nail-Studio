document.addEventListener('DOMContentLoaded', () => {
	const notificationBtn = document.getElementById('notification-btn');
	const notificationPanel = document.getElementById('notification-panel');
	const closeNotification = document.getElementById('close-notification');

	// Open the notification panel
	notificationBtn.addEventListener('click', () => {
		notificationPanel.classList.add('active');
	});

	// Close the notification panel
	closeNotification.addEventListener('click', () => {
		notificationPanel.classList.remove('active');
	});

	// Close when clicking outside the panel
	document.addEventListener('click', (event) => {
		if (
			!notificationPanel.contains(event.target) &&
			!notificationBtn.contains(event.target)
		) {
			notificationPanel.classList.remove('active');
		}
	});
});
// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})



if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})
