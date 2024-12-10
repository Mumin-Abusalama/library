<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
$servername = "localhost";
$username = "username";
$password = "your_password";
$dbname = "librarydb";

$logged_user = $_SESSION['username'];

// Validate input to make sure book has an isbn
if (!isset($_POST['isbn']) || empty($_POST['isbn'])) {
    die("No ISBN provided.");
}
$isbn = $_POST['isbn'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Deletes the reservation from the database
$sql = "DELETE FROM reserved WHERE ISBN = ? AND username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $isbn, $logged_user);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    // Update the books table to set reserved back to N 
    $update_sql = "UPDATE books SET reserved = 'N' WHERE ISBN = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("s", $isbn);
    //reservation is successfully removeds
    if ($update_stmt->execute() && $update_stmt->affected_rows > 0) {
        echo "<h3>Reservation removed successfully</h3>";
    } 
    $update_stmt->close();
} else {
    echo "Failed to remove reservation.";
}

$stmt->close();
$conn->close();
?>

<form action="view.php">
        <button type="submit"class="button">Back</button>
      </form>
      <footer>
<div class="footer-bottom">
        <p>&copy; 2024 Library</p>
      </div>
    </footer>
