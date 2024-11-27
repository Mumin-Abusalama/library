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
    $servername = "localhost";
    $username = "username";
    $password = "your_password";
    $dbname = "librarydb";

    // Create connection
$conn = new mysqli($servername,$username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $first_name = $_POST['Fname'];
        $last_name = $_POST['Lname'];
        $mobile = $_POST['Number'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("INSERT INTO UserRegisteration (Username, FirstName, LastName, MobileNumber, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username,$first_name, $last_name, $mobile, $password);

        if ($stmt->execute()) {
            echo "New user registered successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

    $stmt->close();
    }
    $conn->close();
    ?>
 <div class="wrapper">
        <h1>Welcome to the Library</h1>
    <form method="POST">
        <h2>Register</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <br>
        <label for="Fname">First Name:</label>
        <input type="text" id="Fname" name="Fname" required>
        <br>
        <br>
        <label for="Lname">Last Name:</label>
        <input type="text" id="Lname" name="Lname" required>
        <br>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <br>
        <label for="Number">Mobile number:</label>
        <input type="number" id="mobile" name="Number" maxlength = "10" max = "9999999999" required>
        <br>
        <br>
        <button type="submit"class="button">Register</button>
        <p>Already have an account?<a href ="login.php">Login</a></p>
    </form>
    </div>


</body>
</html>