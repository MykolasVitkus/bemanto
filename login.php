<?php
    require 'User.php';

    $users = [ 
        new User('test@gmail.com', '$2y$10$WHeTi0gWTSyU5tO65LDL6uR8npzVMspXShIhQcL0d5yLWzhiT2CLq'), // labas
        new User('test1@gmail.com', '$2y$10$W9xwGSGEWZ4RlPfvvWkLfurR.40Ap8AzIU3mbN.OoXe5BwjKfI96m'), //slaptazodis
        new User('test2@gmail.com', '$2y$10$CfUSMigHY1le98IWQJ4lrO4IT1xpiDmKE7DYsgv6GCdaMGgg2BWtG'), // karantinas
        new User('test3@gmail.com', '$2y$10$I2WTtOuH5e77.YzqgQDzqut3HhyTFgcnbR/swXB.KpsJEkys8npnO'), //123456789
        new User('test4@gmail.com', '$2y$10$sxv5Ay4bh/o/1Gl1eqROIOzVHiZVRxJe1.Le0cmmgjz9nXajwVXLS') //123
    ];
    $Email = $_POST['email'];
    $Password = $_POST['password'];
    $errMsg = "We could not find user given by this email.";
    foreach($users as $user)
    {
        if($Email === $user->Email)
        {
            if(!password_verify($Password, $user->Password)) {
                $errMsg = "Incorrect password!";
                return;
            }
            session_start();
            $_SESSION['Email'] = $user->Email;
            header('Location: profile.php');
        }
    }
    
    echo $errMsg;
    header("refresh: 4, url=index.html");