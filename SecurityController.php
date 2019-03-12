<?php 

class User
{

    private $id;

    private $username;

    private $password;

    public function __construct($id, $username, $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getPassword()
    {
        return $this->password;
    }
}

session_start();
$users = [
    new User(1, "admin", "password"),
    new User(2, "admin2", "password123")
];
if (isset($_POST['Submit'])) {

    $Username = isset($_POST['user']) ? $_POST['user'] : '';
    $Password = isset($_POST['password']) ? $_POST['password'] : '';

    foreach ($users as $user) {
            if (($user->getUsername() === $Username) && password_verify($Password, $user->getPassword())) {
                    $_SESSION['user']['id'] = $user->getId();
                    $_SESSION['user']['username'] = $user->getUsername();
                    header("location:index.php");
                    exit;
                } else {
                    header("location:loginForm.html");
                }
        }
}

