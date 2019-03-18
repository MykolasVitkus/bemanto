<?php

session_start();

if(!isset($_SESSION['Email']))
{
    header("Location: login.php");
}

require "mysql.php";

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
                <li class="navbar-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-5">
                <li class="navbar-item">
                    <a class="nav-link" href="create_event.php">Create event</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto mr-5">
                <li class="navbar-item">
                    <a class="nav-link" href="signout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="jumbotron text-center">
        <h1>Welcome, <?php echo $_SESSION['Email'] ?></h1>
    </div>

    <div class="container col-sm-10">
        <div class="row mb-5">
            <div class="col text-center">
                <button class="btn btn-primary" onclick="location.href='create_event.php'" type="button">Create new event</button>
            </div>
        </div>

        <?php

            $userId = $_SESSION['Id'];
            $query =    "SELECT events.title, events.description, events.date, users.email
                        FROM users 
                        INNER JOIN events ON (users.id = events.fk_userId)
                        WHERE users.id = '$userId'
                        ORDER BY events.date ASC";

            $result = $mysqlConnection->query($query);
            if($result->num_rows > 0)
            {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    echo '
                    <div class="row border rounded-lg mb-3">
                        <div class="col py-2">
                            <strong><big>' . $row['title'] . '</big></strong><br>
                            Author: <i>'. $row['email'] .'</i><br>
                            <small><i>' . $row['date'] . '</i></small><br>
                            <hr>
                            ' . $row['description'] . '
                        </div>
                    </div>
                    ';
                }
            }
            else
            {
        ?>
                <div class="row">
                    <div class="col text-center">
                        <strong>No events found. Create one now!</strong>
                    </div>
                </div>
        <?php
            }

        ?>

    </div>

</body>

</html> 

<?php

$mysqlConnection->close();