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

<?php include'./header_customer.php' ; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index-customer.php">Home</a>
        <span>›</span>
        <a href="#">View Cart</a>
    </nav>
<h1>Your Cart</h1>

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
            <td><a href="./remove-from-cart.php?product_id=<?= $row['product_id'] ?>" class="details-btn">Remove</a></td>
        </tr>
        <?php $total += $row['qty'] * $row['product_price']; ?>
    <?php endwhile; ?>
</table>


<div class="total-checkout">
    <h2>Total : ৳ <?= $total ?></h2>
    <a href="./checkout-to-pay.php" class="check_out">Proceed to Checkout</a>
</div>

</main>

<?php include '../basic_php/footer.php'; ?>
<?php include '../javascript_files/prevent_access.js'; ?>
</body>
</html>





