<?php

include('../php_files/connection.php');

if (isset($_POST['submit'])) {

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


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <form action="" method="post">

            <h1>
                <legend>Sign Up</legend>
            </h1>

            <br>
            <fieldset>
                <p>
                    <label for="FName">First name : </label>
                    <input type="text" name="FName" id="FName" placeholder="Enter Your First name" required autofocus autocomplete="off">
                </p>
            </fieldset>

            <br>

            <fieldset>
                <p>
                    <label for="LName">Last name : </label>
                    <input type="text" name="LName" id="LName" placeholder="Enter Your last name" required autofocus>
                </p>
            </fieldset>

            <br>

            <fieldset>
                <p>
                    <label for="email">Email Address : </label>
                    <input type="email" name="email" id="email" placeholder="Enter Your Email Address" required autofocus>
                </p>
            </fieldset>

            <br>

            <fieldset>
                <p>
                    <label for="phone">Contact No : </label>
                    <input type="tel" name="phone" id="phone" placeholder="0132-456-7892"
                        pattern="[0][1][0-9]{4}[0-9]{5}"
                        required autofocus>
                </p>
            </fieldset>


            <br>

            <fieldset>
                <p>
                    <label for="password">Password : </label>
                    <input type="password" name="password" id="password" placeholder="Enter Your Password" required autofocus>
                </p>
            </fieldset>

            <br>


            <fieldset>
                <p>
                    <label for="confirm_pass">Confirm Password : </label>
                    <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Enter Your Password Again" required autofocus>
                </p>
            </fieldset>

            <br>

            <fieldset>
                <p>
                    <label for="user_type">User Type : </label>

                    <input type="radio" name="user_type" id="customer" value="customer">
                    <label for="customer">Customer</label>


                    <input type="radio" name="user_type" id="rider" value="rider">
                    <label for="rider">Rider</label>
                </p>
            </fieldset>

            <br>
            <button type="submit" name="submit" value="register">Sign Up</button>



        </form>
    </div>
</body>

</html>