<?php include '../basic_php/connection.php';

// Ensure only riders can view this page
session_start();
if (!isset($_SESSION['rider_id'])) {
    header("Location: ../regular/index.php");
    exit();
} 

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha_Khaben</title>
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="">
    
 
<?php include './header_rider.php';?>


<!--This is the hero section/to show to the picture of the very first page featuring some heading tags and a button -->

<!--This is the banner section with some interesting fact about teas -->    
<?php include '../basic_php/hero-banner.php';?>


    
<!--This is the category section -->   
<?php include './category-display_rider.php';?>
  


<!--This is the About us/Why choose us section -->    
<?php include '../basic_php/about-us.php';?>



<!--This is the Footer section -->   
<?php include '../basic_php/footer.php';?>
    
<!-- -->
<script src="../javascript_files/script.js"></script>
<?php include '../javascript_files/prevent_access.js'; ?>

</body>
</html>