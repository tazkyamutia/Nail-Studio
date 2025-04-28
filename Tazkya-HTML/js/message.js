document.addEventListener("DOMContentLoaded", () => {
    const messagesContainer = document.getElementById("chat-messages");
    const messageInput = document.getElementById("message-input");
    const sendButton = document.getElementById("send-button");
    const toggleUserButton = document.getElementById("toggle-user");

    let currentUser = "sender"; // Default user is the sender

    // Toggle between Sender and Receiver
    toggleUserButton.addEventListener("click", () => {
        currentUser = currentUser === "sender" ? "receiver" : "sender";
        toggleUserButton.textContent = currentUser === "sender" ? "Switch to Receiver" : "Switch to Sender";
    });

    // Send button click event
    sendButton.addEventListener("click", () => {
        const message = messageInput.value.trim();
        if (message) {
            addMessage(currentUser, message);
            messageInput.value = "";
        }
    });

    // Add message to chat
    function addMessage(user, content) {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", user);
        messageDiv.textContent = content;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
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

