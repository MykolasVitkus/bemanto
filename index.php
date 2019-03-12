<?php session_start();
if (!isset($_SESSION['user']['id'])) {
    header("location:loginForm.html");
    exit;
}
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<div class="container text-center">
<p>
<p>Hello, <strong><?php echo $_SESSION['user']['username']; ?></strong> <p><a href="logout.php">Click here</a> to Logout.
   
</div> 