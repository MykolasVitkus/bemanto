<?php

$mysqlHost = "localhost";
$mysqlUser = "root";
$mysqlPass = "";
$mysqlDb = "dd_homework";

$mysqlConnection = new mysqli($mysqlHost, $mysqlUser, $mysqlPass, $mysqlDb);

if($mysqlConnection->connect_error)
{
    die("Connection failed: " . $mysqlConnection->connect_error);
}