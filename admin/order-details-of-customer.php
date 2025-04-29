<?php
include '../basic_php/connection.php';
session_start();

// Ensure only admins can view this page
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../regular/index.php");
    exit();
} 

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
 
$admin_id = $_SESSION['admin_id'];

// Get the order_id from the URL
$order_id = $_GET['order_id'];

// Fetch ordered products for the given order_id
$query = "
SELECT 
    op.product_id,
    p.product_name,
    p.image,
    p.product_price,
    op.quantity
FROM ordered_products op
JOIN product p ON op.product_id = p.product_id
WHERE op.order_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the order details (customer, payment, etc.)
$order_details_query = "
SELECT 
    o.order_id,
    o.order_date,
    c.customer_id,
    concat(u.fname, ' ', u.lname) AS customer_name,
    u.phone,
    c.full_address,
    p.payment_method,
    p.total_amount
FROM orders o
JOIN customer c ON o.customer_id = c.customer_id
JOIN user u ON c.user_id = u.user_id
JOIN payment p ON o.order_id = p.order_id
WHERE o.order_id = ?
";

$order_stmt = $conn->prepare($order_details_query);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order_info = $order_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/admin-audit-history.css">
</head>
<body>
<?php include './header_admin.php'; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="./orders-admin.php">Pending Orders</a>
        <span>›</span>
        <a href="#">Order Details</a>
</nav>

<h1>Order Details for Order #<?php echo $order_info['order_id']; ?></h1>

<!-- Order Information -->
<div>
    <h3>Customer Information:</h3>
    <p><strong>Name:</strong> <?php echo $order_info['customer_name']; ?></p>
    <p><strong>Phone:</strong> <?php echo $order_info['phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $order_info['full_address']; ?></p>

    <h3>Payment Information:</h3>
    <p><strong>Payment Method:</strong> <?php echo $order_info['payment_method']; ?></p>
</div>

<!-- Ordered Products -->
<h3>Ordered Products:</h3>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php

    while ($product = $result->fetch_assoc()) {
        $total = $product['product_price'] * $product['quantity'];
        ?>
        <tr>
            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
            <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="100"></td>
            <td><?php echo htmlspecialchars($product['product_price']); ?> BDT</td>
            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
            <td><?php echo htmlspecialchars($total); ?> BDT</td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Total Amount -->
<div class="total-checkout">
<h2><strong>Total Amount Paid : </strong><?php echo htmlspecialchars($order_info['total_amount']); ?> BDT</h2>
</div>

</main>

<?php include '../basic_php/footer.php'; ?>
<script src="../javascript_files/script.js"></script>
</body>
</html>
