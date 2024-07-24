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

// Helper function to generate Jitsi link
function generateJitsiLink($course_code) {
    return "https://meet.jit.si/lecture-OAU-University-" . $course_code;
}

// Handle different CRUD operations
$action = $_REQUEST['action'];

if ($action === 'create') {
    $course_code = $conn->real_escape_string($_POST['course_code']);
    $lecturer_name = $conn->real_escape_string($_POST['lecturer_name']);
    $class_dates = $conn->real_escape_string($_POST['class_dates']);
    $jitsi_link = generateJitsiLink($course_code);
    $notes = null;

    if (isset($_FILES['notes']) && $_FILES['notes']['error'] === UPLOAD_ERR_OK) {
        $notes = file_get_contents($_FILES['notes']['tmp_name']);
    }

    $sql = "INSERT INTO lectures (course_code, lecturer_name, notes, class_dates, jitsi_link) 
            VALUES ('$course_code', '$lecturer_name', ?, '$class_dates', '$jitsi_link')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("b", $notes);
    $stmt->send_long_data(0, $notes);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Lecture created successfully.";
    } else {
        echo "Error creating lecture: " . $conn->error;
    }
} elseif ($action === 'read') {
    $sql = "SELECT * FROM lectures";
    $result = $conn->query($sql);

    $lectures = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lectures[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($lectures);
} elseif ($action === 'update') {
    $id = $conn->real_escape_string($_POST['id']);
    $course_code = $conn->real_escape_string($_POST['course_code']);
    $lecturer_name = $conn->real_escape_string($_POST['lecturer_name']);
    $class_dates = $conn->real_escape_string($_POST['class_dates']);
    $notes = null;

    if (isset($_FILES['notes']) && $_FILES['notes']['error'] === UPLOAD_ERR_OK) {
        $notes = file_get_contents($_FILES['notes']['tmp_name']);
    }

    $sql = "UPDATE lectures SET course_code='$course_code', lecturer_name='$lecturer_name', class_dates='$class_dates'";
    if ($notes !== null) {
        $sql .= ", notes=?";
    }
    $sql .= " WHERE id='$id'";
    $stmt = $conn->prepare($sql);

    if ($notes !== null) {
        $stmt->bind_param("b", $notes);
        $stmt->send_long_data(0, $notes);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Lecture updated successfully.";
    } else {
        echo "Error updating lecture: " . $conn->error;
    }
} elseif ($action === 'delete') {
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "DELETE FROM lectures WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Lecture deleted successfully.";
    } else {
        echo "Error deleting lecture: " . $conn->error;
    }
}

$conn->close();
?>
