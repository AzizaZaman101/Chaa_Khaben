<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaa_khaben_db_1";



//$servername = "sql101.infinityfree.com";
//$username = "if0_38863563";
//$password = "TUA4Wvdgtkaav";
//$dbname = "if0_38863563_cha_khaben_db_101";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed : " . mysqli_connect_error());
}


