<?php

include '../basic_php/connection.php';

/*include('../html_files/user_sign_up_form.html');*/

if (isset($_POST["submit"])) {

    $user_id = uniqid();
    $FName = $_POST['FName'];
    $LName = $_POST['LName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = sha1($_POST['password']);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $user_type = $_POST['user_type'];


    $user_exists = $conn->prepare("SELECT * FROM user WHERE email= ?");

    $user_exists->execute([$email]);


    if ($user_exists->rowCount() > 0) {
        echo "user already exists";
    } else {
        if ($confirm_pass != $password) {
            echo "password doesn't match";
        } else {
            $insert_query = "INSERT INTO `user`(`user_id`, `email`, `Fname`, `Lname`, `phone`, `user_type`, `password`) VALUES ('[value-6]','$email','$FName','$LName','$phone','$user_type','$password')";

            $result = mysqli_query($conn, $insert_query);

            if ($result) {
                echo "User has been inserted successfully";
            } else {
                echo "failed";
            }
        }
    }
}
