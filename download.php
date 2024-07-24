<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "varsitylite"; // Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetchAll') {
    $sql = "SELECT id, title FROM library";
    $result = $conn->query($sql);

    $documents = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $documents[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($documents);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['download'])) {
    $id = $conn->real_escape_string($_GET['download']);
    $sql = "SELECT title, document FROM library WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $fileName = $row['title'] . ".pdf";
        $blob = $row['document'];

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($blob));
        echo $blob;
        exit;
    } else {
        http_response_code(404);
        echo "Document not found.";
    }
}

$conn->close();
?>