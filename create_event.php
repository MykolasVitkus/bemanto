<?php

require "mysql.php";

session_start();

if(!isset($_SESSION['Email']))
{
    header("Location: index.php");
}

if(isset($_POST['submit']))
{
    $title = mysqli_real_escape_string($mysqlConnection, $_POST['title']);
    $desc = mysqli_real_escape_string($mysqlConnection, $_POST['desc']);
    $date = $_POST['date'];
    $userId = $_SESSION['Id'];

    echo $desc;

    $query = "insert into events (title, description, date, fk_userId) values ('$title', '$desc', '$date', '$userId')";
    $mysqlConnection->query($query);

    header("Location: index.php");
}

$mysqlConnection->close();

?>

<html>

<head>

    <meta charset="UTF-8">
    <title>Homework | Martynas Kasparavičius</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

</head>
<body>

    <!-- Navigation bar -->

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark static-top">
        <div class="container">
            <ul class="navbar-nav ml-5">
                <li class="navbar-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-5">
                <li class="navbar-item active">
                    <a class="nav-link" href="#">Create event</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto mr-5">
                <li class="navbar-item">
                    <a class="nav-link" href="signout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container col-sm-4 mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="eventTitle">Event title</label>
                <input type="text" name="title" class="form-control" id="eventTitle" placeholder="e.g. Run with doggies" maxlength="255" required>
            </div>
            <div class="form-group">
                <label for="eventDesc">Event description</label>
                <textarea type="password" name="desc" class="form-control" id="eventDesc" placeholder="e.g. Run with good boys for the charity purpose" maxlength="3000" required></textarea>
            </div>
            <div class="form-group">
                <label for="eventDate">Event date</label>
                <input type="date" name="date" class="form-control" id="eventDate" min="<?php echo date("Y-m-d"); ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create event</button>
        </form>
    </div>

</body>

</html>