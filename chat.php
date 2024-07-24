<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "varsitylite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all messages
    $sql = "SELECT username, message, timestamp FROM messages ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    $messages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new message
    $username = $conn->real_escape_string($_POST['username']);
    $message = $conn->real_escape_string($_POST['message']);
    $sql = "INSERT INTO messages (username, message) VALUES ('$username', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo "Message sent.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
