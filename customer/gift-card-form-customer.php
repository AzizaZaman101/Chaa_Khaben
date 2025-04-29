<?php
include '../basic_php/connection.php';

session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['customer_id'])) {
    die("Error: User not logged in.");
}

$customer_id = $_SESSION['customer_id'];

// Fetch user details except password and user_type
$sql = "SELECT u.image, u.email, u.fname, u.lname, u.phone, c.full_address, c.city_corporation_id, c.upazila_id 
FROM user u JOIN customer c ON u.user_id=c.user_id 
WHERE c.customer_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/gift-card-form.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <script src="https://kit.fontawesome.com/YOUR_FONT_AWESOME_KIT.js" crossorigin="anonymous"></script>
    <title>profile</title>
</head>
<body>

    <?php include './header_customer.php';?>

<main>
    <nav class="breadcrumb-nav">
        <a href="./index-customer.php">Home</a>
        <span>›</span>
        <a href="#">Gift Card</a>
    </nav>

    <h1>Gift Card</h1>

    <div class="profile-container">
        <form id="profile-form">
         <div class="profile-field">
            <label for="gift_amount">Select Gift Amount:</label>
            <input type="number" id="gift_amount" name="gift_amount" 
            value=""  placeholder="In Taka">
         </div>

        <div class="profile-field">
            <label for="receiver_name">Who are you gifting to?</label>
            <input type="text" id="receiver_name" name="receiver_name" 
            value="" placeholder="Recipient Name">
        </div>

        <div class="profile-field">
            <input type="email" id="receiver_mail" name="receiver_mail" 
            value="" placeholder="Recipient Mail">
        </div>

        <div class="profile-field">
            <label for="personal_note">Personal Note:</label>
            <input type="text" id="personal_note" name="personal_note" 
            value="" placeholder="Message (Optional)"> 
        </div>

        <div class="profile-field">
            <label for="receiver_name">From:</label>
            <input type="text" id="sender_name" name="sender_name" 
            value="" placeholder="Sender Name">
        </div>

        <div class="profile-field">

            <input type="email" id="sender_mail" name="sender_mail" 
            value="" placeholder="Sender Mail">
        </div>

        <div class="check_balance profile-field">
        <label for="check-balance">Have A Gift card?</label>
        <a href="./gift-card-check-balance.php"  id="check-balance" class="check-balance">Check Balance</a>
        </div>

        </form>
    </div>
    <a href="./gift_card_customer.php"  class="check-out">Checkout</a>


</main>



<?php include '../basic_php/footer.php' ; ?>
<<<<<<< HEAD
<?php include '../javascript_files/prevent_access.js'; ?>
=======
<script>
    // Prevent back button from showing cached page
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.href = "../regular/index.php";
        }
    });
</script>
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
    
</body>
</html>