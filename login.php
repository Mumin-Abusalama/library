<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <?php
    session_start();
    //connecting the database
    $servername = "localhost";
    $username = "username";
    $password = "your_password";
    $dbname = "librarydb";
    // Create connection
    $conn = new mysqli($servername, $username, $password,$dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    // Check if the login form is submitted from the html part
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Query to check if the user exists in the database
        $sql = "SELECT * FROM userregisteration WHERE username = ?";
        //prepares the sql statement
        $stmt = $conn->prepare($sql);
        //makes sure the username is a string
        $stmt->bind_param("s", $username);
        //statement is executed
        $stmt->execute();
        //The result of the statement
        $result = $stmt->get_result();

        // Check if a user with that username exists in the database
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['username'] = $username;//Storing the username in the session
            // Verify that the password is right
            if ($password == $user['Password']) {
                // Set session variables if login is successful
                $_SESSION['username'] = $user['username'];
                header('Location: menu.php');//redirects the user to the library page after logging in successfully
                //displays a message along with the username of the user
                echo "Login successful! Welcome, " . htmlspecialchars($user['username']);
            } else {
                echo "Invalid password.";
            }
            //user is not found and needs to register
            } else {
            echo "User not found. Please register first.";
            }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
    ?>
<div class="wrapper">
    <h1>Welcome to the Library</h1>
<form method="POST" class="form">
    <h2>Login</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <i class='bx bxs-user'></i>
    <br>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <i class='bx bxs-lock'></i>
    <br>
    <br>
    <button type="submit"class="button">Login</button>
    <p>Don't have an account?<a href ="register.php">Register</a></p>
</form>
</div>
<footer>
<div class="footer-bottom">
        <p>&copy; 2024 Library</p>
      </div>
    </footer>
</body>
</html>