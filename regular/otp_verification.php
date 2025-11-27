<?php
session_start();
include '../basic_php/connection.php'; 

require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$Mail = new PHPMailer(true);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_otp = $_POST['otp'];
    $user_id = $_SESSION['user_id'];  // Assuming user_id is stored in session after login

    // Fetch the stored OTP and expiry from double_verification table
    $sql = "SELECT otp, expired_at FROM double_verification WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $stored_otp = $data['otp'];
        $expired_at = strtotime($data['expired_at']);

        // Check if OTP is valid and hasn't expired
        if ($user_otp == $stored_otp && $expired_at >= time()) {
            $_SESSION['user_id'] = $user_id;  // Store user ID in session
            unset($_SESSION['temp_user']);  // Clear temporary user session data if OTP is correct

            // Redirect based on user type
            $query = "SELECT user_type FROM user WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $userResult = $stmt->get_result();
            $user = $userResult->fetch_assoc();

            if ($user['user_type'] == 'customer') {
                $customerQuery = "SELECT customer_id FROM customer WHERE user_id = ?";
                $stmt = $conn->prepare($customerQuery);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $customerResult = $stmt->get_result();

                if ($customerResult->num_rows == 1) {
                    $customer = $customerResult->fetch_assoc();
                    $_SESSION['customer_id'] = $customer['customer_id']; // Store customer_id in session
                } else {
                    echo "Error: Customer record not found!";
                    exit();
                }
                header('Location: ../customer/index-customer.php');
            } elseif ($user['user_type'] == 'rider') {
                $riderQuery = "SELECT rider_id FROM rider WHERE user_id = ?";
                $stmt = $conn->prepare($riderQuery);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $riderResult = $stmt->get_result();

                if ($riderResult->num_rows == 1) {
                    $rider = $riderResult->fetch_assoc();
                    $_SESSION['rider_id'] = $rider['rider_id']; // Store rider_id in session
                } else {
                    echo "Error: Rider record not found!";
                    exit();
                }
                header('Location: ../rider/index-rider.php');
            } else {
                $adminQuery = "SELECT admin_id FROM admin WHERE user_id = ?";
                $stmt = $conn->prepare($adminQuery);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $adminResult = $stmt->get_result();

                if ($adminResult->num_rows == 1) {
                    $admin = $adminResult->fetch_assoc();
                    $_SESSION['admin_id'] = $admin['admin_id']; // Store admin_id in session
                } else {
                    echo "Error: Admin record not found!";
                    exit();
                }
                header('Location: ../admin/index-admin.php');
            }
            exit();
        } 
        
        elseif (isset($_POST['resend'])) {

            $query = "SELECT email FROM user WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $userResult = $stmt->get_result();
            $user = $userResult->fetch_assoc();

            // Resend OTP logic
            $email = $user['email'];
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            date_default_timezone_set('Asia/Dhaka');
            $expired_at = date("Y-m-d H:i:s", strtotime('+5 minutes'));

            // Insert OTP into double_verification table
            $insert_otp_query = "INSERT INTO double_verification (user_id, otp,expired_at) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_otp_query);
            $stmt->bind_param("iss", $_SESSION['user_id'], $otp, $expired_at);
            $stmt->execute();

    

            // Send the OTP via email
            $Mail = new PHPMailer(true); 

            //here make sure to put your email and app password in order to use the double verification feature
            try {
                // Server settings
                $Mail->isSMTP();                                      
                $Mail->Host       = 'smtp.gmail.com';                 
                $Mail->SMTPAuth   = true;                             
                $Mail->Username   = 'zamanaziza5@gmail.com';            
                $Mail->Password   = '';        
                $Mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   
                $Mail->Port       = 587;                              
            
                // Recipients
                $Mail->setFrom('zamanaziza5@gmail.com', 'Chaa Khaben');
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
        
        
        else {
            echo "<script>alert('OTP is either invalid or has expired. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No OTP found. Please request a new OTP.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Step Verification</title>
    <style type="text/css">
        #container {
            border: 1px solid black;
            width: 400px;
            margin-left: 400px;
            margin-top: 50px;
            height: 330px;
        }
        form {
            margin-left: 50px;
        }
        p {
            margin-left: 50px;
        }
        h1 {
            margin-left: 50px;
        }
        input[type=number] {
            width: 290px;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            background-color: orange;
            border: 1px solid orange;
            width: 100px;
            padding: 9px;
            margin-left: 100px;
        }
        button:hover {
            cursor: pointer;
            opacity: .9;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Two-Step Verification</h1>
        <p>Enter the 6 Digit OTP Code that has been sent to your email address:</p>
        <form method="post" action="otp_verification.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
        <?php
            // Check if OTP has expired and display the resend button
            $sql = "SELECT expired_at FROM double_verification WHERE user_id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $expired_at = strtotime($data['expired_at']);
                if ($expired_at < time()) {
                    // OTP has expired, show the resend button
                    echo '<form method="post" action="otp_verification.php">
                            <button type="submit" name="resend" class="resend-btn">Resend OTP</button>
                          </form>';
                }
            }
        ?>
    </div>
</body>
</html>
