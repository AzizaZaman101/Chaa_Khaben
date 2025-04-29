<?php include '../basic_php/connection.php' ; ?>
<<<<<<< HEAD
<!DOCTYPE html> 
=======
<!DOCTYPE html>
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha_Khaben</title>
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<<<<<<< HEAD
<body class=""> 
=======
<body class="">
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
    

<!--This is the header/navigation bar section -->
    <header class="header">
        
        <a class="logo"><i class="fa-solid fa-mug-hot"></i> চা খাবেন?</a>

        <nav class="navbar">
            <a href="./index-admin.php" class="active">Home</a>
            <a href="./display-product-admin.php">Products</a>
            <a href="./orders-admin.php">Pending Orders</a>
            <a href="./riders-info.php">Riders Info</a>
        </nav>
        
        <nav class="icons">
            <form action="" class="search_form">
                <input type="search" placeholder="Search here..." class="search_box">
                <i class="fa fa-search" id="search_icon"></i>
            </form>
            <i id="menu-bars" class="fa-solid fa-bars"></i>

            <div class="dropdown_user">
                <i id="user" class="fa-solid fa-user"></i>
                <ul class="dropdown">

                    <li><a href="./profile-admin.php"  id="profile-btn"><i class="fa-solid fa-user"></i>Profile</a></li>

                    <li><a href="./admin-audit-history.php"  id="history-btn"><i class="fa-solid fa-clock-rotate-left"></i>History</a></li>

                    <li><a href="./admin-approved-orders.php"  id="history-btn"><i class="fa-solid fa-clock-rotate-left"></i>Your Approved Orders</a></li>

                    <li><a href="./all-approved-orders.php"  id="history-btn"><i class="fa-solid fa-clock-rotate-left"></i>All Orders</a></li>


<<<<<<< HEAD
                    <li><a href="./logout-admin.php" id="log-out-btn"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a></li>
=======
                    <li><a href="../regular/index.php" id="log-out-btn" onclick="register()"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a></li>
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
                </ul>
            </div>
                    
        </nav>
    </header>
