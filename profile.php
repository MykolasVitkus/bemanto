<?php

    session_start();

    if(!isset($_SESSION['Email']))
    {
        header('Location: index.html');
    }

    echo "Hello, " . $_SESSION['Email'] . "!";