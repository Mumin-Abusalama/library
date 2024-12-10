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

// Database connection parameters
$servername = "localhost";
$username = "username";
$password = "your_password";
$dbname = "librarydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $author = $category = "";
$query = ""; // SQL query will be built based on input

// Search by Book Title
if (isset($_POST['BookTitle']) && $_POST['BookTitle'] != "") {
    $title = $_POST['BookTitle'];
    $query = "SELECT * FROM books WHERE BookTitle LIKE ?";
}

// Search by Author
if (isset($_POST['Author']) && $_POST['Author'] != "") {
    $author = $_POST['Author'];
    $query = "SELECT * FROM books WHERE Author LIKE ?";
}

// Search by Category
if (isset($_POST['categoryDescr']) && $_POST['categoryDescr'] != "") {
    $category = $_POST['categoryDescr'];
    $query = "SELECT * FROM books WHERE category = ?";
}

// Only execute if there is a valid search query
if ($query != "") {
    // Prepare the query
    $stmt = $conn->prepare($query);

    // Bind parameters dynamically based on search type
    if ($title != "") {
        $search_term = "%" . $title . "%";
        $stmt->bind_param("s", $search_term);
    } elseif ($author != "") {
        $search_term = "%" . $author . "%";
        $stmt->bind_param("s", $search_term);
    } elseif ($category != "") {
        $stmt->bind_param("s", $category);
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Search Results</h2>";
        echo "<table border='1'><tr><th>ISBN</th><th>Book Title</th><th>Author</th><th>Category</th><th>Reserved</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['ISBN'] . "</td>
                    <td>" . $row['BookTitle'] . "</td>
                    <td>" . $row['Author'] . "</td>
                    <td>" . $row['category'] . "</td>
                    <td>" . ($row['reserved'] == 'Y' ? 'Yes' : 'No') . "</td>
                    <td>
                        <form method='POST' action=''>
                            <input type='hidden' name='ISBN' value='" . $row['ISBN'] . "'>
                            <button type='submit' name='reserve_button' class='button'" . ($row['reserved'] == 'Y' ? ' disabled' : '') . ">Reserve</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found.</p>";
    }
} else {
    echo "<p>Please enter a search query.</p>";
}

// Handle reservation logic
if (isset($_POST['reserve_button'])) {
    $ISBN = $_POST['ISBN'];
    $username = $_SESSION['username']; // Assume user is logged in and username is stored in session
    $reserve_date = date("Y-m-d H:i:s");

    // Check if the book is already reserved
    $sql = "SELECT reserved FROM books WHERE ISBN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $ISBN);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['reserved'] == 'Y') {
        echo "<p>This book is already reserved.</p>";
    } else {
        // Mark the book as reserved
        $update_sql = "UPDATE books SET reserved = 'Y' WHERE ISBN = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('s', $ISBN);
        $update_stmt->execute();

        // Insert into reserved table
        $reserve_sql = "INSERT INTO reserved (ISBN, username, reserved_date) VALUES (?, ?, ?)";
        $reserve_stmt = $conn->prepare($reserve_sql);
        $reserve_stmt->bind_param('sss', $ISBN, $username, $reserve_date);
        $reserve_stmt->execute();

        echo "<p>Book reserved successfully!</p>";
    }
}

$conn->close();
?>


<div class="wrapper">
<form action="search.php">
        <button type="submit"class="button">Back</button>
      </form>
      <footer>
<div class="footer-bottom">
        <p>&copy; 2024 Library</p>
      </div>
    </footer>
</div>