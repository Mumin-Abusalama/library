<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    // Create connection
$conn = new mysqli($servername,$username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT ISBN, username, reserved_date FROM reserved WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $logged_user);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

    echo "<form method='POST' class='form1' action=''>
    <p>
        ISBN: " . $row["ISBN"] . "<br>
        Username: " . $row["username"] . "<br>
        Date Reserved: " . $row["reserved_date"] . "<br>   
    <br>
    </p>
    </form>";
}
}
 else {
echo "0 results";
}

?>
<form action="books.php">
        <button type="submit"class="button">Back</button>
      </form>


</body>
</html>