<?php

    class User
    {
        public $userEmail;
        public $userPassword;

        public function __construct($userEmail, $userPassword)
        {
            $this->userEmail = $userEmail;
            $this->userPassword = $userPassword;
        }
    }

    $errMsg = "User not found :(";

    $users = [
        new User('antanas@e.mail', '$2y$10$7pfJZfCXkbvml6IncCLgRenrLzlgU.O2LMmdu/eDQ2lCmNzAXqPd6'),
        new User('klikas@e.mail', '$2y$10$IzMIavmxa2CKMCSg6a4PQ.WJX1OasfFKftBUmWatmAN03uexXykKK'),
        new User('mikas@e.mail', '$2y$10$/dnwoKkQE76CMWu71lzNa.vw/.WZ18grNwOeGofU179EyznYlmuMG'),
        new User('tomas@e.mail', '$2y$10$qVy7Yv6I8PjqdKbX.hXFgeCwpHqQrfRkfuR0aUcB2J7qMCph45zha'),
        new User('renas@e.mail', '$2y$10$nWAD0QJyAOtVGPqL9onn8uR2boFcV64KE.iMy3FwlDQcRIVxek2h.'),
        new User('ridas@e.mail', '$2y$10$/xuYhd8pJzhJBN3iEsKQGO8kJKw1WBlLEgBTC7uSxElC7.3SbmNa6')
    ];

    $setEmail = $_POST['email'];
    $setPassword = $_POST['password'];

    foreach($users as $user)
    {
        if($setEmail === $user->userEmail)
        {
            if(password_verify($setPassword, $user->userPassword))
            {
                session_start();
                $_SESSION['Email'] = $user->userEmail;
                header('Location: profile.php');
            }
            else
            {
                $errMsg = "Incorrect password!";
            }
        }
    }

    echo $errMsg;
    header("refresh: 4, url=index.html");
