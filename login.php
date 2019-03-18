<?php

require "mysql.php";

session_start();

$alertType = "info";
$alertMessage = "You need to log in first to get access to home page.";
$setEmail = null;

if(isset($_POST['submit']))
{
    $alertType = "danger";
    $alertMessage = "Account with given email does not exist.";

    $setEmail = $_POST['email'];
    $setPassword = $_POST['password'];

    $query = "select * from users where email = '$setEmail'";
    $result = $mysqlConnection->query($query);

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            if(password_verify($setPassword, $row['password']))
            {
                $_SESSION['Id'] = $row['id'];
                $_SESSION['Email'] = $row['email'];
                header("Location: index.php");
            }   
            else
            {
                $alertMessage = "The given password was incorrect!";
                break;
            }
        }
    }
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
                <li class="navbar-item active">
                        <a class="nav-link" href="#">Login</a>
                    </li>
            </ul>
        </div>
    </nav>

    <div class="container col-sm-4 mt-5">
        <div class="alert alert-<?php echo $alertType; ?>">
            <?php echo $alertMessage; ?>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="emailInput">Email address</label>
                <input type="text" name="email" class="form-control" id="emailInput" placeholder="example@email" value="<?php echo $setEmail; ?>" required>
            </div>
            <div class="form-group">
                <label for="passInput">Password</label>
                <input type="password" name="password" class="form-control" id="passInput" placeholder="••••••••" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Log in</button>
        </form>
    </div>

</body>

</html>