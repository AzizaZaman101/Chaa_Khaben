<?php 

include '../basic_php/connection.php';

session_start();
session_unset();
session_destroy();

// Also remove session cookie (optional but recommended)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

<<<<<<< HEAD
header("refresh:2;url= ../regular/index.php");
=======
header("Location: ../regular/index.php");
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
exit();
?>