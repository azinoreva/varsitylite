<?php
	$title = $_POST['title'];
	$author = $_POST['author'];
	$date_of_publication = $_POST['date_of_publication'];
	$uploaded_by = $_POST['uploaded_by'];
	$document = null;

	// Check if file was uploaded
	if(isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
		// Read the file
		$document = file_get_contents($_FILES['document']['tmp_name']);
	}

	// Database connection
	$conn = new mysqli('localhost', 'root', '', 'varsitylite');

	if($conn->connect_error) {
		echo "$conn->connect_error";
		die("Connection Failed: " . $conn->connect_error);
	} else {
		$stmt = $conn->prepare("INSERT INTO library (Title, Author, Date_of_Publication, Document, Uploaded_by) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("ssbss", $title, $author, $date_of_publication, $document, $uploaded_by);

		// Bind BLOB field separately
		$stmt->send_long_data(3, $document);

		$execval = $stmt->execute();
		echo $execval ? "Uploaded successfully..." : "Upload failed: " . $stmt->error;

		$stmt->close();
		$conn->close();
	}
?>