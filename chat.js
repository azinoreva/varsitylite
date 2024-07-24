document.addEventListener('DOMContentLoaded', function() {
    loadMessages();

    document.getElementById('sendMessageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    setInterval(loadMessages, 5000);  // Refresh messages every 5 seconds
});

function loadMessages() {
    $.ajax({
        url: 'chat.php',
        method: 'GET',
        success: function(data) {
            displayMessages(data);
        },
        error: function(error) {
            console.error('Error fetching messages:', error);
        }
    });
}

function displayMessages(messages) {
    const chatBox = document.getElementById('chatBox');
    chatBox.innerHTML = '';  // Clear previous messages

    messages.forEach(message => {
        const messageItem = document.createElement('div');
        messageItem.className = 'message-item';
        messageItem.innerHTML = `
            <strong>${message.username}</strong>: ${message.message} <small>(${message.timestamp})</small>
        `;
        chatBox.appendChild(messageItem);
    });
}

function sendMessage() {
    const username = document.getElementById('username').value;
    const message = document.getElementById('message').value;

    $.ajax({
        url: 'chat.php',
        method: 'POST',
        data: { username: username, message: message },
        success: function(data) {
            document.getElementById('message').value = '';  // Clear the message input
            loadMessages();  // Refresh messages
        },
        error: function(error) {
            console.error('Error sending message:', error);
        }
    });
}
