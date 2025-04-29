<?php

include('../php_files/connection.php');

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
                <legend>Sign In</legend>
            </h1>
            <br>

            <fieldset>
                <p>
                    <label for="user_type">User Type : </label>

                    <input type="radio" name="user_type" id="customer" value="customer">
                    <label for="customer">Customer</label>

                    <input type="radio" name="user_type" id="admin" value="admin">
                    <label for="admin">Admin</label>

                    <input type="radio" name="user_type" id="rider" value="rider">
                    <label for="rider">Rider</label>
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
                    <label for="password">Password : </label>
                    <input type="password" name="password" id="password" placeholder="Enter Your Password" required autofocus>
                </p>
            </fieldset>

            <br>
            <button type="submit">Sign In</button>
</body>

</html>