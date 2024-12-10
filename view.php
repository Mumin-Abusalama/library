<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h3>Your Reserved Books</h3>
<?php
session_start();
    $servername = "localhost";
    $username = "username";
    $password = "your_password";
    $dbname = "librarydb";

    $logged_user = $_SESSION['username'];

    // Create connection
$conn = new mysqli($servername,$username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
//selects data from the reserved table that the user reserved
$sql = "SELECT ISBN, username, reserved_date FROM reserved WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $logged_user);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

    echo "<form method='POST' class='form2' action='remove.php'>
    <p>
        ISBN: " . $row["ISBN"] . "<br>
        Username: " . $row["username"] . "<br>
        Date Reserved: " . $row["reserved_date"] . "<br>
        <input type='hidden' name='isbn' value='" . ($row['ISBN']) . "'>
        <button type='submit' name='remove_reservation' class='button'>Remove Reservation</button>
    <br>
    </p>
    </form>";
}
}
 else {
echo "<h3>0 results</h3>";
}

?>
<form action="menu.php">
        <button type="submit"class="button1">Back</button>
      </form>

<footer>
<div class="footer-bottom">
        <p>&copy; 2024 Library</p>
      </div>
    </footer>
</body>
</html>