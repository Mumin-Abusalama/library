<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    session_start();

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
    
    //categories fetched from database to avoid being hard coded
    $categories = [];
    $sql = "SELECT categoryDescr FROM category"; 
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['categoryDescr'];
        }
    }
    ?>
<div class="wrapper">
    <h2>Search by Title</h2>
    <form method="POST" class="form">
        <label for="BookTitle">Book Title:</label>
        <input type="varchar" id="BookTitle" name="BookTitle" required>
        <button type="submit"class="button">Search</button>
    </form>
    <h2>Search by Author</h2>
    
    <form method="POST" class="form">
        <label for="Author">Author:</label>
        <input type="varchar" id="Author" name="Author" required>
        <button type="submit"class="button">Search</button>
    </form>
    <h2>Search by Category</h2>
    
    <form method="POST" class="form">
        <label for="categoryDescr">Category:</label>
        <input list="categoryDescr" name ="categoryDescr" required>
        <datalist id="categoryDescr">
            <?php
            foreach ($categories as $category) {
                echo "<option value=\"$category\">";
            }
            ?>
        </datalist>
        <button type="submit"class="button">Search</button>
    </form>
    <br>
    <form action="menu.php">
        <button type="submit"class="button">Back</button>
      </form>
    </div>
      
</div>
<?php
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

//Search by Book Title
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$title = $Author = "";
//Check if it is empty and declared
if(isset($_POST["BookTitle"])){
$title = $_POST["BookTitle"];
// Query to check if the Book Title exists in the database
$sql = "SELECT * FROM books WHERE BookTitle LIKE ?";
//prepares the sql statement
$stmt = $conn->prepare($sql);
//This enables the partial search
$partialSearch = "%$title%";//matching the sequence of the book title
$stmt->bind_param("s", $partialSearch);
//statement is executed
$stmt->execute();
//The result of the statement
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo " <div class='book-list'>";
    while ($row = $result->fetch_assoc()) {
    echo "<div class='book-item'>
    <form method='POST'class='form1' action=''>
    <p>
        ISBN: " . $row["ISBN"] . "<br>
        Book Title: " . $row["BookTitle"] . "<br>
        Author: " . $row["Author"] . "<br>
        Category: " . $row["category"] . "<br>
        Reserved: " . $row["reserved"] . "<br>
        <input type='hidden' name='ISBN' value='" . $row["ISBN"] . "'>
        <input type='hidden' name='book_title' value='" . $row["BookTitle"] . "'>
        <button type='submit' name='reserve_button' class='button'" . $row["reserved"] . ">Reserve</button>
    </p>
    </form>
    </div>";

        } 
        echo"</div>";
    }
else {
    echo "<h3>0 results</h3>";
    }
    }
    //Search by Author
    //Check if it is empty and declared
if(isset($_POST["Author"])){
    $Author = $_POST["Author"];
    // Query to check if the Author exists in the database
    $sql = "SELECT * FROM books WHERE Author like  ?";
    //prepares the sql statement
    $stmt = $conn->prepare($sql);
    $partialSearch1 = "%$Author%";
    $stmt->bind_param("s", $partialSearch1);
    //statement is executed
    $stmt->execute();
    //The result of the statement
    $result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo " <div class='book-list'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='book-item'>
        <form method='POST' class ='form1'action=''>
        <p>
            ISBN: " . $row["ISBN"] . "<br>
            Book Title: " . $row["BookTitle"] . "<br>
            Author: " . $row["Author"] . "<br>
            Category: " . $row["category"] . "<br>
            Reserved: " . $row["reserved"]  . "<br>
            <input type='hidden' name='ISBN' value='" . $row["ISBN"] . "'>
            <input type='hidden' name='book_title' value='" . $row["BookTitle"] . "'>
            <button type='submit' name='reserve_button' class='button'" . $row["reserved"] . ">Reserve</button>
        </form>
        </div>";

        } 
        echo"</div>";
    }
    else {
    echo "<h3>0 results</h3>";
    }
}
}       

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = "";
    //Check if it is empty and declared
    if(isset($_POST["categoryDescr"])){
    $category = $_POST["categoryDescr"];
    // Query to join the books and category table using the category id key
    $sql = "SELECT * from books INNER JOIN 
    category on books.category = category.category
    WHERE category.categoryDescr = ?";
    //prepares the sql statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    //statement is executed
    $stmt->execute();
    //The result of the statement
    $result = $stmt->get_result();
    echo " <div class='book-list'>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            //outputs the available books 
            echo
            "<div class='book-item'>
            <form method='POST' class='form1' action=''>
            <p>
                 ISBN: " . $row["ISBN"] . "<br>
                    Book Title: " . $row["BookTitle"] . "<br>
                    Author: " . $row["Author"] . "<br>
                    Category: " . $row["category"] . "<br>
                    Reserved: " . $row["reserved"] . "<br>
                    <input type='hidden' name='ISBN' value='" . $row["ISBN"] . "'>
                    <input type='hidden' name='book_title' value='" . $row["BookTitle"] . "'>
                    <button type='submit' name='reserve_button' class='button'" . $row["reserved"]   . ">Reserve</button><br>
            <br>
            </p>
            </form>
            </div>";
        }
        echo"</div>";
    }
        
    else {
            echo "<h3>0 results<h3>";
        }
    }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reserve_button'])) {
        // Get the ISBN of the book to be reserved from the form
        $ISBN = $_POST['ISBN'];
        $username = $_SESSION['username']; // Get the logged-in user's username
        $reserve_date = date("Y-m-d H:i:s"); // Get the current date and time for reservation

        //This is to check if the book is already reserved
        $sql = "SELECT reserved FROM books WHERE ISBN = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $ISBN);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Book not found
            echo "<h3>Sorry, no book found with ISBN: $ISBN.<h3>";
        }
         else {
            $row = $result->fetch_assoc();

            if ($row['reserved'] == 'Y') {
                // The book is already reserved
                echo "<h3>Sorry, this book is already reserved.</h3>";
            } 
            else {
                // If the book is available
                // Update the book's reserved status in the books table
                $update_sql = "UPDATE books SET reserved = 'Y' WHERE ISBN = ?";//Y meaning its reserved
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param('s', $ISBN);
                $update_stmt->execute();

    // Insert the reservation into the reserved table
    $reserve_sql = "INSERT INTO reserved (ISBN, username, reserved_date) VALUES (?, ?, ?)";
    $reserve_stmt = $conn->prepare($reserve_sql);
    $reserve_stmt->bind_param('sss', $ISBN, $username, $reserve_date);
    $reserve_stmt->execute();

    // Check if the reservation was successful
    if ($reserve_stmt->affected_rows > 0) {
        echo "<h3>Book successfully reserved!</h3>";
    } 
    else {
        echo "<p>There was an issue reserving the book. Please try again.</p>";
    }
    }
    }
    }
    }
}
$conn->close();
?>
<footer>
<div class="footer-bottom">
        <p>&copy; 2024 Library</p>
      </div>
    </footer>
</body>
</html>


