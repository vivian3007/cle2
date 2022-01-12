<?php
/* connect to the database */
$host       = "localhost";
$database   = "yashima";
$user       = "root";
$password   = "";

$db = mysqli_connect($host, $user, $password, $database)
or die("Error: " . mysqli_connect_error());
?>