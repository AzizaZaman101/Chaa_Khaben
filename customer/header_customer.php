<?php include '../basic_php/connection.php' ; 


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha_Khaben</title>
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>


    <!--This is the header/navigation bar section -->
    <header class="header">

        <a class="logo"><i class="fa-solid fa-mug-hot"></i> চা খাবেন?</a>

        <nav class="navbar">
            <a href="./index-customer.php" class="active">Home</a>
            <a href="./display_products.php">Menu</a>
            <a href="./gift-card-form-customer.php">Gift card</a>
            <a href="./orders-customer.php">Orders</a>
            <a href="#about_us">About Us</a>
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
                    <li><a href="./profile-customer.php" id="login-btn" onclick="login()"><i id="user" class="fa-solid fa-user"></i>Profile</a></li>

                    <li><a href="./logout.php" id="log-out-btn"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a></li>
                </ul>
            </div>

            <a href="./wish-list-customer.php">
                <i id="wish-list" class="fa-regular fa-heart"></i>
            </a>

            <div class="shopping">
                <i id="shopping-cart" class="fa-solid fa-cart-shopping"></i>
                <span class="quantity">0</span>
            </div>
        </nav>
    </header>

    <!--This is the add to cart section -->
    <section class="cart-container">
        <h1 class="cart_heading">Cart</h1>
        <ul class="list-cart"></ul>

        <div class="total-container">
            <p class="total_line">Total :</p>
            <p class="total">0 BDT</p>
        </div>

        <div class="checkout-container">
            <a href="./view-cart.php" class="view-cart">View cart</a>
            <a href="./checkout-customer.php" class="checkout">Checkout</a>
        </div>
    </section>