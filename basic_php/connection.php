<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cha_khaben_db_101";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed : " . mysqli_connect_error());
}


