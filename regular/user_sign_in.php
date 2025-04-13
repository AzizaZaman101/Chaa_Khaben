<?php
include '../basic_php/connection.php';

include('../html_files/user_sign_in_form.html');

if (isset($_POST["Sub"])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $insert_query = "INSERT INTO `user`(`user_id`, `email`, `Fname`, `Lname`, `phone`, `user_type`, `password`) VALUES ('[value-6]','$email','$FName','$LName','$phone','$user_type','$password')";

    $result = mysqli_query($conn, $insert_query);

    if ($result) {
        echo "User has been inserted successfully";
    } else {
        echo "failed";
    }
}
