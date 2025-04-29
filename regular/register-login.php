<?php
session_start();
include '../basic_php/connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {

$fName = $_POST['fname'];
$lName = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_pass = $_POST['confirm_pass'];
$user_type = $_POST['user_type'];


$user_exists = $conn->prepare("SELECT * FROM user WHERE email= ?");
$user_exists->bind_param("s", $email); // Bind parameters to avoid SQL injection
$user_exists->execute();

// Get the result
$result = $user_exists->get_result();

if ($result->num_rows > 0) {
    echo "User already exists";
} 
else {
    // Check if passwords match
    if ($confirm_pass != $password) {
        echo "Passwords don't match";
    } 
    else {
        // Hash the password for secure storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the insert query for the user
        $insert_query = "INSERT INTO `user`(email, fname, lname, phone, user_type, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssss", $email, $fName, $lName, $phone, $user_type, $hashed_password);
        $stmt->execute();

        // Get the user_id for further operations
        $user_id = $conn->insert_id;

        // Check if the user is a customer and insert into the customer table
        if ($user_type == 'customer') {
            $insert_customer_query = "INSERT INTO customer (user_id) VALUES (?)";
            $customer_stmt = $conn->prepare($insert_customer_query);
            $customer_stmt->bind_param("i", $user_id);
            $customer_stmt->execute();

            if ($customer_stmt) {
                   
                    $customerQuery = "SELECT customer_id FROM customer WHERE user_id = ?";
                    $stmt = $conn->prepare($customerQuery);
                    $stmt->bind_param("i", $user_id); 
                    $stmt->execute();
                    $customerResult = $stmt->get_result();

                
                        $customer = $customerResult->fetch_assoc();
                        $_SESSION['customer_id'] = $customer['customer_id']; 

                        echo "<script>
                    alert('User has been registered as a Customer.');
                    window.location.href = '../customer/index-customer.php';
                        </script>";
                    exit();
            } 
            else {
                echo "Failed to insert into the customer table.";
            }
        }
        
        elseif ($user_type == 'rider') {
            $insert_rider_query = "INSERT INTO rider (user_id, rider_active_status, pending_delivery) VALUES (?, 'active', 0)";
            $rider_stmt = $conn->prepare($insert_rider_query);
            $rider_stmt->bind_param("i", $user_id);
            $rider_stmt->execute();
        
            if ($rider_stmt) {
                $riderQuery = "SELECT rider_id FROM rider WHERE user_id = ?";
                    $stmt = $conn->prepare($riderQuery);
                    $stmt->bind_param("i", $user_id); 
                    $stmt->execute();
                    $riderResult = $stmt->get_result();

                
                        $rider = $riderResult->fetch_assoc();
                        $_SESSION['rider_id'] = $rider['rider_id']; 

                        echo "<script>
                    alert('User has been registered as a rider.');
                    window.location.href = '../rider/index-rider.php';
                        </script>";
                    exit();
            } else {
                echo "Failed to insert into the rider table.";
            }
        } 
        
        elseif ($user_type == 'admin') {
            $insert_admin_query = "INSERT INTO admin (user_id) VALUES (?)";
            $admin_stmt = $conn->prepare($insert_admin_query);
            $admin_stmt->bind_param("i", $user_id);
            $admin_stmt->execute();
        
            if ($admin_stmt) {
                $adminQuery = "SELECT admin_id FROM admin WHERE user_id = ?";
                    $stmt = $conn->prepare($adminQuery);
                    $stmt->bind_param("i", $user_id); 
                    $stmt->execute();
                    $adminResult = $stmt->get_result();

                
                        $admin = $adminResult->fetch_assoc();
                        $_SESSION['admin_id'] = $admin['admin_id']; 

                        echo "<script>
                    alert('User has been registered as an Admin.');
                    window.location.href = '../admin/index-admin.php';
                        </script>";
                    exit();
            } else {
                echo "Failed to insert into the admin table.";
            }
        } 
        
        else {
            echo "User has been registered successfully!";
        }
    }
}
}
?>



<!--login-->
<?php

include '../basic_php/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

//$invalid_email="Invalid Email Address!";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];



    // Prepare the statement to fetch user details
    $query = "SELECT user_id, email, password, user_type FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();


        // Verify the hashed password
        if (($password === $user['password']) ||  password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];


        //generate otp and send it in email
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            // Insert OTP into double_verification table
$insert_otp_query = "INSERT INTO double_verification (user_id, otp) VALUES (?, ?)";
$stmt = $conn->prepare($insert_otp_query);
$stmt->bind_param("is", $_SESSION['user_id'], $otp);
$stmt->execute();
        
            $Mail = new PHPMailer(true);

try {
    // Server settings
    $Mail->isSMTP();                                      
    $Mail->Host       = 'smtp.gmail.com';                 
    $Mail->SMTPAuth   = true;                             
    $Mail->Username   = 'zamanaziza5@gmail.com';            
    $Mail->Password   = 'tggjlujxdguxiusy';        
    $Mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   
    $Mail->Port       = 587;                              

    // Recipients
    $Mail->setFrom('zamanaziza5@gmail.com', 'Aziza Zaman');
    $Mail->addAddress($email);

    $Mail->isHTML(true);
    $Mail->Subject = 'Your OTP Code';
    $Mail->Body    = "Your OTP code is: <b>{$otp}</b>";

    $Mail->send();
    header('Location: ./otp_verification.php');
    exit();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$Mail->ErrorInfo}";
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
    <title>Register</title>
    <link rel="stylesheet" href="../style/register_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head> 

<body>

<?php include './header.php';?>

<div class="form_container">
<!-- Form Box  -->    

    <div class="form-box">

        <!--Set New Password Form  -->
    
        <div class="set-new-password-container" id="set_new_password">
            <div class="top">
                <p class="heading_form">Set New Password</p>
            </div>

            <div class="input-box">
                <input type="password" class="input-field" name="" id="" placeholder="Enter Password">
                <i class="fa-solid fa-lock"></i>
                <a href="#" class="toggle_password" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></a>
            </div>

            <div class="input-box">
                <input type="password" class="input-field" name="" id="" placeholder="Confirm Password">
                <i class="fa-solid fa-lock"></i>
                <a href="#" class="toggle_password" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></a>
            </div>

            <div class="input-box">
                <input type="submit" class="submit" name="" id="" value="Confirm">
            </div>

            <div class="two-col">
                <div class="one">
                    <label for="set-new-password-check"> </label>
                </div>
                <div class="two">
                    <label for="">
                        <a href="#" onclick="login()">Go Back</a>
                    </label>
                </div>
            </div>
        </div>


        <!--Write The Code Sent to Email Form  -->
    
        <div class="write-code-container" id="write_code">
            <div class="top">
                <span>Didn't get a code?<a href="#" onclick="forgot_password()">Check</a></span>
                <p class="heading_form">Write Code</p>
            </div>

            <div class="input-box">
                <input type="password" class="input-field" name="" id="" placeholder="Enter Code">
                <i class="fa-solid fa-key"></i>
                <a href="#" class="toggle_password" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></a>
            </div>

            <div class="input-box">
                <input type="submit" class="submit" name="" id="" value="Done" onclick="set_new_password()">
            </div>

            <div class="two-col">
                <div class="one">
                    <label for="code-check">Enter code to reset password</label>
                </div>
                <div class="two">
                    <label for="">
                        <a href="#" onclick="login()">Go Back</a>
                    </label>
                </div>
            </div>
        </div>


        <!--Forgot Password Form  -->
    
        <div class="forgot-password-container" id="forgot_password">
            <div class="top">
                <span>Want to login?<a href="#" onclick="login()">Login
                </a></span>
                <p class="heading_form">Forgot Password</p>
            </div>

            <div class="input-box">
                <input type="email" class="input-field" name="" id="" placeholder="Enter Email">
                <i class="fa-regular fa-user"></i>
            </div>

            <div class="input-box">
                <input type="submit" class="submit" name="" id="" value="Send Code" onclick="write_code()">
            </div>

            <div class="two-col">
                <div class="one">
                    <label for="forgot-pass-check">A code will be sent to your email id</label>
                </div>
            </div>
        </div>





        <!--Login Form  -->
    
        <div class="login-container" id="login" action="./register-login.php" method="POST">
                    <div class="top">
                        <span>Don't have an account?<a href="#" onclick="register()">Sign Up
                        </a></span>
                        <p class="heading_form">Login</p>
                    </div>
        <form method="POST" action="">


                    <div class="input-box">
                        <input type="email" class="input-field" name="email" id="email" placeholder="Enter Email" required>
                        <i class="fa-regular fa-user"></i>
                    </div>
        
                    <div class="input-box">
                        
                        <input type="password" class="input-field" name="password" id="password" placeholder="Enter Password" required>
                        <i class="fa-solid fa-lock"></i>
                        <a href="#" class="toggle_password" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></a>
                    </div>
        
                    <div class="input-box">
                        <input type="submit" class="submit" name="login" id="login" value="Sign In">
                    </div>
            </form>
                    <div class="two-col">
                        <div class="one">
                            <input type="checkbox" name="" id="login-check">
                            <label for="login-check">Remember Me</label>
                        </div>
        
                        <div class="two">
                            <label for="">
                                <a href="#" onclick="forgot_password()">Forgot Password?</a>
                            </label>
                        </div>
                    </div>
        </div>






        <!--Registration Form  -->

            <div class="register-container" id="register">

            
                <div class="top">
                    <span>Have an account?<a href="#" onclick="login()">Login
                    </a></span>
                    <p class="heading_form">Sign Up</p>
                </div>
            <form method="POST" action="">
                <div class="two-forms">
                    <div class="input-box">
                        <input type="text" class="registration-input-field" name="fname" id="fname" placeholder="First name" required autocomplete="off">
                        <i class="fa-regular fa-user"></i>
                    </div>

                    <div class="input-box">
                        <input type="text" class="registration-input-field" name="lname" id="lname" placeholder="Last name" required autocomplete="off">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>

                <div class="input-box">
                    <input type="email" class="registration-input-field" name="email" id="email" placeholder="Email Address" required autocomplete="off">
                    <i class="fa-regular fa-envelope"></i>
                </div>

                <div class="input-box">
                    <input type="tel" class="registration-input-field" name="phone" id="phone" placeholder="0132-456-7892"
                        pattern="[0][1][0-9]{4}[0-9]{5}"
                        required autocomplete="off">
                    <i class="fa-solid fa-phone"></i>
                </div>

                <div class="two-forms">
                    <div class="input-box">
                        <input type="password" class="registration-input-field" name="password" id="password" placeholder="Password" required>
                        <i class="fa-solid fa-lock"></i>
                        <a href="#" class="toggle_password" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></a>
                    </div>

                <div class="input-box">
                    <input type="password" class="registration-input-field" name="confirm_pass" id="confirm_pass" placeholder="Confirm Password" required>
                    <i class="fa-solid fa-lock"></i>
                    <a href="#" class="toggle_password" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></a>
                </div>
        </div>
            <div class="user_type_box input-box">
                
                <input type="radio" class="user_type" name="user_type" id="customer" value="customer">
                <label for="customer" class="user_type">Customer</label>
                
                <input type="radio" class="user_type" name="user_type" id="rider" value="rider">
                <label for="rider" class="user_type">Rider</label>
            </div>

            <div class="input-box">
                <input type="submit" class="submit" name="register" id="register" value="Register">
            </div>
        </form>

            <div class="two-col">
                <div class="one">
                    <input type="checkbox" name="" id="register-check">
                    <label for="register-check">Remember Me</label>
                </div>

                <div class="two">
                    <label for="">
                        <a href="#">Terms & Conditions</a>
                    </label>
                </div>
            </div>
            </div>


    </div>
</div>


<?php include '../basic_php/footer.php' ;?>

    <script>

        var a= document.getElementById("login-btn");
        var b= document.getElementById("register-btn");
        var c= document.getElementById("forgot-password-btn");
        var d= document.getElementById("write-code-btn");
        var e= document.getElementById("set-new-password-btn");
        

        var v= document.getElementById("login");
        var w= document.getElementById("register");
        var x= document.getElementById("forgot_password");
        var y= document.getElementById("write_code");
        var z= document.getElementById("set_new_password");


        
        function set_new_password(){
            v.style.left = "52.5rem";
            w.style.right = "-52.5rem"
            x.style.left = "-52.5rem";
            y.style.left = "-105rem";  
            z.style.left = "0.4rem";
        }
        function write_code(){
            v.style.left = "52.5rem";
            w.style.right = "-52.5rem"
            x.style.left = "-52.5rem";
            y.style.left = "0.4rem";  
            z.style.left = "-157.5rem";
        }
        function forgot_password(){
            v.style.left = "52.5rem";
            w.style.right = "-52.5rem"
            x.style.left = "0.4rem";
            y.style.left = "-105rem";  
            z.style.left = "-157.5rem";
        }
        function login(){
            v.style.left = "0.4rem";
            w.style.right = "-52.5rem";
            x.style.left = "-52.5rem";
            y.style.left = "-105rem";  
            z.style.left = "-157.5rem";
        }
        function register(){
            v.style.left = "-51rem";
            w.style.right = "0.5rem";
            x.style.left = "-52.5rem";
            y.style.left = "-105rem";  
            z.style.left = "-157.5rem";
        }
        


       /* function togglePassword() {
            var input = document.getElementById("password");
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }*/

        function togglePassword() {
            var passwordFields = ["password", "confirm_pass"];

            passwordFields.forEach(function (id) {
            var input = document.getElementById(id);
            if (input) {
                input.type = input.type === "password" ? "text" : "password";
                }
            });
        }
        

    </script>



<script src="../javascript_files/prevent_access.js"></script>
    
</body>
</html>