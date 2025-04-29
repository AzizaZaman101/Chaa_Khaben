<?php
include '../basic_php/connection.php';
session_start();

$show_login_message = false;

if (!isset($_SESSION['customer_id'])) {
    $show_login_message = true;
} 

$total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/admin-audit-history.css">
</head>
<body>

<?php include'./header.php' ; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index.php">Home</a>
        <span>›</span>
        <a href="#">View Cart</a>
    </nav>
<h1>Your Cart</h1>
<?php if ($show_login_message): ?>
        <div class="login-warning-div">
            <p class="login-warning">Please login first to see your Cart.</p>
        </div>


<div class="total-checkout">
    <h2>Total : ৳ <?= $total ?></h2>
    <a href="./checkout.php" class="check_out">Proceed to Checkout</a>
</div>
<?php endif; ?>

</main>

<?php include '../basic_php/footer.php'; ?>
    
</body>
</html>





