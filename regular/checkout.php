<?php
include '../basic_php/connection.php';
session_start();

$show_login_message = false;

if (!isset($_SESSION['customer_id'])) {
    $show_login_message = true;
} else {
$customer_id = $_SESSION['customer_id'];

$sql = "
    SELECT p.product_name, p.product_price, p.image, ci.qty, p.product_id
    FROM cart c
    JOIN cart_item ci ON c.cart_id = ci.cart_id
    JOIN product p ON ci.product_id = p.product_id
    WHERE c.customer_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out</title>
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
        <a href="#">Check Out</a>
    </nav>
<h1>Check Out</h1>

<?php if ($show_login_message): ?>
        <div class="login-warning-div">
    <p class="login-warning">Please login first to buy.</p>
    </div>
<?php elseif ($result->num_rows > 0): ?>

<table>
    <tr>
        <th>Product</th>
        <th>Name</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <img src="<?= $row['image'] ?>" width="50">
            </td>
            <td><?= $row['product_name'] ?></td>
            <td><?= $row['qty'] ?></td>
            <td>৳<?= $row['product_price'] ?></td>
            <td>৳<?= $row['qty'] * $row['product_price'] ?></td>
            <td><a href="remove-from-cart.php?product_id=<?= $row['product_id'] ?>" class="details-btn">Remove</a></td>
        </tr>
        <?php $total += $row['qty'] * $row['product_price']; ?>
    <?php endwhile; ?>
</table>

<a href="./display_products.php" class="continue-shopping"><i class="fa-solid fa-backward"></i> Continue Shopping </a>

<div class="total-coupon">
    <p class="total-heading">Sub Total : ৳ <?= $total ?></p>
    
    <p class="casual-description"><i>Shipping & taxes calculated at checkout</i></p>

    <a href="./checkout-to-pay.php" class="checkout-btn">CHECKOUT</a>
</div>

<?php endif; ?>
</main>

<?php include '../basic_php/footer.php'; ?>

</body>
</html>