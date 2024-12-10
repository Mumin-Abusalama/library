<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="wrapper">
<?php 
    session_start();
    echo"<center>Welcome back,".$_SESSION['username']."<center>";//Welcome message
    ?>
<form action="search.php">
    <button type="submit" class="button1">Search/Reserve a Book</button>
    <br>
</form>
<form action="view.php">
<button type="submit" class="button1">View Reserved Books</button>
</form>
<br>
<form action="login.php">
<button type="submit" class="button">Logout</button>
</form>
</div>
<footer>
<div class="footer-bottom">
        <p>&copy; 2024 Library</p>
      </div>
    </footer>
</body>
</html>