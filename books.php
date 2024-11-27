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
    echo"<center>Welcome back,".$_SESSION['username']."<center>";
    ?>
<div class="wrapper">
<form action="search.php">
    <button type="submit" class="button1">Search/Reserve a Book</button>
    <br><br>
</form>
<form action="view.php">
<button type="submit" class="button1">View Reserved Books</button>
</form>
</div>
<form action="login.php">
<button type="submit" class="button">Logout</button>
</form>
</body>
</html>